<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('order')->paginate(15);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $data = $request->except('image');
        $data['slug'] = Str::slug($request->title);
        
        //  PURIFIER LIBRARY TO CLEAN HTML CONTENT
        $data['description'] = Purifier::clean($request->description);
      
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_service.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/services'), $filename);
            $data['image'] = $filename;
        }

        Service::create($data);
        return redirect()->route('admin.services.index')->with('success', 'Service created successfully!');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $data = $request->except('image');

        $data['description'] = Purifier::clean($request->description);
  
        if ($request->title !== $service->title) {
            $data['slug'] = Str::slug($request->title);
        }

        if ($request->hasFile('image')) {
            if ($service->image && file_exists(public_path('uploads/services/' . $service->image))) {
                unlink(public_path('uploads/services/' . $service->image));
            }
            $file = $request->file('image');
            $filename = time() . '_service.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/services'), $filename);
            $data['image'] = $filename;
        }

        $service->update($data);
        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully!');
    }

    public function destroy(Service $service)
    {
        if ($service->image && file_exists(public_path('uploads/services/' . $service->image))) {
            unlink(public_path('uploads/services/' . $service->image));
        }
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully!');
    }
}
