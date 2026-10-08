@extends('layouts.admin')

@section('title', 'Service-City SEO Pages')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    
    <div class="d-flex justify-content-between align-items-center my-4">
        <div>
            <h1 class="h3 mb-0">Service-City SEO Pages</h1>
            <p class="text-muted mb-0">Manage dynamic landing pages for service-city combinations</p>
        </div>
        <div class="btn-group" role="group">
            <a href="{{ route('admin.service-city-seo.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Create New
            </a>
            <form action="{{ route('admin.service-city-seo.bulk-generate') }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="auto_approve" value="0">
                <button type="submit" class="btn btn-success" onclick="return confirm('Generate all missing combinations?\n\nNote: Generated pages will be inactive by default.')">
                    <i class="bi bi-lightning"></i> Bulk Generate
                </button>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    @if($stats)
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="text-muted mb-0">Services</h6>
                    <h3 class="mb-0">{{ $stats['active_services'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="text-muted mb-0">Cities</h6>
                    <h3 class="mb-0">{{ $stats['active_cities'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="text-muted mb-0">Active</h6>
                    <h3 class="mb-0 text-success">{{ $stats['active_combinations'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="text-muted mb-0">Potential</h6>
                    <h3 class="mb-0">{{ $stats['potential_combinations'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="text-muted mb-0">Coverage</h6>
                    <h3 class="mb-0 text-primary">{{ $stats['coverage_percentage'] }}%</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="text-muted mb-0">Missing</h6>
                    <h3 class="mb-0 text-warning">{{ $stats['missing_combinations'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filter Bar -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search by service or city..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="service" class="form-select">
                        <option value="">All Services</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ request('service') == $service->id ? 'selected' : '' }}>
                                {{ $service->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="city" class="form-select">
                        <option value="">All Cities</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Service - City</th>
                        <th>URL</th>
                        <th>SEO Title</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($seoPages as $page)
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ $page->service->title }}</strong>
                                    <br>
                                    <small class="text-muted">in {{ $page->city->name }}</small>
                                </div>
                            </td>
                            <td>
                                <code class="text-primary">{{ $page->service->slug }}-in-{{ $page->city->slug }}</code>
                            </td>
                            <td>
                                <small>{{ Str::limit($page->seo_title ?? 'Auto-generated', 40) }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $page->priority }}</span>
                            </td>
                            <td>
                                <form action="{{ route('admin.service-city-seo.toggle-active', $page) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $page->is_active ? 'btn-success' : 'btn-secondary' }}">
                                        {{ $page->is_active ? '✓ Active' : '✗ Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.service-city-seo.show', $page) }}" class="btn btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.service-city-seo.edit', $page) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.service-city-seo.destroy', $page) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete" 
                                                onclick="return confirm('Delete this page?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No SEO pages found. <a href="{{ route('admin.service-city-seo.create') }}">Create one</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($seoPages->hasPages())
        <div class="card-footer">
            {{ $seoPages->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endsection
