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
                <h3>Manajemen Data Rumah Tangga</h3>
                <a href="/RumahTangga/create" class="mb-5 btn btn-sm btn-info mt-10"><span class="fa fa-plus"></span> Tambah Data</a>
                <table id="table-rumah-tangga" class="table table-bordered mt-3">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-white">No</th>
                            <th class="text-white">Provinsi</th>
                            <th class="text-white">Kota/Kabupaten</th>
                            <th class="text-white">Kecamatan</th>
                            <th class="text-white">Desa</th>
                            <th class="text-white">Alamat</th>
                            <th class="text-white">Kepala Keluarga</th>
                            <th class="text-white">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Card-->
    </div>
</div>

@endsection

@section('script')
    @include('RumahTangga.indexJs')
@endsection
