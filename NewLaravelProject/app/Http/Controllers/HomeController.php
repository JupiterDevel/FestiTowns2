<?php

namespace App\Http\Controllers;

use App\Models\Locality;
use App\Models\Festivity;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $localities = Locality::with('festivities')->get();
        $upcomingFestivities = Festivity::with('locality')
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->limit(6)
            ->get();
        
        // Manejar búsquedas
        $searchResults = null;
        $searchType = null;
        $searchQuery = $request->get('search');
        $searchDate = $request->get('search_date');
        
        // Si hay búsqueda por fecha, usar el campo de fecha
        if ($searchDate) {
            $searchQuery = $searchDate;
            $searchType = 'date';
        }
        
        if ($searchQuery) {
            $searchType = $request->get('search_type', 'festivity');
            
            switch ($searchType) {
                case 'locality':
                    $searchResults = Locality::withCount('festivities')
                        ->where('name', 'LIKE', "%{$searchQuery}%")
                        ->orderBy('name')
                        ->paginate(10);
                    break;
                    
                case 'festivity':
                    $searchResults = Festivity::with(['locality', 'votes'])
                        ->where('name', 'LIKE', "%{$searchQuery}%")
                        ->orderBy('name')
                        ->paginate(10);
                    break;
                    
                case 'date':
                    try {
                        $date = Carbon::parse($searchQuery);
                        $endDate = $date->copy()->addWeek();
                        
                        $searchResults = Festivity::with(['locality', 'votes'])
                            ->whereBetween('start_date', [$date->toDateString(), $endDate->toDateString()])
                            ->orderBy('start_date')
                            ->paginate(10);
                    } catch (\Exception $e) {
                        $searchResults = collect()->paginate(10);
                    }
                    break;
            }
        }
        
        return view('home', compact('localities', 'upcomingFestivities', 'searchResults', 'searchType', 'searchQuery'));
    }
}
