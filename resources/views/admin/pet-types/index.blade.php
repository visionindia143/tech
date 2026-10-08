@extends('layouts.admin')
@section('title', 'Pet Types')
@section('actions')
    <a href="{{ route('admin.pet-types.create') }}" class="btn-admin btn-admin-primary"><i class="fas fa-plus"></i> New Pet Type</a>
@endsection
@section('content')
<div class="panel">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:60px;">Order</th>
                    <th style="width:60px;">Icon</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th style="width:120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($petTypes as $petType)
                <tr>
                    <td style="color:var(--admin-gray);font-size:0.85rem;">{{ $petType->sort_order }}</td>
                    <td style="font-size:1.4rem;text-align:center;">{{ $petType->icon }}</td>
                    <td><strong>{{ $petType->name }}</strong></td>
                    <td>
                        <span class="badge {{ $petType->is_active ? 'badge-success' : 'badge-secondary' }}">
                            {{ $petType->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('admin.pet-types.edit', $petType) }}" class="btn-admin btn-admin-info btn-admin-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.pet-types.destroy', $petType) }}" method="POST"
                                  onsubmit="return confirm('Delete {{ $petType->name }}?')">
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
                    <td colspan="5" style="text-align:center;padding:30px;color:var(--admin-gray);">
                        No pet types yet. <a href="{{ route('admin.pet-types.create') }}">Add one</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($petTypes->hasPages())
    <div style="padding:16px 20px;">{{ $petTypes->links() }}</div>
    @endif
</div>
@endsection
