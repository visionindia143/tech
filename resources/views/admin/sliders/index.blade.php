@extends('layouts.admin')
@section('title', 'Manage Sliders')
@section('actions')
    <a href="{{ route('admin.sliders.create') }}" class="btn-admin btn-admin-primary"><i class="fas fa-plus"></i> New Slider</a>
@endsection

@section('content')
<div class="panel">
    <div class="table-responsive"><table class="data-table">
        <thead><tr><th>Image</th><th>Title</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($sliders as $slider)
            <tr>
                <td><img src="{{ $slider->image_url }}" alt=""></td>
                <td><strong>{{ $slider->title }}</strong><br><small style="color:var(--admin-gray)">{{ Str::limit($slider->subtitle, 50) }}</small></td>
                <td>{{ $slider->order }}</td>
                <td><span class="badge {{ $slider->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $slider->is_active ? 'Active' : 'Inactive' }}</span></td>
                <td>
                    <div class="btn-group">
                        <a href="{{ route('admin.sliders.edit', $slider) }}" class="btn-admin btn-admin-info btn-admin-sm"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST" onsubmit="return confirm('Delete this slider?')">@csrf @method('DELETE')
                            <button type="submit" class="btn-admin btn-admin-danger btn-admin-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;padding:30px;color:var(--admin-gray);">No sliders found</td></tr>
            @endforelse
        </tbody>
    </table></div>
</div>
@endsection
