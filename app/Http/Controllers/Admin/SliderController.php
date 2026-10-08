<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('order')->paginate(15);
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5096',
            'mobile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5096',
        ]);

        $data = $request->except(['image', 'mobile_image']);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_slider.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/sliders'), $filename);
            $data['image'] = $filename;
        }

        if ($request->hasFile('mobile_image')) {
            $file = $request->file('mobile_image');
            $filename = time() . '_slider_mobile.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/sliders'), $filename);
            $data['mobile_image'] = $filename;
        }

        Slider::create($data);
        return redirect()->route('admin.sliders.index')->with('success', 'Slider created successfully!');
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5096',
            'mobile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5096',
        ]);

        $data = $request->except(['image', 'mobile_image']);

        if ($request->hasFile('image')) {
            if ($slider->image && file_exists(public_path('uploads/sliders/' . $slider->image))) {
                unlink(public_path('uploads/sliders/' . $slider->image));
            }
            $file = $request->file('image');
            $filename = time() . '_slider.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/sliders'), $filename);
            $data['image'] = $filename;
        }

        if ($request->hasFile('mobile_image')) {
            if ($slider->mobile_image && file_exists(public_path('uploads/sliders/' . $slider->mobile_image))) {
                unlink(public_path('uploads/sliders/' . $slider->mobile_image));
            }
            $file = $request->file('mobile_image');
            $filename = time() . '_slider_mobile.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/sliders'), $filename);
            $data['mobile_image'] = $filename;
        }

        // Mobile image remove karo agar checkbox checked ho
        if ($request->boolean('remove_mobile_image') && $slider->mobile_image) {
            if (file_exists(public_path('uploads/sliders/' . $slider->mobile_image))) {
                unlink(public_path('uploads/sliders/' . $slider->mobile_image));
            }
            $data['mobile_image'] = null;
        }

        $slider->update($data);
        return redirect()->route('admin.sliders.index')->with('success', 'Slider updated successfully!');
    }

    public function destroy(Slider $slider)
    {
        if ($slider->image && file_exists(public_path('uploads/sliders/' . $slider->image))) {
            unlink(public_path('uploads/sliders/' . $slider->image));
        }
        if ($slider->mobile_image && file_exists(public_path('uploads/sliders/' . $slider->mobile_image))) {
            unlink(public_path('uploads/sliders/' . $slider->mobile_image));
        }
        $slider->delete();
        return redirect()->route('admin.sliders.index')->with('success', 'Slider deleted successfully!');
    }
}
