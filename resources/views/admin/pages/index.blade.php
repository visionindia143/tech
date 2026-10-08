@extends('layouts.admin')
@section('title', 'Manage Pages')
@section('actions')
    <a href="{{ route('admin.pages.create') }}" class="btn-admin btn-admin-primary"><i class="fas fa-plus"></i> New Page</a>
@endsection
@section('content')
<div class="panel">
    <div class="table-responsive"><table class="data-table">
        <thead><tr><th>Title</th><th>Slug</th><th>Status</th><th>Updated</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($pages as $page)
            <tr>
                <td><strong>{{ $page->title }}</strong></td>
                <td><small style="color:var(--admin-gray)">/page/{{ $page->slug }}</small></td>
                <td><span class="badge {{ $page->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $page->is_active ? 'Active' : 'Inactive' }}</span></td>
                <td>{{ $page->updated_at->format('M d, Y') }}</td>
                <td>
                    <div class="btn-group">
                        <a href="{{ route('admin.pages.edit', $page) }}" class="btn-admin btn-admin-info btn-admin-sm"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" onsubmit="return confirm('Delete this page?')">@csrf @method('DELETE')
                            <button type="submit" class="btn-admin btn-admin-danger btn-admin-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;padding:30px;color:var(--admin-gray);">No pages</td></tr>
            @endforelse
        </tbody>
    </table></div>
</div>
@endsection
