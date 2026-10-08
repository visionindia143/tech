@extends('layouts.admin')
@section('title', 'Vaccination Types')
@section('actions')
    <a href="{{ route('admin.vaccination-types.create') }}" class="btn-admin btn-admin-primary"><i class="fas fa-plus"></i> New Type</a>
@endsection
@section('content')
<div class="panel">
    <div class="table-responsive"><table class="data-table">
        <thead><tr><th>Name</th><th>Description</th><th>Price</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($types as $type)
            <tr>
                <td><strong>{{ $type->name }}</strong></td>
                <td>{{ Str::limit($type->description, 60) }}</td>
                <td>{{ $type->price ? '₹'.number_format($type->price) : '-' }}</td>
                <td><span class="badge {{ $type->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $type->is_active ? 'Active' : 'Inactive' }}</span></td>
                <td>
                    <div class="btn-group">
                        <a href="{{ route('admin.vaccination-types.edit', $type) }}" class="btn-admin btn-admin-info btn-admin-sm"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.vaccination-types.destroy', $type) }}" method="POST" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                            <button type="submit" class="btn-admin btn-admin-danger btn-admin-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;padding:30px;color:var(--admin-gray);">No vaccination types</td></tr>
            @endforelse
        </tbody>
    </table></div>
</div>
@endsection
