<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function apiIndex()
    {
        $Banners = Banner::with('user', 'category')->all();
        return response()->json($Banners);
    }

    public function apiStore(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'description' => 'required|min:10'
        ]);

        $path = "https://via.placeholder.com/720x480";

        if ($request->hasFile('thumbnail')) {
            $path =  $request->file('thumbnail')->store('thumbnail');
        }

        $banner = new Banner();
        $banner->user_id = $request->user()->id;
        $banner->category_id = $request->input('category_id');
        $banner->thumbnail = $path;
        $banner->title = $request->input('title');
        $banner->description = $request->input('description');
        $banner->save();
        return response()->json($banner);
    }


    public function apiBanner($id)
    {
        $banner = Banner::with('user', 'category')->find($id);
        return response()->json($banner);
    }

    public function apiUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'description' => 'required|min:10'
        ]);

        $Banner = Banner::with('user', 'category')->find($id);

        $path = $Banner->path;

        if ($request->hasFile('thumbnail')) {
            $path =  $request->file('thumbnail')->store('thumbnail');
        }
        $Banner->user_id = $request->user()->id;
        $Banner->category_id = $request->input('category_id');
        $Banner->thumbnail = $path;
        $Banner->title = $request->input('title');
        $Banner->description = $request->input('description');
        $Banner->save();
        return response()->json($Banner);
    }

    public function apiDestroy($id)
    {
        $Banner = Banner::find($id);
        $Banner->delete();
        return response()->json(['message' => 'Delete Banner successfuly']);
    }
}
