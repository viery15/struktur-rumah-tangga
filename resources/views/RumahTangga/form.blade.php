@extends('Layout.index')

@section('style')
    <style>
        .modal.and.carousel {
            position: fixed;
        }
    </style>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <!--begin::Card-->
        <div class="card card-custom gutter-b card-stretch">
            <!--begin::Body-->
            <div class="card-body">
                <h3>Form Data Rumah Tangga</h3>
                <form id="form">
                    <table class="table table-striped mt-10" style="font-size: 14px;">
                        <tr>
                            <td class="align-middle" style="width: 15%">Provinsi</td>
                            <td class="align-middle" style="width: 2%"> : </td>
                            <td class="align-middle">
                                <div class="form-group" style="margin-bottom: 0">
                                    <select v-on:change="changeProvinsi()" id="provinsi" class='form-control' v-model='provinsi' v-select='provinsi'>
                                        <option v-for="(provinsi, index) in dataProvinsi" :key="index" :value=provinsi>@{{ provinsi.nama }}</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-middle" style="width: 15%">Kota/Kabupaten</td>
                            <td class="align-middle" style="width: 2%"> : </td>
                            <td class="align-middle">
                                <div class="form-group" style="margin-bottom: 0">
                                    <select v-on:change="changeKota()" id="kota" class='form-control' v-model='kota' v-select='kota'>
                                        <option v-for="(kota, index) in dataKota" :key="index" :value=kota>@{{ kota.nama }}</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-middle" style="width: 15%">Kecamatan</td>
                            <td class="align-middle" style="width: 2%"> : </td>
                            <td class="align-middle">
                                <div class="form-group" style="margin-bottom: 0">
                                    <select v-on:change="changeKecamatan()" id="kecamatan" class='form-control' v-model='kecamatan' v-select='kecamatan'>
                                        <option v-for="(kecamatan, index) in dataKecamatan" :key="index" :value=kecamatan>@{{ kecamatan.nama }}</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-middle" style="width: 15%">Desa</td>
                            <td class="align-middle" style="width: 2%"> : </td>
                            <td class="align-middle">
                                <div class="form-group" style="margin-bottom: 0">
                                    <select id="kelurahan" class='form-control' v-model='kelurahan' v-select='kelurahan'>
                                        <option v-for="(kelurahan, index) in dataKelurahan" :key="index" :value=kelurahan>@{{ kelurahan.nama }}</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-middle" style="width: 15%">Alamat</td>
                            <td class="align-middle" style="width: 2%"> : </td>
                            <td class="align-middle">
                                <div class="form-group" style="margin-bottom: 0">
                                    <input name="alamat" v-model="rumah_tangga.alamat" type="text" class="form-control form-control-sm" placeholder="Alamat">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-middle" style="width: 15%">Kepala Keluarga</td>
                            <td class="align-middle" style="width: 2%"> : </td>
                            <td class="align-middle">
                                <div class="form-group" style="margin-bottom: 0">
                                    <select id="kepala" class='form-control' v-model='kepala' v-select='kepala'>
                                        <option v-for="(anggota, index) in anggota_keluarga" :key="index" :value=anggota>@{{ anggota.hubungan_keluarga }} - @{{ anggota.nama }}</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>

                <h3 style="margin-top: 3%">Anggota Keluarga</h3>
                <hr>

                <table id="table-peserta" class="table table-bordered">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-white text-center">No</th>
                            <th class="text-white">NIK</th>
                            <th class="text-white">Nama</th>
                            <th class="text-white">Hubungan</th>
                            <th class="text-white">Tanggal Lahir</th>
                            <th class="text-white">Jenis Kelamin</th>
                            <th class="text-white text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">#</td>
                            <td>
                                <input v-model="formAnggota.nik" type="text" class="form-control" placeholder="NIK">
                            </td>
                            <td>
                                <input v-model="formAnggota.nama" type="text" class="form-control" placeholder="Nama Lengkap">
                            </td>
                            <td>
                                <div class="form-group" style="margin-bottom: 0">
                                    <select id="hubungan" class='form-control' v-model='formAnggota.hubungan_keluarga' v-select='formAnggota.hubungan_keluarga'>
                                        <option v-for="(hubungan, index) in dataHubungan" :key="index" :value=hubungan>@{{ hubungan }}</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <input v-model="formAnggota.tanggal_lahir" type="date" class="form-control">
                            </td>
                            <td>
                                <div class="form-group" style="margin-bottom: 0">
                                    <select id="jenis-kelamin" class='form-control' v-model='formAnggota.jenis_kelamin' v-select='formAnggota.jenis_kelamin'>
                                        <option v-for="(jenisKelamin, index) in dataJenisKelamin" :key="index" :value=jenisKelamin>@{{ jenisKelamin }}</option>
                                    </select>
                                </div>
                            </td>
                            <td class="text-center">
                                <button v-on:click="addAnggota()" class="btn btn-sm btn-success"><span class="fa fa-plus"></span></button>
                            </td>
                        </tr>

                        <tr v-for="(anggota, index) in anggota_keluarga" :key="index">
                            <td class="text-center">@{{ index + 1 }}</td>
                            <td>@{{ anggota.nik }}</td>
                            <td>@{{ anggota.nama }}</td>
                            <td>@{{ anggota.hubungan_keluarga }}</td>
                            <td>@{{ anggota.tanggal_lahir }}</td>
                            <td>@{{ anggota.jenis_kelamin }}</td>
                            <td class="text-center"><button class="btn btn-sm btn-danger"><span class="fa fa-trash"></span></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!--end::Body-->
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-lg-end">
                        <button v-on:click="save()" class="btn btn-md btn-success"><span class="fa fa-save"></span> Simpan</button>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Card-->
    </div>
</div>

@endsection

@section('script')
    @include('RumahTangga.formJs')
@endsection
