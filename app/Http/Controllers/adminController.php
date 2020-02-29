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

class adminController extends Controller
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

    public function barang(){
        $data = M_Barang::all();

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

    
    public function addbarang(Request $request){
        $gam_produk = $request->file('icon');
        $namefile = $gam_produk->getClientOriginalName();
        $gam_produk->move(public_path('images'),$namefile);

        $data = new M_Barang();
        $data->kode_barang = $request->input('kode_barang');
        $data->jenis_barang = $request->input('jenis_barang');
        $data->icon = $namefile;
        $data->save();
        // dd($data->kode_barang);

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

    public function updatebarang(Request $request, $kode_barang){
        $jenis_barang = $request->input('jenis_barang');
        $data = M_Barang::find($kode_barang);
        $data->update([
            'jenis_barang' => $jenis_barang,
        ]);

        if ($request->hasFile('icon'))
        {
            $gam_produk = $request->file('icon');
            $namefile = $gam_produk->getClientOriginalName();
            $gam_produk->move(public_path('images'),$namefile); 
            $data->icon = $namefile;
            $data->save();             
        }   

        if ($data) {
          return response()->json([
            'success' => true,
            'message' => 'data barang diupdate',
          ], 200);
        } else {
          return response()->json([
            'success' => false,
            'message' => 'data tidak diupdate',
            'data' => ''
          ], 404);
        }
    }

    public function deletebarang(Request $request, $kode_barang){
        $data = M_Barang::where('kode_barang', $kode_barang)->delete();
        if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'data dihapus',
            ], 200);
        } else {
          return response()->json([
              'success' => false,
              'message' => 'data tidak dihapus',
              'data' => ''
          ], 404);
        }
    }

    public function addestimasi(Request $request){
        $data = new M_Estimasi();
        $data->kode_barang = $request->input('kode_barang');
        $data->est_kerusakan = $request->input('est_kerusakan');
        $data->harga = $request->input('harga');
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

    public function updateestimasi(Request $request, $id){
      $est_kerusakan = $request->input('est_kerusakan');
      $harga = $request->input('harga');  
      $data = M_Estimasi::where('id_estimasi', $id)->update([
            'est_kerusakan' => $est_kerusakan,
            'harga' => $harga,
        ]);

        if ($data) {
          return response()->json([
            'success' => true,
            'message' => 'data diupdate',
          ], 200);
        } else {
          return response()->json([
            'success' => false,
            'message' => 'data tidak diupdate',
            'data' => ''
          ], 404);
        }
    }

    public function deleteestimasi(Request $request, $id){
        $data = M_Estimasi::where('id_estimasi', $id)->delete();
        if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'data dihapus',
            ], 200);
        } else {
          return response()->json([
              'success' => false,
              'message' => 'data tidak dihapus',
              'data' => ''
          ], 404);
        }
    }
    
    public function estimasi(){
        $data = M_Estimasi::all();

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

     
    public function daftarteknisi(Request $request){
        $t_selfi = $request->file('t_selfi');
        $namefile = $t_selfi->getClientOriginalName();
        $t_selfi->move(public_path('images/teknisi'),$namefile);

        $t_ktp = $request->file('t_ktp');
        $ktp = $t_ktp->getClientOriginalName();
        $t_selfi->move(public_path('images/teknisi'),$ktp);

        $data = new M_Teknisi();
        $data->t_nama = $request->input('t_nama');
        $data->t_email = $request->input('t_email');
        $data->t_alamat = $request->input('t_alamat');
        $data->t_hp = $request->input('t_hp');
        $data->t_keahlian = $request->input('t_keahlian');
        $data->t_ktp = $ktp;
        $data->t_selfi = $namefile;
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

    public function teknisi(){
      $data = M_Teknisi::all();

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

    Public function service(){
      $service = M_Service::join('users', 'service.id', '=', 'users.id')
      ->join('barang', 'service.kode_barang', '=', 'barang.kode_barang')
      ->join('teknisi', 'service.id_teknisi', '=', 'teknisi.id_teknisi')
      ->select('service.id_service', 'service.id', 'service.kode_service', 'service.kode_barang', 'service.lokasi',
      'users.name', 'teknisi.t_nama', 'service.start_date', 'service.status_service',
      'barang.kode_barang', 'barang.jenis_barang')
      ->get();

      if(count($service) != 0){
        return response()->json([
            'success' => true,
            'message' => 'data ditemukan',
            'data' => $service
        ], 200);
      } else {
        return response()->json([
            'success' => false,
            'message' => 'data tidak ditemukan',
            'data' => ''
        ], 404);
      }
    }

    public function detail_service(Request $request, $kode_service){
        $data = M_Service::leftjoin('teknisi', 'service.id_teknisi', '=', 'teknisi.id_teknisi')
        ->join('users', 'service.id', '=', 'users.id')
        ->join('barang', 'service.kode_barang', '=', 'barang.kode_barang')
        ->leftjoin('kerusakan', 'service.kode_service', '=', 'kerusakan.kode_service')
        ->where('service.kode_service', $kode_service)
        ->select('service.id_service', 'service.id', 'service.id_teknisi', 'service.kode_service', 'service.kode_barang', 'service.lokasi',
        'service.total_harga', 'service.status_garansi', 'service.start_date', 'service.end_date','service.valid_until', 'service.status_service',
        'teknisi.id_teknisi', 'teknisi.t_nama', 'teknisi.t_alamat', 'teknisi.t_keahlian', 'teknisi.t_hp',
        'kerusakan.harga', 'kerusakan.kerusakan',
        'barang.kode_barang', 'barang.jenis_barang')
        ->get();
        if($data){
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

    public function users(){
        $data = M_Users::all();
        if($data){
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

    public function dashboard(){
        $barang = M_Barang::all();
        $service = M_Service::all();
        $teknisi = M_Teknisi::all();

        $data = [
          'service' => $service,
          'teknisi' => $teknisi,
          'barang' => $barang,
        ];
        
        if(count($data) != 0){
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

}
