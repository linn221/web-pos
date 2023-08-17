<?php

namespace App\Http\Controllers;

use App\Http\Resources\PhotoResource;
use App\Models\Photo;
// use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Foundation\Console\UpCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

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

        return PhotoResource::collection($photos);
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
            $size = $uploadedFile->getSize();
            $savedPhoto = $uploadedFile->store("public/photo");
            $fileName = $uploadedFile->getClientOriginalName();
            $extension = $uploadedFile->extension();

            $files[] = Photo::create([
                'url' => $savedPhoto,
                'name' => $fileName,
                'extension' => $extension,
                'size' => $size,
                'user_id' => Auth::id(),

            ]);
        }


        return PhotoResource::collection($files);
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

        $photo = Photo::find($id);

        if (is_null($photo)) {
            return response()->json([
                'message' => 'photo is not found'
            ]);
        }

        Storage::delete($photo->url);
        $photo->delete();


        return response()->json([
            "message" => "photo has delete"
        ]);
    }
}
