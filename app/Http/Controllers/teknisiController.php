<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\M_User;
use App\M_Barang;
use App\M_Estimasi;
use App\M_Service;
use App\M_Rating;
use App\M_Kerusakan;

class teknisiController extends Controller
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

    public function dataservice(){
        $data = M_Service::where('id_teknisi', null)->get();
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

    public function ambilservice(Request $request){
        $kode_service = $request->input('kode_service');
        $id_teknisi = $request->input('id_teknisi');
        
        $data = M_Service::where('kode_service', $kode_service)->update([
            'id_teknisi' => $id_teknisi,
        ]);
        if($data){
            return response()->json([
                'success' => true,
                'message' => 'data tersimpan',
                'data' => 'service telah diambil'
            ], 200);
          } else {
            return response()->json([
                'success' => false,
                'message' => 'data tidak ditemukan',
                'data' => ''
            ], 404);
          }
    }

    public function kerusakan(Request $request){
        $kode_service = $request->input('kode_service');
        $kerusakan = $request->input('kerusakan');
        $harga = $request->input('harga');

        $data = new M_Kerusakan;
        $data->kode_service = $kode_service;
        $data->harga = $harga;
        $data->kerusakan = $kerusakan;
        $data->save();

        if($data){
        return response()->json([
            'success' => true,
            'message' => 'data tersimpan',
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

    public function datakerusakan(){
        $kode_service = $request->input('kode_service');
        $data = M_Kerusakan::where('kode_service', $kode_service)->get();
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

    public function servicedone(Request $request){
        $kode_service = $request->input('kode_service');
        $date = date('Y-m-d');
        $status = 'Done';
        $total = $request->input('total_harga');
        $data = M_Service::where('kode_service', $kode_service)->update([
            'end_date' => $date,
            'status' => $status,
            'total_harga' => $total,
        ]);
        if($data){
        return response()->json([
            'success' => true,
            'message' => 'data tersimpan',
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
        $data = M_Rating::where('kode_service', $kode_service)->first();
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

}
