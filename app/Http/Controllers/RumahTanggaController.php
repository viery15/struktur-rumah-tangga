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
use DataTables;

class RumahTanggaController extends Controller
{
    public function index(){
        return view('RumahTangga.index');
    }

    public function create(){
        $mode = "create";
        $data = "";
        $wilayah['provinsi'] = "";
        $wilayah['kota'] = "";
        $wilayah['kecamatan'] = "";
        $wilayah['kelurahan'] = "";
        $anggota = "";
        return view('RumahTangga.form', compact('mode', 'data', 'wilayah', 'anggota'));
    }

    public function edit($id)
    {
        $data = RumahTangga::find($id);
        $wilayah['provinsi'] = Provinsi::find($data->provinsi_id);
        $wilayah['kota'] = Kota::find($data->kabupaten_id);
        $wilayah['kecamatan'] = Kecamatan::find($data->kecamatan_id);
        $wilayah['kelurahan'] = Kelurahan::find($data->desa_id);
        $anggota = AnggotaKeluarga::where('rumah_tangga_id', '=', $data->id)->get();

        $mode = "edit";
        return view('rumahTangga.form', compact('mode', 'data', 'wilayah', 'anggota'));
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

    public function update(Request $request){
        try{
            DB::beginTransaction();

            $rumahTangga = RumahTangga::find($request->rumahTangga['id']);
            $rumahTangga->update($request->rumahTangga);

            foreach ($request->anggotaKeluarga as $anggota) {
                if(isset($anggota['id'])){
                    $anggotaKeluarga = AnggotaKeluarga::find($anggota['id']);
                    $anggotaKeluarga->update($anggota);
                }
                else{
                    $anggota['rumah_tangga_id'] = $request->rumahTangga['id'];
                    AnggotaKeluarga::create($anggota);
                }
            }

            if(isset($request->anggotaKeluargaDelete) && count($request->anggotaKeluargaDelete) > 0){
                foreach ($request->anggotaKeluargaDelete as $d) {
                    AnggotaKeluarga::destroy($d);
                }
            }

            DB::commit();

            return response()->json([
                'status' => "S",
                'message' => "Data berhasil disimpan"
            ]);

        } catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => "E",
                'message' => "Data gagal disimpan"
            ]);
        }

    }

    public function delete($id){
        try{
            DB::beginTransaction();

            RumahTangga::destroy($id);
            AnggotaKeluarga::where('rumah_tangga_id', $id)->delete();

            DB::commit();

            return response()->json([
                'status' => "S",
                'message' => "Data berhasil dihapus"
            ]);


        } catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function getListDT(){
        try{
            $data = DB::select("
                select a.*, b.nama as provinsi, c.nama as kota, d.nama as kecamatan, e.nama as kelurahan from rumah_tangga as a
                join provinsi as b on a.provinsi_id = b.id
                join kota as c on a.kabupaten_id = c.id
                join kecamatan as d on a.kecamatan_id = d.id
                join kelurahan as e on a.desa_id = e.id
            ");

            return Datatables::of($data)->make(true);
        } catch(Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
