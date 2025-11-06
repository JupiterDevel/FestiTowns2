<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Festivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Festivity $festivity)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
        ]);

        $comment = new Comment();
        $comment->content = $validated['content'];
        $comment->user_id = Auth::id();
        $comment->festivity_id = $festivity->id;
        $comment->approved = false; // Default to pending approval

        // Handle photo upload if provided
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            // Store in public disk under comments directory
            $path = $photo->storeAs('comments', $filename, 'public');
            // Get relative URL (without domain) for storage
            $comment->photo = '/storage/comments/' . $filename;
        }

        $comment->save();

        // Otorgar puntos por comentar (solo a visitantes)
        $user = Auth::user();
        if ($user->isVisitor()) {
            $user->addPoints(2); // 2 puntos por comentar
        }

        return redirect()->route('festivities.show', $festivity)
            ->with('success', 'Your comment has been submitted and is pending approval.');
    }

    /**
     * Approve a comment (Admin or TownHall only)
     */
    public function approve(Comment $comment)
    {
        $this->authorize('approve', $comment);
        
        $comment->update(['approved' => true]);

        return redirect()->back()
            ->with('success', 'Comment approved successfully.');
    }

    /**
     * Reject a comment (Admin or TownHall only)
     */
    public function reject(Comment $comment)
    {
        $this->authorize('approve', $comment);
        
        $comment->delete();

        return redirect()->back()
            ->with('success', 'Comment rejected and deleted.');
    }

    /**
     * Display pending comments for moderation
     */
    public function pending()
    {
        $this->authorize('viewAny', Comment::class);
        
        $comments = Comment::with(['user', 'festivity.locality'])
            ->where('approved', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('comments.pending', compact('comments'));
    }
}
