<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ImageRequest;
use App\Models\Images;
use App\Constants\Image;

class ImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(['object' => Images::all()]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param ImageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ImageRequest $request)
    {
        $user_file = $request->file('image');
        $extension = $user_file->getClientOriginalExtension();
        $info['size'] = $user_file->getSize();

        if ($extension != 'svg') {
            $size = getimagesize($user_file);
            $info['dimension'] = $size[0] . ' x ' . $size[1];
        }

        $uniqid = uniqid();

        $image = new Images();
        $image->name = $uniqid;
        $image->extension = $extension;
        $image->info = collect($info);

        $patch = Image::STORAGE_PATCH . DIRECTORY_SEPARATOR;

        $user_file->storeAs($patch, $uniqid . '.' . $extension);

        if ($image->save()) {
            return response(['notification' => 'complete']);
        }
        return response(['error'], 401);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return response(['object' => Images::findOrFail($id)]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param ImageRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ImageRequest $request, int $id)
    {
        $user_file = $request->file('image');
        $extension = $user_file->getClientOriginalExtension();
        $info['size'] = $user_file->getSize();

        if ($extension != 'svg') {
            $size = getimagesize($user_file);
            $info['dimension'] = $size[0] . ' x ' . $size[1];
        }


        $image = Images::findOrFail($id);
        $image->extension = $extension;
        $image->info = collect($info);

        $patch = Image::STORAGE_PATCH . DIRECTORY_SEPARATOR;

        $user_file->storeAs($patch, $image->name . '.' . $extension);

        if ($image->update()) {
            return response(['notification' => 'complete']);
        }
        return response(['error'], 401);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $image = Images::findOrFail($id);
        $file = Image::STORAGE_PATCH . DIRECTORY_SEPARATOR . $image->name . '.' . $image->extension;
        if ($image->delete() && Storage::exists($file)) {
            Storage::delete($file);
            return response(['notification' => 'complete']);
        }
        return response(['error'], 401);
    }
}
