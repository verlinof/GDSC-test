<?php

namespace App\Http\Middleware;

use App\Models\Note;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class NotesOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentUser = Auth::user();
        $note = Note::findOrFail($request->id);
        
        if($note->id_user != $currentUser->id){
            return response()->json([
                'error' => 'unauthorized'
            ], 401);
        }
        
        return $next($request);
    }
}