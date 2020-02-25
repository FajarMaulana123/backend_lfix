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
use App\M_Kerusakan;

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
        ->select('service.id_service', 'service.id', 'service.id_teknisi', 'service.kode_service', 'service.kode_barang', 'service.lokasi',
        'service.total_harga', 'service.status_garansi', 'service.start_date', 'service.end_date', 'service.valid_until', 'service.status_service',
        'teknisi.id_teknisi', 'teknisi.t_nama', 'teknisi.t_alamat', 'teknisi.t_keahlian',
        'kerusakan.harga', 'kerusakan.kerusakan',
        'barang.kode_barang', 'barang.jenis_barang')
        ->get();

        // dd($service);
        $coba = $service->groupBy('id_service');
        // dd($coba->kode_service);


        
        // $i =0;
        // foreach ($coba as $services){
        //   $damege[$i] = [
        //     'jenis' => $services->kerusakan,
        //     'harga' => $services->harga,
        //   ];
        //   $i++;
        // } 

        
        // dd($kerusakan);
        
        foreach ($coba as $services){
          $i = 0;
          // $kerusakan = M_Kerusakan::where('kode_service', $service[]->kode_service)->get();

          for ($a=0; $a < sizeof($services); $a++) { 
            $damege[$a] = [
               'jenis' => $services[$a]->kerusakan,
               'harga' => $services[$a]->harga
            ];
            
          }

          // dd($service[$i]->kode_service);
          // dd($damege);
          // $tgl = $services[$i]->end_date;
          // $tgl2 = date('Y-m-d', strtotime('+1 month', strtotime($tgl)));
         
          // if(date('Y-m-d') < $tgl2){
          //   $status_garansi = 'valid';
          // } else {
          //   $status_garansi = 'expire';
          // }

          $data[] = [
            'idService' => $services[$i]->id_service,
            'status' => $services[$i]->status_service,
            'kode_service' => $services[$i]->kode_service,
            'kode_barang' => $services[$i]->kode_barang,
            'kategori' => $services[$i]->jenis_barang,
            'lokasiPelanggan' => $services[$i]->lokasi,
            'startDate' => $services[$i]->start_date,
            'endDate' => $services[$i]->end_date,
            'teknisi' => [
                'namaTeknisi' => $services[$i]->t_nama,
                'lokasiTeknisi' => $services[$i]->t_alamat,
                'specialist' => $services[$i]->t_keahlian,
            ],
            'damage' => $damege,
            'guarantee' => [
                'id_guarantee' => $services[$i]->id_service,
                'id_service' => $services[$i]->id_service,
                'status' => $services[$i]->status_garansi,
                'valid_until' => $services[$i]->valid_until,
            ]
                
            
          ];
          $damege = [];

        $i++;
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

    public function confirm_teknisi(Request $request){

        $id_service = $request->input('id_service');
        $start_date = date('Y-m-d');
        $status = 'On Process';
        
        $data = M_Service::where('id_service', $id_service)->update([
            'start_date' => $start_date,
            'status_service' => $status,
        ]);

        if ($data) {
          return response()->json([
              'success' => true,
              'message' => 'data disimpan',
              'data' => 'Service telah diterima'
          ], 200);
        } else {
          return response()->json([
              'success' => false,
              'message' => 'data tidak ditemukan',
              'data' => ''
          ], 404);
        }
        

    }

    public function cancel_teknisi(Request $request){
        $id_service = $request->input('id_service');
        $data = M_Service::where('id_service', $id_service)->update([
            'id_teknisi' => null,
        ]);
        
        if ($data) {
          return response()->json([
              'success' => true,
              'message' => 'teknisi telah dicancel',
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

    public function cancel_damage(Request $request){
        $kode_service = $request->input('kode_service');
        $data = M_Kerusakan::where('kode_service', $kode_service)->delete();
        if ($data) {
          return response()->json([
              'success' => true,
              'message' => 'damage telah dicancel',
          ], 200);
        } else {
          return response()->json([
              'success' => false,
              'message' => 'data tidak ditemukan',
          ], 404);
        }    

    }

    public function confirm_damage(Request $request){
        $id = $request->input('id_service');
        $total = $request->input('total_harga');
        $data = M_Service::where('id_service', $id)->update([
            'total_harga' => $total,
        ]);
        if ($data) {
          return response()->json([
              'success' => true,
              'message' => 'damage telah disetujui',
          ], 200);
        } else {
          return response()->json([
              'success' => false,
              'message' => 'data tidak ditemukan',
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

    public function data_guarantee (Request $request){
        $id_user = $request->input('userId');
        $service = M_Service::leftjoin('teknisi', 'service.id_teknisi', '=', 'teknisi.id_teknisi')
        ->join('users', 'service.id', '=', 'users.id')
        ->join('barang', 'service.kode_barang', '=', 'barang.kode_barang')
        ->leftjoin('kerusakan', 'service.kode_service', '=', 'kerusakan.kode_service')
        ->where('service.id', $id_user )
        ->whereNotNull('service.status_garansi')
        ->select('service.id_service', 'service.id', 'service.id_teknisi', 'service.kode_service', 'service.kode_barang', 'service.lokasi',
        'service.total_harga', 'service.status_garansi', 'service.start_date', 'service.end_date', 'service.status_service',
        'teknisi.id_teknisi', 'teknisi.t_nama', 'teknisi.t_alamat', 'teknisi.t_keahlian',
        'kerusakan.harga', 'kerusakan.kerusakan',
        'barang.kode_barang', 'barang.jenis_barang')
        ->get();
        
        $coba = $service->groupBy('id_service');
        
        
        
        
        foreach ($coba as $services){
          $i = 0;
          $dd = $services[$i]->status_garansi;
          // dd($dd);
          // if($services[$i]->status_garansi != null){
            for ($a=0; $a < sizeof($services); $a++) { 
              $damege[$a] = [
                 'jenis' => $services[$a]->kerusakan,
                 'harga' => $services[$a]->harga
              ];
              
            }
            // if($services[$i]->status_garansi == null){
            //   if ($service) {
            //     return response()->json([
            //         'success' => true,
            //         'message' => 'data kosong',
                    
            //     ], 200);
            //   }
            // }

          
            // if ($services[$i]->status_garansi != null){
            $data[] = [
              'id_guarantee' => $services[$i]->id_service,
              'status' => $services[$i]->status_garansi,
              'valid_until' => $services[$i]->end_date,
              'service' => [
                'idService' => $services[$i]->id_service,
                'status' => $services[$i]->status_service,
                'kategori' => $services[$i]->jenis_barang,
                'lokasiPelanggan' => $services[$i]->lokasi,
                'startDate' => $services[$i]->start_date,
                'endDate' => $services[$i]->end_date,
                'teknisi' => [
                    'namaTeknisi' => $services[$i]->t_nama,
                    'lokasiTeknisi' => $services[$i]->t_alamat,
                    'specialist' => $services[$i]->t_keahlian,
                ],
                'damage' => $damege,
              ],
    
            ];
          
            

            $damege = [];
          // }

          

        $i++;
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

}
