<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestType;
use Illuminate\Http\Request;

class TestTypeController extends Controller
{
    public function index()
    {
        $types = TestType::orderBy('category')->orderBy('name')->paginate(15);

        return view('admin.test-types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.test-types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'price' => 'nullable|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:5|max:1440',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        TestType::create($data);

        return redirect()->route('admin.test-types.index')->with('success', 'Diagnostic test created successfully!');
    }

    public function edit(TestType $testType)
    {
        return view('admin.test-types.edit', compact('testType'));
    }

    public function update(Request $request, TestType $testType)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'price' => 'nullable|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:5|max:1440',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $testType->update($data);

        return redirect()->route('admin.test-types.index')->with('success', 'Diagnostic test updated successfully!');
    }

    public function destroy(TestType $testType)
    {
        $testType->delete();

        return redirect()->route('admin.test-types.index')->with('success', 'Diagnostic test deleted successfully!');
    }
}