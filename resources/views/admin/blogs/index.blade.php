@extends('layouts.admin')
@section('title', 'Manage Blogs')

@section('actions')
    <a href="{{ route('admin.blogs.create') }}" class="btn-admin btn-admin-primary">
        <i class="fas fa-plus"></i> New Blog
    </a>
@endsection

@section('content')
<div class="panel">
    <div class="panel-header">
        <h2>All Blog Posts</h2>
        <form action="{{ route('admin.blogs.index') }}" method="GET" class="filter-bar" style="margin:0;">
            <input type="text" name="search" placeholder="Search blogs..." value="{{ request('search') }}">
            <select name="status" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="publish" {{ request('status') == 'publish' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
            <button type="submit" class="btn-admin btn-admin-outline btn-admin-sm"><i class="fas fa-search"></i></button>
        </form>
    </div>
    <div class="table-responsive"><table class="data-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Status</th>
                <th>Home Slider</th>
                <th>Views</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($blogs as $blog)
            <tr>
                <td>
                    @if($blog->image)
                        <img src="{{ $blog->image_url }}" alt="">
                    @else
                        <div style="width:50px;height:50px;background:#f3f4f6;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#9ca3af;"><i class="fas fa-image"></i></div>
                    @endif
                </td>
                <td>
                    <strong>{{ Str::limit($blog->title, 50) }}</strong><br>
                    <small style="color:var(--admin-gray)">/blog/{{ $blog->slug }}</small>
                </td>
                <td>
                    <span class="badge {{ $blog->status === 'publish' ? 'badge-success' : 'badge-secondary' }}">
                        {{ ucfirst($blog->status) }}
                    </span>
                </td>
                <td>
                    <form action="{{ route('admin.blogs.toggle-slider', $blog) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-admin {{ $blog->show_in_slider ? 'btn-admin-success' : 'btn-admin-outline' }} btn-admin-sm" style="min-width:94px;justify-content:center;">
                            {{ $blog->show_in_slider ? 'ON' : 'OFF' }}
                        </button>
                    </form>
                </td>
                <td>{{ number_format($blog->views) }}</td>
                <td>{{ $blog->created_at->format('M d, Y') }}</td>
                <td>
                    <div class="btn-group">
                        <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn-admin btn-admin-info btn-admin-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-admin btn-admin-danger btn-admin-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:30px;color:var(--admin-gray);">No blogs found</td>
            </tr>
            @endforelse
        </tbody>
    </table></div>
    @if($blogs->hasPages())
    <div class="admin-pagination" style="padding:16px 24px;">
        {{ $blogs->links('pagination::simple-bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
