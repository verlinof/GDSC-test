<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Note;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\NoteDetailResource;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $note = Note::where('id_user', Auth::user()->id)->with('User:id,username')->get();
            return NoteDetailResource::collection($note);
        }catch(Exception $e){
            return response()->json([
                'error' => $e
            ],500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "note_title" => 'required|max:255',
            "note_content" => 'required',
        ]);

        try{
            $request['id_user'] = Auth::user()->id;
            $note = Note::create($request->all());
            return new NoteDetailResource($note->loadMissing('User:id,username'));
        }catch(Exception $e){
            return response()->json([
                'error' => $e
            ],500);
        }
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
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        //
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
                "status" => "Notes berhasil dihapus"
            ]);
        }catch(Exception $e){
            return response()->json([
                "error" => $e
            ],500);
        }
    }
}