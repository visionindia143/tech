@extends('layouts.admin')
@section('title', 'Manage Gallery')
@section('actions')
    <a href="{{ route('admin.galleries.create') }}" class="btn-admin btn-admin-primary"><i class="fas fa-plus"></i> Add Pet</a>
@endsection
@section('content')
<div class="panel">
    <div class="table-responsive"><table class="data-table">
        <thead><tr><th>Image</th><th>Title</th><th>Type</th><th>Breed</th><th>Status</th><th>Home Slider</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($galleries as $item)
            <tr>
                <td><img src="{{ $item->image_url }}" alt=""></td>
                <td>{{ $item->title }}</td>
                <td>{{ $item->pet_type ?? '-' }}</td>
                <td>{{ $item->breed ?? '-' }}</td>
                <td><span class="badge {{ $item->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $item->is_active ? 'Active' : 'Inactive' }}</span></td>
                <td>
                    <form action="{{ route('admin.galleries.toggle-home', $item) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-admin {{ $item->show_on_home ? 'btn-admin-success' : 'btn-admin-outline' }} btn-admin-sm" style="min-width:94px;justify-content:center;">
                            {{ $item->show_on_home ? 'ON' : 'OFF' }}
                        </button>
                    </form>
                </td>
                <td>
                    <div class="btn-group">
                        <a href="{{ route('admin.galleries.edit', $item) }}" class="btn-admin btn-admin-info btn-admin-sm"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.galleries.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                            <button type="submit" class="btn-admin btn-admin-danger btn-admin-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;padding:30px;color:var(--admin-gray);">No gallery items</td></tr>
            @endforelse
        </tbody>
    </table></div>
</div>
@endsection
