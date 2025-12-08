<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use App\Models\Advertisement;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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

    /**
     * Enable voting for all users.
     */
    public function enableVoting()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        Cache::forever('voting_enabled', true);

        return redirect()->route('admin.panel', ['tab' => 'voting'])
            ->with('success', 'Las votaciones han sido habilitadas. Todos los usuarios pueden votar ahora.');
    }

    /**
     * Disable voting for all users.
     */
    public function disableVoting()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        Cache::forever('voting_enabled', false);

        return redirect()->route('admin.panel', ['tab' => 'voting'])
            ->with('success', 'Las votaciones han sido deshabilitadas. Los usuarios no pueden votar ahora.');
    }

    /**
     * Reset all votes (delete all votes from all festivities).
     */
    public function resetVotes()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        try {
            DB::transaction(function () {
                Vote::truncate();
            });

            return redirect()->route('admin.panel', ['tab' => 'voting'])
                ->with('success', 'Todas las votaciones han sido reiniciadas. Todas las festividades ahora tienen 0 votos.');
        } catch (\Exception $e) {
            return redirect()->route('admin.panel', ['tab' => 'voting'])
                ->with('error', 'Ha ocurrido un error al reiniciar las votaciones. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Update the informational message for the "Most Voted" page.
     */
    public function updateMessage(Request $request)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'message' => 'nullable|string|max:2000',
        ]);

        $message = $request->input('message', '');
        
        if (empty(trim($message))) {
            Cache::forget('voting_info_message');
            return redirect()->route('admin.panel', ['tab' => 'voting'])
                ->with('success', 'El mensaje ha sido eliminado.');
        }

        Cache::forever('voting_info_message', $message);

        return redirect()->route('admin.panel', ['tab' => 'voting'])
            ->with('success', 'El mensaje ha sido guardado exitosamente.');
    }

    /**
     * Clear the informational message for the "Most Voted" page.
     */
    public function clearMessage()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        Cache::forget('voting_info_message');

        return redirect()->route('admin.panel', ['tab' => 'voting'])
            ->with('success', 'El mensaje ha sido eliminado.');
    }

    /**
     * Update contact information (email, phone, social media).
     */
    public function updateContact(Request $request)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
        ]);

        // Guardar información de contacto
        if ($request->filled('email')) {
            Cache::forever('contact_email', $request->input('email'));
        } else {
            Cache::forget('contact_email');
        }

        if ($request->filled('phone')) {
            Cache::forever('contact_phone', $request->input('phone'));
        } else {
            Cache::forget('contact_phone');
        }

        // Guardar redes sociales
        if ($request->filled('facebook')) {
            Cache::forever('social_facebook', $request->input('facebook'));
        } else {
            Cache::forget('social_facebook');
        }

        if ($request->filled('twitter')) {
            Cache::forever('social_twitter', $request->input('twitter'));
        } else {
            Cache::forget('social_twitter');
        }

        if ($request->filled('instagram')) {
            Cache::forever('social_instagram', $request->input('instagram'));
        } else {
            Cache::forget('social_instagram');
        }

        if ($request->filled('youtube')) {
            Cache::forever('social_youtube', $request->input('youtube'));
        } else {
            Cache::forget('social_youtube');
        }

        return redirect()->route('admin.panel', ['tab' => 'contact'])
            ->with('success', 'La información de contacto ha sido actualizada exitosamente.');
    }
}

