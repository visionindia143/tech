@extends('layouts.admin')
@section('title', 'Animal Types')
@section('actions')
    <a href="{{ route('admin.animal-types.create') }}" class="btn-admin btn-admin-primary">
        <i class="fas fa-plus"></i> Add Animal Type
    </a>
@endsection

@section('content')
<div class="panel">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:70px;">Icon</th>
                    <th>Name</th>
                    <th>Icon Class</th>
                    <th style="width:100px;">Status</th>
                    <th style="width:80px;">Order</th>
                    <th style="width:110px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($animalTypes as $animalType)
                <tr>
                    <td>
                        <div style="width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,rgba(var(--admin-primary-rgb,255,107,53),0.12),rgba(var(--admin-primary-rgb,255,107,53),0.06));display:flex;align-items:center;justify-content:center;">
                            <i class="{{ $animalType->icon_class }}" style="font-size:1.4rem;color:var(--admin-primary);"></i>
                        </div>
                    </td>
                    <td>
                        <strong style="color:var(--admin-dark);">{{ $animalType->name }}</strong>
                    </td>
                    <td>
                        <code style="background:var(--admin-light-gray,#F5F5F5);padding:3px 8px;border-radius:4px;font-size:0.82rem;color:var(--admin-dark);">{{ $animalType->icon_class }}</code>
                    </td>
                    <td>
                        <span class="badge {{ $animalType->is_active ? 'badge-success' : 'badge-secondary' }}">
                            {{ $animalType->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td style="color:var(--admin-gray);font-size:0.9rem;">{{ $animalType->sort_order }}</td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('admin.animal-types.edit', $animalType) }}" class="btn-admin btn-admin-info btn-admin-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.animal-types.destroy', $animalType) }}" method="POST"
                                  onsubmit="return confirm('Delete this animal type?')">
                                @csrf @method('DELETE')
                                <button class="btn-admin btn-admin-danger btn-admin-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div style="text-align:center;padding:48px 24px;color:var(--admin-gray);">
                            <i class="fas fa-paw" style="font-size:2.5rem;margin-bottom:12px;display:block;opacity:.4;"></i>
                            <div style="font-weight:600;margin-bottom:6px;color:var(--admin-dark);">No animal types yet</div>
                            <div style="font-size:0.88rem;margin-bottom:20px;">Add animal types to display in the homepage carousel.</div>
                            <a href="{{ route('admin.animal-types.create') }}" class="btn-admin btn-admin-primary">
                                <i class="fas fa-plus"></i> Add Animal Type
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
