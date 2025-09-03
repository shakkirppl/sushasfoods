<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HomeVideoGallary;
use DB;

use App\Helper\File;
class HomeVideoGallaryController  extends Controller
{

   public function index()
    {
        try {
            $videoGallary = HomeVideoGallary::get();
            return view('home-video-gallary.index', compact('videoGallary'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function create() 
    {
        return view('home-video-gallary.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'video' => 'required|file|mimes:mp4,mov,avi,wmv|max:20480' // max 20MB
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Handle video file upload
                $videoFile = $request->file('video');
                $videoName = time().'_'.$videoFile->getClientOriginalName();
                $videoFile->move(public_path('uploads/banner-video'), $videoName);

                HomeVideoGallary::create([
                    'video' => $videoName
                ]);
            });

            return redirect()->route('home-video-gallary.index')->with('success', 'Video uploaded successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function edit(HomeVideoGallary $homeVideoGallary) 
    {
        return view('home-video-gallary.edit', compact('homeVideoGallary'));
    }

    public function update(Request $request, HomeVideoGallary $homeVideoGallary)
    {
        $request->validate([
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:20480'
        ]);

        try {
            DB::transaction(function () use ($request, $homeVideoGallary) {
            
                // If new video is uploaded
                if ($request->hasFile('video')) {
                    $videoFile = $request->file('video');
                    $videoName = time().'_'.$videoFile->getClientOriginalName();
                    $videoFile->move(public_path('uploads/banner-video'), $videoName);
                    $updateData['video'] = $videoName;
                }

                $homeVideoGallary->update($updateData);
            });

            return redirect()->route('home-video-gallary.index')->with('success', 'Video updated successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function destroy(HomeVideoGallary $homeVideoGallary) 
    {
        try {
            DB::transaction(function () use ($homeVideoGallary) {
                $homeVideoGallary->delete();
            });

            return redirect()->route('home-video-gallary.index')->with('success','Video deleted successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
