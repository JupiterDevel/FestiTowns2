<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Locality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource (Admin only).
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);
        
        $users = User::with('locality')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource (Admin only).
     */
    public function create()
    {
        $this->authorize('create', User::class);
        
        $localities = Locality::all();
        return view('users.create', compact('localities'));
    }

    /**
     * Store a newly created resource in storage (Admin only).
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,townhall,visitor',
            'locality_id' => 'nullable|exists:localities,id',
            'province' => 'nullable|string|in:'.implode(',', config('provinces.provinces', [])),
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('users', $filename, 'public');
            $validated['photo'] = '/storage/users/' . $filename;
        }

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);
        
        $user->load('locality');
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        
        $localities = Locality::all();
        return view('users.edit', compact('user', 'localities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,townhall,visitor',
            'locality_id' => 'nullable|exists:localities,id',
            'province' => 'nullable|string|in:'.implode(',', config('provinces.provinces', [])),
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo && !filter_var($user->photo, FILTER_VALIDATE_URL)) {
                $oldPhotoPath = public_path(str_replace('/storage/', 'storage/', $user->photo));
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }
            
            $photo = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('users', $filename, 'public');
            $validated['photo'] = '/storage/users/' . $filename;
        } elseif ($request->has('remove_photo')) {
            // Delete photo if user wants to remove it
            if ($user->photo && !filter_var($user->photo, FILTER_VALIDATE_URL)) {
                $oldPhotoPath = public_path(str_replace('/storage/', 'storage/', $user->photo));
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }
            $validated['photo'] = null;
        } else {
            // Don't update photo if not provided
            unset($validated['photo']);
        }

        $user->update($validated);

        return redirect()->route('users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage (Admin only).
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
