<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Founder;
use Illuminate\Http\Request;

class FounderController extends Controller
{
    public function index()
    {
        $founders = Founder::orderBy('sort_order')->orderBy('created_at')->paginate(20);
        return view('admin.founders.index', compact('founders'));
    }

    public function create()
    {
        return view('admin.founders.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'role'           => 'required|string|max:255',
            'bio'            => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'linkedin_url'   => 'nullable|url|max:255',
            'twitter_url'    => 'nullable|url|max:255',
            'instagram_url'  => 'nullable|url|max:255',
            'sort_order'     => 'nullable|integer|min:0',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_founder.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/founders'), $filename);
            $data['image'] = $filename;
        }

        Founder::create($data);
        return redirect()->route('admin.founders.index')->with('success', 'Founder added successfully!');
    }

    public function edit(Founder $founder)
    {
        return view('admin.founders.form', compact('founder'));
    }

    public function update(Request $request, Founder $founder)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'role'           => 'required|string|max:255',
            'bio'            => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'linkedin_url'   => 'nullable|url|max:255',
            'twitter_url'    => 'nullable|url|max:255',
            'instagram_url'  => 'nullable|url|max:255',
            'sort_order'     => 'nullable|integer|min:0',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($founder->image && file_exists(public_path('uploads/founders/' . $founder->image))) {
                unlink(public_path('uploads/founders/' . $founder->image));
            }
            $file = $request->file('image');
            $filename = time() . '_founder.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/founders'), $filename);
            $data['image'] = $filename;
        }

        $founder->update($data);
        return redirect()->route('admin.founders.index')->with('success', 'Founder updated successfully!');
    }

    public function destroy(Founder $founder)
    {
        if ($founder->image && file_exists(public_path('uploads/founders/' . $founder->image))) {
            unlink(public_path('uploads/founders/' . $founder->image));
        }
        $founder->delete();
        return redirect()->route('admin.founders.index')->with('success', 'Founder deleted successfully!');
    }
}
