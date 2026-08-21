<?php

namespace App\Http\Controllers;

use App\Models\Plan;

class LandingController extends Controller
{
    public function index()
    {
        $plans = Plan::where('is_active', true)
            ->orderBy('harga', 'asc')
            ->get();

        // Ganti dari 'landing.index' ke 'index' atau 'view.index'
        return view('index', compact('plans'));
        // ATAU jika file di folder view/
        // return view('view.index', compact('plans'));
    }
}