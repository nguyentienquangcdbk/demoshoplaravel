<?php

namespace App\Http\Controllers;

// use Facade\FlareClient\Stacktrace\File;

use App\Models\productimg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class imgController extends Controller
{
    //

    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $fileNameHash = Str::random(40) . '.' . $request->file('file')->getClientOriginalExtension();
            $filePath = $request->file('file')->storeAs('public', $fileNameHash);
            $dataUpload = [
                'file_name' => $fileNameHash,
                'file_path' => Storage::url($filePath)
            ];
            return $dataUpload;
        }
    }

    public function uploads(Request $request)
    {

        if ($request->hasFile('filename')) {
            $arr = [];

            foreach ($request->filename as $fileItem) {
                if ($fileItem) {
                    // return 'vào được rồi 2';

                    // $fileNameOrigin = $fileItem->getClientOriginalName();
                    $fileNameHash = Str::random(40) . '.' . $fileItem->getClientOriginalExtension();
                    $filePath = $fileItem->storeAs('public', $fileNameHash);
                    $dataUploadTrait = [
                        'file_name' => $fileNameHash,
                        'file_path' => Storage::url($filePath)
                    ];
                    $arr[] = $dataUploadTrait;
                }
            }
            return $arr;
            // return 'ok';
        } else {

            return 'oksd';
        }
    }

    public function delete(Request $request)
    {
        // $u = Storage::path($request->name);
        // $u = public_path($request->name);
        // File::Exit('public', $request->name);
        if ($request->name) {

            File::delete(public_path($request->name));
            // Storage::delete(public_path($request->name));
            return 'ok';
        }
    }
    public function removeProductImg($id)
    {

        $path = productimg::find($id);

        if ($path->path) {
            File::delete(public_path($path->path));

            $path->delete();
            // Storage::delete(public_path($request->name));
            return 'ok';
        }
    }
}
