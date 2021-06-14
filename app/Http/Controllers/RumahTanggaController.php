<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKeluarga;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use App\Models\Provinsi;
use App\Models\RumahTangga;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RumahTanggaController extends Controller
{
    public function index(){
        return view('RumahTangga.index');
    }

    public function create(){
        return view('RumahTangga.form');
    }

    public function store(Request $request){
        try{
            DB::beginTransaction();

            if(count($request->anggotaKeluarga) == 0){
                return response()->json([
                    'status' => "E",
                    'message' => "Anggota keluarga tidak boleh kosong"
                ]);
            }

            $rumahTangga = RumahTangga::create($request->rumahTangga);

            for ($i=0; $i < count($request->anggotaKeluarga); $i++) {
                $anggota = $request->anggotaKeluarga[$i];
                $anggota['rumah_tangga_id'] = $rumahTangga->id;
                AnggotaKeluarga::create($anggota);
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

    public function getListDT(){

    }
}
