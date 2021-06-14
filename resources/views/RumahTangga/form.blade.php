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
