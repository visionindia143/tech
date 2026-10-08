<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PetAdoption;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PetAdoptionController extends Controller
{
    public function index()
    {
        $pets = PetAdoption::orderBy('order')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.pet-adoptions.index', compact('pets'));
    }

    public function create()
    {
        return view('admin.pet-adoptions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'breed'             => 'nullable|string|max:100',
            'age'               => 'nullable|string|max:50',
            'gender'            => 'required|in:male,female,unknown',
            'size'              => 'nullable|string|max:50',
            'color'             => 'nullable|string|max:100',
            'pet_type'          => 'required|string|max:50',
            'short_description' => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'adoption_fee'      => 'nullable|numeric|min:0',
            'is_vaccinated'     => 'nullable|boolean',
            'is_neutered'       => 'nullable|boolean',
            'status'            => 'required|in:available,reserved,adopted',
            'is_active'         => 'nullable|boolean',
            'is_featured'       => 'nullable|boolean',
            'order'             => 'nullable|integer',
            'images.*'          => 'nullable|image|max:2048',
        ]);

        $data['slug']        = PetAdoption::generateSlug($data['name']);
        $data['is_vaccinated'] = $request->boolean('is_vaccinated');
        $data['is_neutered']   = $request->boolean('is_neutered');
        $data['is_active']     = $request->boolean('is_active', true);
        $data['is_featured']   = $request->boolean('is_featured');
        $data['images']        = $this->handleImages($request);

        PetAdoption::create($data);

        return redirect()->route('admin.pet-adoptions.index')->with('success', 'Pet added successfully!');
    }

    public function edit(PetAdoption $petAdoption)
    {
        return view('admin.pet-adoptions.edit', compact('petAdoption'));
    }

    public function update(Request $request, PetAdoption $petAdoption)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'breed'             => 'nullable|string|max:100',
            'age'               => 'nullable|string|max:50',
            'gender'            => 'required|in:male,female,unknown',
            'size'              => 'nullable|string|max:50',
            'color'             => 'nullable|string|max:100',
            'pet_type'          => 'required|string|max:50',
            'short_description' => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'adoption_fee'      => 'nullable|numeric|min:0',
            'is_vaccinated'     => 'nullable|boolean',
            'is_neutered'       => 'nullable|boolean',
            'status'            => 'required|in:available,reserved,adopted',
            'is_active'         => 'nullable|boolean',
            'is_featured'       => 'nullable|boolean',
            'order'             => 'nullable|integer',
            'images.*'          => 'nullable|image|max:2048',
        ]);

        $data['is_vaccinated'] = $request->boolean('is_vaccinated');
        $data['is_neutered']   = $request->boolean('is_neutered');
        $data['is_active']     = $request->boolean('is_active', true);
        $data['is_featured']   = $request->boolean('is_featured');

        // Handle images: keep existing + add new
        $existingImages = $petAdoption->images ?? [];
        $newImages      = $this->handleImages($request);
        $data['images'] = array_merge($existingImages, $newImages);

        // Remove deleted images
        if ($request->has('remove_images')) {
            $toRemove = $request->input('remove_images', []);
            $data['images'] = array_values(array_diff($data['images'], $toRemove));
            foreach ($toRemove as $img) {
                @unlink(public_path('uploads/adoptions/' . $img));
            }
        }

        $petAdoption->update($data);

        return redirect()->route('admin.pet-adoptions.index')->with('success', 'Pet updated successfully!');
    }

    public function destroy(PetAdoption $petAdoption)
    {
        foreach ($petAdoption->images ?? [] as $img) {
            @unlink(public_path('uploads/adoptions/' . $img));
        }
        $petAdoption->delete();

        return redirect()->route('admin.pet-adoptions.index')->with('success', 'Pet deleted.');
    }

    private function handleImages(Request $request): array
    {
        $uploaded = [];
        if ($request->hasFile('images')) {
            $dir = public_path('uploads/adoptions');
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            foreach ($request->file('images') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($dir, $filename);
                $uploaded[] = $filename;
            }
        }
        return $uploaded;
    }
}
