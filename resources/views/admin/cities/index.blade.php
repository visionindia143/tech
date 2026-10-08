@extends('layouts.admin')
@section('title', 'Manage Cities')
@section('actions')
    <a href="{{ route('admin.cities.create') }}" class="btn-admin btn-admin-primary"><i class="fas fa-plus"></i> New City</a>
@endsection
@section('content')
<div class="panel">
    <div class="table-responsive"><table class="data-table">
        <thead><tr><th>Image</th><th>Name</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($cities as $city)
            <tr>
                <td>@if($city->image)<img src="{{ $city->image_url }}" alt="">@else <div style="width:50px;height:50px;background:#f3f4f6;border-radius:8px;"></div> @endif</td>
                <td><strong>{{ $city->name }}</strong></td>
                <td><span class="badge {{ $city->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $city->is_active ? 'Active' : 'Inactive' }}</span></td>
                <td>
                    <div class="btn-group">
                        <a href="{{ route('admin.cities.edit', $city) }}" class="btn-admin btn-admin-info btn-admin-sm"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.cities.destroy', $city) }}" method="POST" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                            <button type="submit" class="btn-admin btn-admin-danger btn-admin-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;padding:30px;color:var(--admin-gray);">No cities</td></tr>
            @endforelse
        </tbody>
    </table></div>
</div>
@endsection
