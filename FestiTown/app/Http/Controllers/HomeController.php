<?php

namespace App\Http\Controllers;

use App\Models\Town;
use App\Models\Festive;
use App\Models\Adverticements;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page with statistics and featured content.
     */
    public function index()
    {
        $stats = [
            'towns_count' => Town::count(),
            'festives_count' => Festive::count(),
            'advertisements_count' => Adverticements::count(),
        ];

        $featuredTowns = Town::with('festive')->take(6)->get();
        $upcomingFestives = Festive::with('town')
            ->where('date', '>=', now())
            ->orderBy('date')
            ->take(6)
            ->get();
        $recentAdvertisements = Adverticements::with('festive.town')
            ->latest()
            ->take(8)
            ->get();

        return view('home', compact('stats', 'featuredTowns', 'upcomingFestives', 'recentAdvertisements'));
    }
}

