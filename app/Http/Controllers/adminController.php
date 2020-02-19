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
        $data = new M_Barang();
        $data->kode_barang = $request->input('kode_barang');
        $data->jenis_barang = $request->input('jenis_barang');
        $data->icon = $request->input('icon');
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

    public function updatebarang(Request $request, $id){
        $data = M_Barang::where('id_barang', $id)->first();
        $data->kode_barang = $request->input('kode_barang');
        $data->jenis_barang = $request->input('jenis_barang');
        $data->icon = $request->input('icon');
        $data->save();

        if ($data) {
          return response()->json([
            'success' => true,
            'message' => 'data diupdate',
            'data' => $data
          ], 200);
        } else {
          return response()->json([
            'success' => false,
            'message' => 'data tidak diupdate',
            'data' => ''
          ], 404);
        }
    }

    public function deletebarang(Request $request, $id){
        $data = M_Barang::where('id_barang', $id)->first();
        $data->delete();
        if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'data dihapus',
                'data' => $data
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
        $data->jenis_barang = $request->input('jenis_barang');
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
        $data = M_Estimasi::where('id_estimasi', $id)->first();
        $data->kode_barang = $request->input('kode_barang');
        $data->est_kerusakan = $request->input('est_kerusakan');
        $data->harga = $request->input('harga');
        $data->jenis_barang = $request->input('jenis_barang');
        $data->save();

        if ($data) {
          return response()->json([
            'success' => true,
            'message' => 'data diupdate',
            'data' => $data
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
        $data = M_Estimasi::where('id_estimasi', $id)->first();
        $data->delete();
        if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'data dihapus',
                'data' => $data
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

    public function tipeestimasi($kode_barang){
        $data = M_Estimasi::where('kode_barang', $kode_barang)->get();

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
        $data = new M_Teknisi();
        $data->t_nama = $request->input('t_nama');
        $data->t_email = $request->input('t_email');
        $data->t_alamat = $request->input('t_alamat');
        $data->t_hp = $request->input('t_hp');
        $data->t_keahlian = $request->input('t_keahlian');
        $data->t_ktp = $request->input('t_ktp');
        $data->t_selfi = $request->input('t_selfi');
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

}
