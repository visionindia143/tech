@extends('layouts.admin')
@section('title', 'Diagnostic Tests')
@section('actions')
    <a href="{{ route('admin.test-types.create') }}" class="btn-admin btn-admin-primary"><i class="fas fa-plus"></i> New Test</a>
@endsection
@section('content')
<div class="panel">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Test</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($types as $type)
                    <tr>
                        <td>
                            <strong>{{ $type->name }}</strong>
                            @if($type->description)
                                <br><small style="color:var(--admin-gray)">{{ Str::limit($type->description, 70) }}</small>
                            @endif
                        </td>
                        <td>{{ $type->category ?: '-' }}</td>
                        <td>{{ $type->price ? '₹' . number_format($type->price) : '-' }}</td>
                        <td>{{ $type->duration_minutes ? $type->duration_minutes . ' min' : '-' }}</td>
                        <td>
                            <span class="badge {{ $type->is_active ? 'badge-success' : 'badge-secondary' }}">
                                {{ $type->is_active ? 'Visible' : 'Hidden' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.test-types.edit', $type) }}" class="btn-admin btn-admin-info btn-admin-sm"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.test-types.destroy', $type) }}" method="POST" onsubmit="return confirm('Delete this test?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-admin btn-admin-danger btn-admin-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;padding:30px;color:var(--admin-gray);">No diagnostic tests found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($types->hasPages())
        <div style="padding:16px 24px;">{{ $types->links() }}</div>
    @endif
</div>
@endsection