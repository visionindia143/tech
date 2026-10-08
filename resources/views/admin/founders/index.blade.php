@extends('layouts.admin')
@section('title', 'Founders')
@section('actions')
    <a href="{{ route('admin.founders.create') }}" class="btn-admin btn-admin-primary">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" style="flex-shrink:0"><path d="M7 1v12M1 7h12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        Add Founder
    </a>
@endsection

@section('styles')
<style>
.founders-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.founder-card {
    background: white;
    border-radius: var(--admin-radius);
    border: 1px solid var(--admin-border);
    overflow: hidden;
    transition: box-shadow 0.2s ease;
}

.founder-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.founder-card-header {
    padding: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--admin-light);
    border-bottom: 1px solid var(--admin-border);
}

.founder-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}

.founder-avatar-placeholder {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: var(--admin-gray);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.founder-avatar-placeholder svg {
    width: 24px;
    height: 24px;
}

.founder-actions {
    display: flex;
    gap: 8px;
}

.btn-icon {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s;
    color: var(--admin-gray);
}

.btn-icon:hover {
    background: var(--admin-light);
    color: var(--admin-dark);
}

.btn-icon-edit:hover {
    color: var(--admin-info);
}

.btn-icon-danger:hover {
    color: var(--admin-danger);
    background: #FEF2F2;
}

.founder-card-body {
    padding: 16px;
}

.founder-name {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 4px;
    color: var(--admin-dark);
}

.founder-role {
    font-size: 0.9rem;
    color: var(--admin-gray);
    margin-bottom: 8px;
}

.founder-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 12px;
}

.order-badge {
    background: var(--admin-light);
    color: var(--admin-gray);
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

.badge-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 4px;
}

@media (max-width: 768px) {
    .founders-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .founder-card-header {
        padding: 12px;
    }

    .founder-card-body {
        padding: 12px;
    }
}
</style>
@endsection

@section('content')

@if(session('success'))
<div class="admin-alert admin-alert-success">
    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="7" stroke="currentColor" stroke-width="1.2"/><path d="M5 8l2 2 4-4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
    {{ session('success') }}
</div>
@endif

<div class="panel">
    <div class="panel-header">
        <h2>All Founders / Team Members</h2>
        <span class="panel-count">{{ $founders->total() }} total</span>
    </div>

    <div class="panel-body" style="padding:0;">
        @if($founders->isEmpty())
            <div class="empty-state">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" style="opacity:.3;display:block;margin:0 auto 12px"><circle cx="16" cy="12" r="6" stroke="currentColor" stroke-width="1.5"/><path d="M4 28c0-6.6 5.4-10 12-10s12 3.4 12 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                No founders yet. <a href="{{ route('admin.founders.create') }}">Add the first one</a>.
            </div>
        @else
            <div class="founders-grid">
                @foreach($founders as $founder)
                <div class="founder-card">
                    <div class="founder-card-header">
                        @if($founder->image)
                            <img src="{{ $founder->image_url }}" alt="{{ $founder->name }}" class="founder-avatar">
                        @else
                            <div class="founder-avatar-placeholder">
                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none"><circle cx="20" cy="15" r="7.5" stroke="currentColor" stroke-width="2"/><path d="M5 35c0-8.3 6.7-13.3 15-13.3s15 5.1 15 13.3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                            </div>
                        @endif
                        <div class="founder-actions">
                            <a href="{{ route('admin.founders.edit', $founder) }}" class="btn-icon btn-icon-edit" title="Edit">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M11 4l1 1-8 8H3v-1l8-8z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
                            </a>
                            <form action="{{ route('admin.founders.destroy', $founder) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this founder?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon btn-icon-danger" title="Delete">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 4.5h10M6 4.5V3h4v1.5M6.5 7v5M9.5 7v5M4 4.5l1 9h6l1-9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="founder-card-body">
                        <h3 class="founder-name">{{ $founder->name }}</h3>
                        <p class="founder-role">{{ $founder->role }}</p>
                        <div class="founder-meta">
                            <span class="order-badge">Order: {{ $founder->sort_order }}</span>
                            @if($founder->is_active)
                                <span class="badge badge-success"><span class="badge-dot"></span>Active</span>
                            @else
                                <span class="badge badge-danger"><span class="badge-dot"></span>Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="pagination-wrap">{{ $founders->links() }}</div>
        @endif
    </div>
</div>

@endsection