<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\NoteDetailResource;
use App\Http\Resources\UserResource;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $note = Note::with('User:id,username')->get();
            return NoteDetailResource::collection($note);
        }catch(Exception $e){
            return response()->json([
                'error' => $e
            ],500);
        }
    }

    public function allUser(){
        try{
            $user = User::all();
            return UserResource::collection($user);
        }catch(Exception $e){
            return response()->json([
                'error' => $e
            ],500);
        }
    }

    public function showUser($id)
    {
        try{
            $user = User::findOrFail($id);
            return new UserResource($user);
        }catch(Exception $e){
            return response()->json([
                "error" => $e
            ],500);
        }
    }

    public function updateUser(Request $request, $id)
    {
        try{
            $validated = $request->validate([
                "username" => 'required|max:255',
                "email" => 'required',
                "status" => 'nullable|in:default,editor,admin'
            ]);
            $note = Note::findOrFail($id);
            $note->update($request->all());
            return new NoteDetailResource($note->loadMissing('User:id,username'));
        }catch(Exception $e){
            return response()->json([
                "error" => $e
            ],500);
        }
    }

    public function deleteUser($id)
    {
        try{
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                "status" => "Akun berhasil dihapus oleh admin"
            ]);
        }catch(Exception $e){
            return response()->json([
                "error" => $e
            ],500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            $note = Note::findOrFail($id);
            return new NoteDetailResource($note->loadMissing('User:id,username'));
        }catch(Exception $e){
            return response()->json([
                "error" => $e
            ],500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try{
            $validated = $request->validate([
                "note_title" => 'required|max:255',
                "note_content" => 'required',
            ]);
            $note = Note::findOrFail($id);
            $note->update($request->all());
            return new NoteDetailResource($note->loadMissing('User:id,username'));
        }catch(Exception $e){
            return response()->json([
                "error" => $e
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try{
            $note = Note::findOrFail($id);
            $note->delete();

            return response()->json([
                "status" => "Notes berhasil dihapus oleh admin"
            ]);
        }catch(Exception $e){
            return response()->json([
                "error" => $e
            ],500);
        }
    }
}