<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\M_Barang;
use App\M_Estimasi;
use App\M_Teknisi;
use App\M_User;
use App\M_Service;
use App\M_Rating;

class userController extends Controller
{
    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     //
    // }

    // //

    public function register(Request $request){
        $data = new M_User();
        $data->name = $request->input('name');
        $data->email = $request->input('email');
        $data->phone = $request->input('phone');
        $data->save();

        if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'data disimpan',
                'data' => $data
            ], 200);
          } else {
            return response()->json([
                'success' => false,
                'message' => 'data tidak disimpan',
                'data' => ''
            ], 404);
          }
    }

    public function login(Request $request){
        // dd($request);
        // $data = new M_User();
        // $email = "coba";
        $data = M_User::where('phone',$request->phone)->first();
        // dd($data);

        if ($data) {
          return response()->json([
              'success' => true,
              'message' => 'data ditemukan',
              'data' => $data
          ], 200);
        } else {
          return response()->json([
              'success' => false,
              'message' => 'data tidak ditemukan',
              'data' => ''
          ], 404);
        }
    }

    public function kategoriBarang(){
        $data = M_Barang::join('estimasi', 'barang.kode_barang', '=', 'estimasi.kode_barang')
                ->orderBy('estimasi.est_kerusakan', 'ASC')
                ->get();

        $barang = $data->groupBy('jenis_barang');
        
        foreach ($barang as $key) {
          $i = 0;    
          

          for ($a=0; $a < sizeof($key); $a++) { 
            $jenis_kerusakan[$a] = [
               'nama' => $key[$a]->est_kerusakan,
               'harga' => $key[$a]->harga
            ];
          }

          $result[] = [
                  'name' => $key[$i]->jenis_barang,
                  'image' => 'localhost:8000/images/' . $key[0]->icon,
                  'jenis_kerusakan' => $jenis_kerusakan
              ];

          $jenis_kerusakan = [];
          
          $i++;
        }
        


        if ($data) {
          return response()->json([
              'success' => true,
              'message' => 'data ditemukan',
              'data' => $result
          ], 200);
        } else {
          return response()->json([
              'success' => false,
              'message' => 'data tidak ditemukan',
              'data' => ''
          ], 404);
        }
    }

    public function service(Request $request){
        
        $alamat = $request->input('lokasiPelanggan');
        $kategori = $request->input('kategori');
        $id = $request->input('userId');

        $barang = DB::table('estimasi')->where('jenis_barang',$kategori)->select('kode_barang')->first();
        $pemilik = DB::table('users')->where('id',$id)->first();
        // dd($pemilik);
        
        $date = new \DateTime('now');
        $tgl = $date->format('dmY');

        $kode_service = 'SV'.'-'.$tgl.'-'.$barang->kode_barang;

        $data = new M_Service();
        $data->kode_service = $kode_service;
        $data->status_service = 'Waiting';
        $data->id_user = $pemilik->id;
        $data->kode_barang = $barang->kode_barang;
        $data->lokasi = $alamat;
        $data->save();

        if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'data disimpan',
                'data' => $data
            ], 200);
          } else {
            return response()->json([
                'success' => false,
                'message' => 'data tidak disimpan',
                'data' => ''
            ], 404);
          }
    }

    public function delete_service(Request $request){
        
        $kode_service = $request->input('kode_service');

        $data = M_Service::where('kode_service', $kode_service)->first();
        $data->delete();

        if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'Success delete your services !'
            ], 200);
          } else {
            return response()->json([
                'success' => false,
                'message' => 'data tidak disimpan',
                'data' => ''
            ], 404);
          }
    }

    public function rating(Request $request){
        
        $kode_service = $request->input('kode_service');
        $reviewer = $request->input('reviewer');
        $rating = $request->input('rating');
        $feedback = $request->input('feedback');

        // $barang = DB::table('estimasi')->where('jenis_barang',$kategori)->select('kode_barang')->first();
        // $pemilik = DB::table('users')->where('id',$id)->first();
        // dd($pemilik);
        
        // $date = new \DateTime('now');
        // $tgl = $date->format('dmY');

        // $kode_service = $tgl .'-'. $barang->kode_barang.'-'.$pemilik->name;

        // dd($kode_service);
        
        // $kode_service = 
        $data = new M_Rating();
        $data->kode_service = $kode_service;
        $data->id_user = $reviewer;
        $data->rating = $rating;
        $data->feedback = $feedback;
        $data->save();

        if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'Rating Success !'
            ], 200);
          } else {
            return response()->json([
                'success' => false,
                'message' => 'data tidak disimpan',
                'data' => ''
            ], 404);
          }
    }

}
