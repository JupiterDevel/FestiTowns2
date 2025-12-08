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
        $activeTab = $request->get('tab', 'comments');

        // Load pending comments (if user is admin or townhall)
        if (auth()->user()->isAdmin() || auth()->user()->isTownHall()) {
            if ($activeTab === 'comments') {
                $search = $request->get('search', '');
                $sort = in_array($request->get('sort'), ['user_name', 'content', 'festivity_name', 'created_at'], true) 
                    ? $request->get('sort') 
                    : 'created_at';
                $direction = $request->get('direction') === 'asc' ? 'asc' : 'desc';

                $query = Comment::with(['user', 'festivity.locality'])
                    ->where('approved', false)
                    ->when($search, function ($q) use ($search) {
                        $q->where(function ($inner) use ($search) {
                            $inner->where('content', 'like', "%{$search}%")
                                ->orWhereHas('user', function ($userQuery) use ($search) {
                                    $userQuery->where('name', 'like', "%{$search}%")
                                        ->orWhere('email', 'like', "%{$search}%");
                                })
                                ->orWhereHas('festivity', function ($festivityQuery) use ($search) {
                                    $festivityQuery->where('name', 'like', "%{$search}%");
                                });
                        });
                    });

                // Apply sorting
                if ($sort === 'user_name') {
                    $query->join('users', 'comments.user_id', '=', 'users.id')
                        ->orderBy('users.name', $direction)
                        ->select('comments.*');
                } elseif ($sort === 'festivity_name') {
                    $query->join('festivities', 'comments.festivity_id', '=', 'festivities.id')
                        ->orderBy('festivities.name', $direction)
                        ->select('comments.*');
                } else {
                    $query->orderBy('comments.' . $sort, $direction);
                }

                $data['comments'] = $query->paginate(12)->withQueryString();
                $data['comments_search'] = $search;
                $data['comments_sort'] = $sort;
                $data['comments_direction'] = $direction;
            } else {
                // Load all comments without pagination for non-active tab
                $data['comments'] = Comment::with(['user', 'festivity.locality'])
                    ->where('approved', false)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }

        // Load users (admin only)
        if (auth()->user()->isAdmin()) {
            if ($activeTab === 'users') {
                $search = $request->get('search', '');
                $sort = in_array($request->get('sort'), ['name', 'email', 'role', 'locality_name', 'created_at'], true) 
                    ? $request->get('sort') 
                    : 'created_at';
                $direction = $request->get('direction') === 'asc' ? 'asc' : 'desc';

                $query = User::with('locality')
                    ->when($search, function ($q) use ($search) {
                        $q->where(function ($inner) use ($search) {
                            $inner->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhereHas('locality', function ($localityQuery) use ($search) {
                                    $localityQuery->where('name', 'like', "%{$search}%");
                                });
                        });
                    });

                // Apply sorting
                if ($sort === 'locality_name') {
                    $query->leftJoin('localities', 'users.locality_id', '=', 'localities.id')
                        ->orderBy('localities.name', $direction)
                        ->select('users.*');
                } else {
                    $query->orderBy('users.' . $sort, $direction);
                }

                $data['users'] = $query->paginate(12)->withQueryString();
                $data['users_search'] = $search;
                $data['users_sort'] = $sort;
                $data['users_direction'] = $direction;
            } else {
                // Load all users without pagination for non-active tab
                $data['users'] = User::with('locality')->get();
            }
        }

        // Load advertisements (admin only)
        if (auth()->user()->isAdmin()) {
            if ($activeTab === 'advertisements') {
                $search = $request->get('search', '');
                $sort = in_array($request->get('sort'), ['name', 'priority', 'active', 'created_at', 'end_date'], true) 
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
            } else {
                // Load all advertisements without pagination for non-active tab
                $data['advertisements'] = Advertisement::with(['festivity', 'locality'])
                    ->premium()
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }

        return view('admin.panel', $data);
    }
}

