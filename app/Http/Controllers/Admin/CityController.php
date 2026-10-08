<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::orderBy('order')->paginate(15);
        return view('admin.cities.index', compact('cities'));
    }

    public function create()
    {
        return view('admin.cities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:cities,slug',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
        ]);

        $data = $request->except('image');
        $data['slug'] = Str::slug($request->slug ?: $request->name);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_city.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/cities'), $filename);
            $data['image'] = $filename;
        }

        City::create($data);
        return redirect()->route('admin.cities.index')->with('success', 'City created successfully!');
    }

    public function edit(City $city)
    {
        return view('admin.cities.edit', compact('city'));
    }

    public function update(Request $request, City $city)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:cities,slug,' . $city->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
        ]);

        $data = $request->except('image');
        $data['slug'] = Str::slug($request->slug ?: $request->name);

        if ($request->hasFile('image')) {
            if ($city->image && file_exists(public_path('uploads/cities/' . $city->image))) {
                unlink(public_path('uploads/cities/' . $city->image));
            }
            $file = $request->file('image');
            $filename = time() . '_city.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/cities'), $filename);
            $data['image'] = $filename;
        }

        $city->update($data);
        return redirect()->route('admin.cities.index')->with('success', 'City updated successfully!');
    }

    public function destroy(City $city)
    {
        if ($city->image && file_exists(public_path('uploads/cities/' . $city->image))) {
            unlink(public_path('uploads/cities/' . $city->image));
        }
        $city->delete();
        return redirect()->route('admin.cities.index')->with('success', 'City deleted successfully!');
    }
}
