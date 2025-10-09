<?php

namespace App\Http\Controllers;

use App\Models\Locality;
use App\Models\Festivity;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $localities = Locality::with('festivities')->get();
        $upcomingFestivities = Festivity::with('locality')
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->limit(6)
            ->get();
        
        return view('home', compact('localities', 'upcomingFestivities'));
    }
}
