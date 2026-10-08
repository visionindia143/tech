<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::orderBy('order')->paginate(15);
        return view('admin.galleries.index', compact('galleries'));
    }

    public function create()
    {
        return view('admin.galleries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'show_on_home' => 'nullable|boolean',
        ]);

        $data = $request->except('image');
        $data['show_on_home'] = $request->has('show_on_home');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_gallery.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/galleries'), $filename);
            $data['image'] = $filename;
        }

        Gallery::create($data);
        return redirect()->route('admin.galleries.index')->with('success', 'Gallery item created successfully!');
    }

    public function edit(Gallery $gallery)
    {
        return view('admin.galleries.edit', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'show_on_home' => 'nullable|boolean',
        ]);

        $data = $request->except('image');
        $data['show_on_home'] = $request->has('show_on_home');

        if ($request->hasFile('image')) {
            if ($gallery->image && file_exists(public_path('uploads/galleries/' . $gallery->image))) {
                unlink(public_path('uploads/galleries/' . $gallery->image));
            }
            $file = $request->file('image');
            $filename = time() . '_gallery.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/galleries'), $filename);
            $data['image'] = $filename;
        }

        $gallery->update($data);
        return redirect()->route('admin.galleries.index')->with('success', 'Gallery item updated successfully!');
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->image && file_exists(public_path('uploads/galleries/' . $gallery->image))) {
            unlink(public_path('uploads/galleries/' . $gallery->image));
        }
        $gallery->delete();
        return redirect()->route('admin.galleries.index')->with('success', 'Gallery item deleted successfully!');
    }

    public function toggleHome(Gallery $gallery)
    {
        $gallery->update([
            'show_on_home' => ! (bool) $gallery->show_on_home,
        ]);

        return back()->with('success', 'Gallery slider visibility updated successfully!');
    }
}
