<?php
namespace App\Http\Controllers;

use App\Models\WhatInFarms;
use App\Models\WhatInFarmImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WhatInFarmController extends Controller
{
    public function index()
    {
        $farms = WhatInFarms::latest()->get();
        return view('what_in_farm.index', compact('farms'));
    }

    public function create()
    {
        return view('what_in_farm.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image',
            'description' => 'nullable|string',
            'description1' => 'nullable|string',
            'description2' => 'nullable|string',
            'description3' => 'nullable|string',
            'description4' => 'nullable|string',
            'description5' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $data = $request->except('image');
             $data = $request->except('video');

            if ($request->hasFile('image')) {
                $imageName = time().'_'.$request->image->getClientOriginalName();
                $request->image->move(public_path('uploads/what_in_farm'), $imageName);
                $data['image'] = $imageName;
            }

            $videoName=null;
             if ($request->hasFile('video')) {
                $videoFile = $request->file('video');
                $videoName = time().'_'.$videoFile->getClientOriginalName();
                $videoFile->move(public_path('uploads/what_in_farm'), $videoName);
                }
                  $data['video'] = $videoName;
              $farm = WhatInFarms::create($data);

        // Multiple gallery images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $imgName = time().'_'.$image->getClientOriginalName();
                $image->move(public_path('uploads/what_in_farm/gallery'), $imgName);

                WhatInFarmImage::create([
                    'what_in_farm_id' => $farm->id,
                    'image' => $imgName,
                ]);
            }
        }

            DB::commit();
            return redirect()->route('what-in-farm.index')->with('success','Record created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $farm = WhatInFarms::with('images')->findOrFail($id);
        return view('what_in_farm.edit', compact('farm'));
    }

   public function update(Request $request, $id)
{
    $request->validate([
        'name'         => 'required|string|max:255',
        'category'     => 'required|string',
        'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'images.*'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'description'  => 'nullable|string',
        'description1' => 'nullable|string',
        'description2' => 'nullable|string',
        'description3' => 'nullable|string',
        'description4' => 'nullable|string',
        'description5' => 'nullable|string',
    ]);

    DB::beginTransaction();
    try {
        $farm = WhatInFarms::findOrFail($id);

        // Update normal fields except images
        $data = $request->except(['image', 'images']);
        
        // ✅ Handle main image replacement
        if ($request->hasFile('image')) {
            // delete old image if exists
            if ($farm->image && file_exists(public_path('uploads/what_in_farm/'.$farm->image))) {
                unlink(public_path('uploads/what_in_farm/'.$farm->image));
            }

            $imageName = time().'_'.$request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads/what_in_farm'), $imageName);
            $data['image'] = $imageName;
        }

        // Update main farm record
        $farm->update($data);

        // ✅ Handle multiple gallery images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $galleryName = time().'_'.$file->getClientOriginalName();
                $file->move(public_path('uploads/what_in_farm/gallery'), $galleryName);

                // Assuming you have relation: WhatInFarms -> hasMany(WhatInFarmImage::class, 'farm_id')
                $farm->images()->create([
                    'image' => $galleryName
                ]);
            }
        }

        DB::commit();
        return redirect()->route('what-in-farm.index')->with('success', 'Record updated successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors($e->getMessage())->withInput();
    }
}

    public function destroy($id)
    {
        $farm = WhatInFarms::findOrFail($id);
        $farm->delete();

        return redirect()->route('what-in-farm.index')->with('success','Record deleted successfully.');
    }
    public function deleteImage($id)
{
    $image = WhatInFarmImage::findOrFail($id);

    // Delete image file from public folder
    $path = public_path('uploads/what_in_farm/gallery/' . $image->image);
    if (file_exists($path)) {
        unlink($path);
    }

    $image->delete();

    return back()->with('success', 'Image deleted successfully.');
}
}
