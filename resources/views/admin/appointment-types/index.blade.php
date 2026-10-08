@extends('layouts.admin')
@section('title', 'Manage Appointment Type')
@section('actions')
    <a href="{{ route('admin.appointment-types.create') }}" class="btn-admin btn-admin-primary"><i class="fas fa-plus"></i> New
        Service</a>
@endsection
@section('content')
    <div class="panel">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Icon</th>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointment_types as $appointment_type)
                        <tr>
                            <td>
                                <div
                                    style="width:44px;height:44px;background:#FFF3E0;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#FF6B35;">
                                    <i class="fas fa-paw"></i></div>
                            </td>
                            <td><strong>{{ $appointment_type->title }}</strong></td>
                            <td>{{ $appointment_type->price ? '₹' . number_format($appointment_type->price) : '-' }}</td>
                            <td><span
                                    class="badge {{ $appointment_type->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $appointment_type->is_active ? 'Active' : 'Inactive' }}</span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.appointment-types.edit', $appointment_type) }}"
                                        class="btn-admin btn-admin-info btn-admin-sm"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.appointment-types.destroy', $appointment_type) }}"
                                        method="POST" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                        <button type="submit" class="btn-admin btn-admin-danger btn-admin-sm"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:30px;color:var(--admin-gray);">No services
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
