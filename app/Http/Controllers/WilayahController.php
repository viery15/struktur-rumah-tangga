<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use App\Models\Provinsi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WilayahController extends Controller
{
    public function getAllProvinsi(){
        try{
            return response()->json([
                'status' => "S",
                'data' => Provinsi::get()
            ]);
        } catch(Exception $e){
            throw $e;
        }
    }

    public function getKotaByProvId(Request $request){
        try{
            return response()->json([
                'status' => "S",
                'data' => Kota::where('provinsi_id', '=', $request->input('provinsiId'))->get()
            ]);
        } catch(Exception $e){
            throw $e;
        }
    }

    public function getKcmByKotaId(Request $request){
        try{
            return response()->json([
                'status' => "S",
                'data' => Kecamatan::where('kota_id', '=', $request->input('kotaId'))->get()
            ]);
        } catch(Exception $e){
            throw $e;
        }
    }

    public function getKlhByKcmId(Request $request){
        try{
            return response()->json([
                'status' => "S",
                'data' => Kelurahan::where('kecamatan_id', '=', $request->input('kecamatanId'))->get()
            ]);
        } catch(Exception $e){
            throw $e;
        }
    }

    public function importWilayah(){
        $path = storage_path() . "/wilayah/regency.json";
        $data = json_decode(file_get_contents($path), true);

        try{
            DB::beginTransaction();

            for ($i=0; $i < count($data); $i++) {
                $provinsi = Provinsi::create([
                    'nama' => $data[$i]['provinsi']
                ]);

                for ($j=0; $j < count($data[$i]['kota']); $j++) {
                    $kota = Kota::create([
                        'provinsi_id' => $provinsi->id,
                        'nama' => $data[$i]['kota'][$j]['name']
                    ]);

                    for ($k=0; $k < count($data[$i]['kota'][$j]['kecamatan']); $k++) {
                        $kecamatan = Kecamatan::create([
                            'kota_id' => $kota->id,
                            'nama' => $data[$i]['kota'][$j]['kecamatan'][$k]['name']
                        ]);

                        for ($l=0; $l < count($data[$i]['kota'][$j]['kecamatan'][$k]['kelurahan']); $l++) {
                            $kelurahan = Kelurahan::create([
                                'kecamatan_id' => $kecamatan->id,
                                'nama' => $data[$i]['kota'][$j]['kecamatan'][$k]['kelurahan'][$l]['name']
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            return response()->json([
                'status' => "S",
                'message' => "Data berhasil disimpan"
            ]);

        } catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

}
