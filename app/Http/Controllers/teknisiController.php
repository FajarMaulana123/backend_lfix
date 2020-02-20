<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\M_User;
use App\M_Barang;
use App\M_Estimasi;
use App\M_Service;
use App\M_Rating;

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
