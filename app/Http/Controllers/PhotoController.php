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

use function PHPUnit\Framework\isEmpty;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role == "admin") {
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
            $bytes = $uploadedFile->getSize();
            $megaBytes = $bytes / (1024 * 1024); // Convert bytes to megabytes
            $megaBytesFormatted = number_format($megaBytes, 2); // Format to 2 decimal places
            $savedPhoto = $uploadedFile->store("public/photo");
            $fileName = $uploadedFile->getClientOriginalName();
            $extension = $uploadedFile->extension();

            $files[] = Photo::create([
                'url' => $savedPhoto,
                'name' => $fileName,
                'extension' => $extension,
                'size' => $megaBytesFormatted,
                'user_id' => Auth::id(),

            ]);
        }


        return PhotoResource::collection($files);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $photo = Photo::find($id);

        if (is_null($photo)) {
            abort(404, 'photo is not found');
        }

        Storage::delete($photo->url);
        $photo->delete();


        return response()->json([
            "message" => "photo has been deleted"
        ]);
    }


    // public function multipleDestroy(Request $request)
    // {

    //     $photos = Photo::whereIn('id', $request->ids)->get();
    //     if (count($photos) === 0) {
    //         abort(404, 'photo is not found');
    //     }

    //     foreach ($photos as $photo) {
    //         Storage::delete($photo->url);
    //     }

    //     Photo::destroy($request->ids);

    //     return response()->json([
    //         'message' => "you have deleted multiple photos ",
    //     ]);
    // }
}
