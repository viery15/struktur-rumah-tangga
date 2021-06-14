<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RumahTanggaController extends Controller
{
    public function index(){
        return view('RumahTangga.index');
    }
}
