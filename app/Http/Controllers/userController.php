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

use Carbon\Carbon;

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
                  'image' => 'localhost:8000/images/' . $key[$i]->icon,
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

        $barang = DB::table('barang')->where('jenis_barang',$kategori)->select('kode_barang')->first();
        $pemilik = DB::table('users')->where('id',$id)->first();
        // dd($pemilik);
        
        $akhir = M_Service::max('id_service');

        $kode = "SV".sprintf("%03s", abs($akhir + 1));

        $data = new M_Service();
        $data->kode_service = $kode;
        $data->status_service = 'Waiting';
        $data->id = $pemilik->id;
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

    public function dataservice(Request $request){
        $id_user = $request->input('userId');
      
        $service = M_Service::leftjoin('teknisi', 'service.id_teknisi', '=', 'teknisi.id_teknisi')
        ->join('users', 'service.id', '=', 'users.id')
        ->join('barang', 'service.kode_barang', '=', 'barang.kode_barang')
        ->leftjoin('kerusakan', 'service.kode_service', '=', 'kerusakan.kode_service')
        ->where('service.id', $id_user)
        ->get();

        // $coba = $service->groupBy('kode_barang');
        // dd($service);
        
        // $i =0;
        foreach ($service as $services){

          $data[] = [
            'idService' => $services->id_service,
            'status' => $services->status_service,
            'kode_barang' => $services->kode_barang,
            'kategori' => $services->jenis_barang,
            'lokasiPelanggan' => $services->lokasi,
            'startDate' => $services->start_date,
            'teknisi' => [
                'namaTeknisi' => $services->t_nama,
                'lokasiTeknisi' => $services->t_alamat,
                'specialist' => $services->t_keahlian,
            ],
            'damage' => [
                'jenis' => $services->kerusakan,
                'harga' => $services->harga,
            ],
          ];
          

        // $i++;
        }
        
        if ($service) {
          return response()->json([
              'success' => true,
              'message' => 'data ditemukan',
              'data' => $data,
          ], 200);
        } else {
          return response()->json([
              'success' => false,
              'message' => 'data tidak ditemukan',
              'data' => ''
          ], 404);
        }

    }

    public function confirm_service(Request $request){
        $id_service = $request->input('id_service');
        $start_date = date('d-m-Y');
        $status = 'On Process';
        
        $data = M_Service::where('id_service', $id_service)->update([
            'start_date' => $start_date,
            'status_service' => $status,
        ]);

        if ($data) {
          return response()->json([
              'success' => true,
              'message' => 'data disimpan',
              'data' => ''
          ], 200);
        } else {
          return response()->json([
              'success' => false,
              'message' => 'data tidak ditemukan',
              'data' => ''
          ], 404);
        }
        

    }

    public function cancel_service(Request $request){
        $id_service = $request->input('id_service');
        $data = M_Service::where('id_service', $id_service)->update([
            'id_teknisi' => null,
        ]);
        
        if ($data) {
          return response()->json([
              'success' => true,
              'message' => 'data disimpan',
              'data' => ''
          ], 200);
        } else {
          return response()->json([
              'success' => false,
              'message' => 'data tidak ditemukan',
              'data' => ''
          ], 404);
        }
        
    }

    public function rating(Request $request){
        
        $kode_service = $request->input('kode_service');
        $reviewer = $request->input('reviewer');
        $rating = $request->input('rating');
        $feedback = $request->input('feedback');

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
