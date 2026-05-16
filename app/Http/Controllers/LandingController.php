<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;
use App\Models\BookingRequest;
use Illuminate\Support\Str;

class LandingController extends Controller
{
    public function index()
    {
        $kendaraans = Kendaraan::all();
        $captcha = Str::random(5);
        session(['captcha_code' => $captcha]);
        
        return view('home', compact('kendaraans', 'captcha'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'foto_ktp' => 'required|image|max:2048',
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'captcha_input' => 'required|in:' . session('captcha_code'),
        ]);

        $path = $request->file('foto_ktp')->store('ktp', 'public');

        BookingRequest::create([
            'nama_lengkap' => $request->nama_lengkap,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'foto_ktp' => $path,
            'kendaraan_id' => $request->kendaraan_id,
            'status' => 'proses_review',
        ]);

        return redirect()->back()->with('success', 'Pengajuan Booking akan segera diproses.');
    }
}
