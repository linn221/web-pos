<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role === "admin") {
            $photos = Photo::all();
        } else {
            $photos = Auth::user()->photos;
        }

        return response()->json($photos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'files.*' => 'required|mimes:jpeg,png,jpg,gif'
        ]);
        $uploadedFiles = $request->file('photos');


        $files = [];
        foreach ($uploadedFiles as $uploadedFile) {
            $savedPhoto = $uploadedFile->store("public/photo");
            $fileName = $uploadedFile->getClientOriginalName();
            $extension = $uploadedFile->getClientOriginalExtension();

            $files[] = Photo::create([
                'url' => $savedPhoto,
                'name' => $fileName,
                'extension' => $extension,
                'user_id' => Auth::id(),

            ]);
        }


        return response()->json($files);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    }
}
