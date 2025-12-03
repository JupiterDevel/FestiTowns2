<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AdminPanelController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display the unified admin panel with all admin functionalities.
     */
    public function index(Request $request)
    {
        // Check if user has admin access (either admin or townhall for comments)
        if (!auth()->check() || (!auth()->user()->isAdmin() && !auth()->user()->isTownHall())) {
            abort(403, 'Unauthorized access.');
        }

        $data = [];

        // Load pending comments (if user is admin or townhall)
        if (auth()->user()->isAdmin() || auth()->user()->isTownHall()) {
            $data['comments'] = Comment::with(['user', 'festivity.locality'])
                ->where('approved', false)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Load users (admin only)
        if (auth()->user()->isAdmin()) {
            $data['users'] = User::with('locality')->get();
        }

        // Load advertisements (admin only)
        if (auth()->user()->isAdmin()) {
            $search = $request->get('search', '');
            $sort = in_array($request->get('sort'), ['name', 'priority', 'active', 'created_at'], true) 
                ? $request->get('sort') 
                : 'created_at';
            $direction = $request->get('direction') === 'asc' ? 'asc' : 'desc';

            $query = Advertisement::with(['festivity', 'locality'])
                ->premium()
                ->when($search, function ($q) use ($search) {
                    $q->where(function ($inner) use ($search) {
                        $inner->where('name', 'like', "%{$search}%")
                            ->orWhere('url', 'like', "%{$search}%");
                    });
                })
                ->orderBy($sort, $direction);

            $data['advertisements'] = $query->paginate(12)->withQueryString();
            $data['search'] = $search;
            $data['sort'] = $sort;
            $data['direction'] = $direction;
        }

        return view('admin.panel', $data);
    }
}

