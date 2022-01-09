<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $original_image = $request->image->getClientOriginalName();
        $path = Storage::disk('s3')->putFileAs('images/', $request->file('image'), $original_image);
        // Storage::disk('s3')->put('images/' . $original_image, $request->image);
        // $original_image_url = Storage::disk('s3')->url('images/' . $original_image);
        $original_image_url = $path;


        $image_resize_large = Image::make($request->image);
        $image_resize_large->resize(1024, 600)->stream();

        $large_image = 'large' . $request->image->getClientOriginalName();

        Storage::disk('s3')->put('images/' . $large_image, $image_resize_large);
        $large_image_url = Storage::disk('s3')->url('images/' . $large_image);

        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'original_image' => $original_image,
            'original_image_url' => $original_image_url,
            'large_image' => $large_image,
            'large_image_url' => $large_image_url

        ]);

        return response(['data' => []]);
        // return Storage::url($request->file);

        // $path = $request->image->store('images/', 's3');

        // if ($request->has('file')) {
        //     $image = $request->file('file');
        //     $s3 = Storage::disk('s3');
        //     $file_name = uniqid() . '.' . $image->getClientOriginalExtension();
        //     $s3filePath = '/assets/' . $file_name;
        //     $s3->put($s3filePath, file_get_contents($image), 'public');
        //     return 'ok';
        // }


        // $image_name = str_random(20);
        // $ext = strtolower($request->image->getClientOriginalExtension()); // You can use also getClientOriginalName()
        // $image_full_name = $image_name . '.' . $ext;
        // $upload_path = 'image/';    //Creating Sub directory in Public folder to put image
        // $image_url = $upload_path . $image_full_name;
        // $success = $request->image->move($upload_path, $image_full_name);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
