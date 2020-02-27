<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\M_User;
use App\M_Barang;
use App\M_Teknisi;
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

    public function login(Request $request){
        $hp = $request->input('no');
        // dd($hp);
        $data = M_Teknisi::where('t_hp', '=', $hp)->first();
        
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
    
    public function dataservice(){
        
        $service = M_Service::join('users', 'service.id', '=', 'users.id')
        ->join('barang', 'service.kode_barang', '=', 'barang.kode_barang')
        ->where('service.id_teknisi', '=', null)
        ->select('service.id_service', 'service.id', 'service.kode_service', 'service.kode_barang', 'service.lokasi',
        'users.name', 'users.phone',
        'barang.kode_barang', 'barang.jenis_barang')
        ->get();

        $coba = $service->groupBy('id_service');
        foreach ($coba as $services){
            $i = 0;
            $data[] = [
                'idService' => $services[$i]->id_service,
                'kode_service' => $services[$i]->kode_service,
                'kode_barang' => $services[$i]->kode_barang,
                'kategori' => $services[$i]->jenis_barang,
                'lokasiservice' => $services[$i]->lokasi,
                'namauser' => $services[$i]->name,
                'no_hp' => $services[$i]->phone,
              ];
  
          $i++;
          }     

        if($service){
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

    // public function pergi (Request $request){
    //     $kode_service = $request->input('kode_service');
    //     $data = M_Service::where('kode_service', $kode_service)->update([
    //         'status_teknisi' => 'On the way',
    //     ]);
    //     if($data){
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'teknisi on the way',
    //         ], 200);
    //     } else {
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'data tidak ditemukan',
    //         'data' => ''
    //     ], 404);
    //     }
        
    // }

    public function kerusakan(Request $request){
        // $kode_service = $request->input('kode_service');
        // $kerusakan = $request->input('kerusakan');
        // $harga = $request->input('harga');
        $kerusakan = $request->input('data');
        $total = 0;
        // dd($data);
        foreach ($kerusakan as $tt){
            $data = new M_Kerusakan;
            $data->kode_service = $tt['kode_service'];
            $data->harga = $tt['harga'];
            $data->kerusakan = $tt['kerusakan'];
            $data->save();
            $total += $tt['harga']; 
            $kode = $tt['kode_service'];
        }

        $service = M_Service::where('kode_service', $kode)->update([
            'status_teknisi' => 'Need confirmation',
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

    
    // public function doingservice(Request $request){
    //     $kode_service = $request->input('kode_service');
    //     $data = M_Service::where('kode_service', $kode_service)->update([
    //         'status_teknisi' => 'Doing service',
    //     ]);
    //     if($data){
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'teknisi dalam proses pengerjaan',
    //         ], 200);
    //     } else {
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'data tidak ditemukan',
    //         'data' => ''
    //     ], 404);
    //     }
    // }

    public function datakerusakan(Request $request){
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
        $status_teknisi = 'Done service';
        $status_garansi = 'Valid';
        $tgl2 = date('Y-m-d', strtotime('+2 weeks', strtotime($date)));
        $data = M_Service::where('kode_service', $kode_service)->update([
            'end_date' => $date,
            'status_service' => $status,
            'status_garansi' => $status_garansi,
            'valid_until' => $tgl2,
            'status_teknisi' => $status_teknisi,
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

    // public function rating(Request $request){
    //     $kode_service = $request->input('kode_service');
    //     $data = M_Rating::where('kode_service', $kode_service)->first();
    //     if($data){
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'data ditemukan',
    //         'data' => $data
    //     ], 200);
    //     } else {
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'data tidak ditemukan',
    //         'data' => ''
    //     ], 404);
    //     }
    // }

}
