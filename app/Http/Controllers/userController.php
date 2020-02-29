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
use GuzzleHttp\Client;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

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
        
        $data = M_User::where('phone',$request->input('phone'))->first();
        
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

    public function updateToken(Request $request){

        $data = M_User::where('id',$request->input('userId'))->update([
            'remember_token' => $request->input('token'),
          ]);

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

        $get = M_Barang::all();
        
        $data = M_Barang::join('estimasi', 'barang.kode_barang', '=', 'estimasi.kode_barang')
                ->orderBy('estimasi.est_kerusakan', 'ASC')
                ->get();

        $barang = $data->groupBy('jenis_barang');
        
        if (count($get) != 0){
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
      }
       
      $optionBuilder = new OptionsBuilder();
      $optionBuilder->setTimeToLive(60*20);

      $notificationBuilder = new PayloadNotificationBuilder('Ini Judul');
      $notificationBuilder->setBody('Ini Isinya')
                  ->setSound('default');

      $dataBuilder = new PayloadDataBuilder();
      $dataBuilder->addData(['data' => 'Isi_data']);

      $option = $optionBuilder->build();
      $notification = $notificationBuilder->build();
      $data = $dataBuilder->build();

      $token = "fzqSqRIaFm8:APA91bF6OvTRckj8irrQcemp2vGEC2X5Gwzd80Pn5ciM3ZfyQn97nD_TZpIDwdAOF-04yBNNorcNpI2rhrPC50zVJzo9YTyc-bavi_uO7cak0dJPQ_LP53tQ6siQArjxUiqDs70AQwvq";

      $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

      $downstreamResponse->numberSuccess();
      $downstreamResponse->numberFailure();
      $downstreamResponse->numberModification();

      // return Array - you must remove all this tokens in your database
      $downstreamResponse->tokensToDelete();

      // return Array (key : oldToken, value : new token - you must change the token in your database)
      $downstreamResponse->tokensToModify();

      // return Array - you should try to resend the message to the tokens in the array
      $downstreamResponse->tokensToRetry();

      // return Array (key:token, value:error) - in production you should remove from your database the tokens
      $downstreamResponse->tokensWithError(); 


        if (count($get) != 0) {
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

        $dataRate = new M_Rating();
        $dataRate->kode_service = $kode;
        $dataRate->id_user = $pemilik->id;
        $dataRate->rated = '0';
        $dataRate->save();

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
        
        $get = M_Service::where('id', $id_user)->get();

        $service = M_Service::leftjoin('teknisi', 'service.id_teknisi', '=', 'teknisi.id_teknisi')
        ->join('users', 'service.id', '=', 'users.id')
        ->join('barang', 'service.kode_barang', '=', 'barang.kode_barang')
        ->leftjoin('kerusakan', 'service.kode_service', '=', 'kerusakan.kode_service')
        ->where('service.id', $id_user)
        ->select('service.id_service', 'service.id', 'service.id_teknisi', 'service.kode_service', 'service.kode_barang', 'service.lokasi',
        'service.total_harga', 'service.status_garansi', 'service.start_date', 'service.end_date', 'service.valid_until', 'service.status_teknisi', 'service.status_service',
        'teknisi.id_teknisi', 'teknisi.t_nama', 'teknisi.t_alamat', 'teknisi.t_keahlian','teknisi.t_hp',
        'kerusakan.harga', 'kerusakan.kerusakan',
        'barang.kode_barang', 'barang.jenis_barang')
        ->orderBy('service.id_service', 'ASC')
        ->get();


        
        $coba = $service->groupBy('id_service');
        // dd($data);
        if (count($get) != 0){
        foreach ($coba as $services){
          $i = 0;  
          // dd($services);
          if($services[0]->kerusakan != null){
            for ($a=0; $a < sizeof($services); $a++) { 
              $damege[$a] = [
                'jenis' => $services[$a]->kerusakan,
                'harga' => $services[$a]->harga
              ];
            }
          }else{
            $damege = [];
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
          // dd($services[1]);
          if($services[0]->id_teknisi == null){
                        
            $data[] = [
              'idService' => $services[$i]->id_service,
              'status' => $services[$i]->status_service,
              'kode_service' => $services[$i]->kode_service,
              'kode_barang' => $services[$i]->kode_barang,
              'kategori' => $services[$i]->jenis_barang,
              'lokasiPelanggan' => $services[$i]->lokasi,
              'startDate' => $services[$i]->start_date,
              'endDate' => $services[$i]->end_date,
              'teknisi' => null,
              'damage' => null,
              'guarantee' => null
            ];   
            
          } 


          if($services[0]->id_teknisi != null && count($damege) == 0 &&  $services[0]->status_garansi == null){
            // dd(count($damege));
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
                  'no_hp' => $services[$i]->t_hp,
                  'rating' => $services[$i]->rating_teknisi,
                  'status_teknisi' => $services[$i]->status_teknisi,
              ],
              'damage' => null,
              'guarantee' => null,
                  
              
            ];
          }else if($services[0]->id_teknisi != null && count($damege) != 0 &&  $services[0]->status_garansi == null){
            // dd(count($damege));
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
                  'no_hp' => $services[$i]->t_hp,
                  'rating' => $services[$i]->rating_teknisi,
                  'status_teknisi' => $services[$i]->status_teknisi,
              ],
              'damage' => $damege,
              'guarantee' => null,
                  
              
            ];
          }

          if ($services[0]->id_teknisi != null && count($damege) != null && $services[0]->status_garansi != null){

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
                'no_hp' => $services[$i]->t_hp,
                'rating' => $services[$i]->rating_teknisi,
                'status_teknisi' => $services[$i]->status_teknisi,
            ],
            'damage' => $damege,
            'guarantee' => [
                'id_guarantee' => $services[$i]->id_service,
                'id_service' => $services[$i]->id_service,
                'status' => $services[$i]->status_garansi,
                'valid_until' => $services[$i]->valid_until,
            ]
          ];
        }

          $damege = [];
        $i++;
        }
      }
      
        // dd();
        if (count($get) != 0){
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
       
        $status = 'On Process';
        
        $data = M_Service::where('id_service', $id_service)->update([
            
            'status_service' => $status,
            'status_teknisi' => 'On the way',
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

    public function cancel_service(Request $request){
        $kode_service = $request->input('kode_service');
        
        $service = M_Service::where('kode_service', $kode_service)->delete();

        if ($service) {
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
        $start_date = date('Y-m-d');
        $data = M_Service::where('id_service', $id)->update([
            'start_date' => $start_date,
            'status_teknisi' => 'Doing service',
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
        $rating = $request->input('rating');
        $feedback = $request->input('feedback');

        $data = M_Rating::where('kode_service', $kode_service)->update([
            'rating' => $rating,
            'rated' => '1',
            'feedback' => $feedback
        ]);

        $get = M_Rating::where('kode_service', $kode_service)->first();
        $id_teknisi = $get->id_teknisi;
        $dataRate = M_Rating::where('id_teknisi', $id_teknisi)->get();
        
        $rate = 0;
        foreach ($dataRate as $value) {
          $rate += $value->rating;
        }

        $rate /= count($dataRate);

        $this->updateRating($id_teknisi, $rate);

        if ($get) {
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

    public function updateRating($id_teknisi, $value)
    {
      $data = M_Teknisi::where('id_teknisi', $id_teknisi)->update([
            'rating_teknisi' => $value,
      ]);
    }

    public function data_guarantee (Request $request){
        $id_user = $request->input('userId');
        $cek = M_Service::where('id', $id_user)->get();
        foreach ($cek as $ceks){
        if ($ceks->valid_until < date('Y-m-d') && $ceks->valid_until != null){
          M_service::where('id_service', $ceks->id_service)->update([
            'status_garansi' => 'Expired',
          ]);
        }
      }
        $service = M_Service::leftjoin('teknisi', 'service.id_teknisi', '=', 'teknisi.id_teknisi')
        ->join('users', 'service.id', '=', 'users.id')
        ->join('barang', 'service.kode_barang', '=', 'barang.kode_barang')
        ->leftjoin('kerusakan', 'service.kode_service', '=', 'kerusakan.kode_service')
        ->where('service.id', $id_user )
        ->where('service.status_garansi','!=', 'null')
        ->select('service.id_service', 'service.id', 'service.id_teknisi', 'service.kode_service', 'service.kode_barang', 'service.lokasi',
        'service.total_harga', 'service.status_garansi', 'service.start_date', 'service.end_date','service.valid_until', 'service.status_service',
        'teknisi.id_teknisi', 'teknisi.t_nama', 'teknisi.t_alamat', 'teknisi.t_keahlian', 'teknisi.t_hp',
        'kerusakan.harga', 'kerusakan.kerusakan',
        'barang.kode_barang', 'barang.jenis_barang')
        ->get();
        // dd($service);
        $coba = $service->groupBy('id_service');
        
        
        
        if(count($service) != 0){

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
            


          
            // if ($services[$i]->status_garansi != null){
            $data[] = [
              'id_guarantee' => $services[$i]->id_service,
              'status' => $services[$i]->status_garansi,
              'valid_until' => $services[$i]->valid_until,
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
                    'no_hp' => $services[$i]->t_hp,
                    'rating' => $services[$i]->rating_teknisi,
                ],
                'damage' => $damege,
              ],
    
            ];
          
            

            $damege = [];

            
          // }

          

        $i++;
        }             
      }

        
        if (count($service) != 0) {
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

    public function claim_guarantee(Request $request){
        $id_service = $request->input('id_service');
        $data = M_Service::where('id_service',$id_service)->update([
            'status_garansi' => 'On Process Guaranted',
        ]);
        if ($data) {
          return response()->json([
              'success' => true,
              'message' => 'data disimpan',
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
