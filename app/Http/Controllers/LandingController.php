<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;

class LandingController extends Controller
{
    public function index()
    {
        $kendaraans = Kendaraan::all();
        return view('home', compact('kendaraans'));
    }
}
