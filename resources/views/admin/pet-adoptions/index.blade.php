@extends('layouts.admin')
@section('title', 'Pets for Adoption')
@section('actions')
    <a href="{{ route('admin.pet-adoptions.create') }}" class="btn-admin btn-admin-primary"><i class="fas fa-plus"></i> Add Pet</a>
@endsection
@section('content')
<div class="panel">
    <div class="table-responsive"><table class="data-table">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Name / Breed</th>
                <th>Type</th>
                <th>Age / Gender</th>
                <th>Fee</th>
                <th>Status</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pets as $pet)
            <tr>
                <td>
                    <img src="{{ $pet->first_image_url }}" alt="{{ $pet->name }}"
                         style="width:52px;height:52px;object-fit:cover;border-radius:10px;">
                </td>
                <td>
                    <strong>{{ $pet->name }}</strong>
                    @if($pet->breed)<br><small style="color:var(--admin-gray);">{{ $pet->breed }}</small>@endif
                </td>
                <td>{{ ucfirst($pet->pet_type) }}</td>
                <td>
                    {{ $pet->age ?? '—' }}<br>
                    <small style="color:var(--admin-gray);">{{ ucfirst($pet->gender) }}</small>
                </td>
                <td>{{ $pet->adoption_fee ? '₹'.number_format($pet->adoption_fee) : 'Free' }}</td>
                <td>
                    @if($pet->status === 'available')
                        <span class="badge badge-success">Available</span>
                    @elseif($pet->status === 'reserved')
                        <span class="badge badge-warning">Reserved</span>
                    @else
                        <span class="badge badge-secondary">Adopted</span>
                    @endif
                </td>
                <td><span class="badge {{ $pet->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $pet->is_active ? 'Yes' : 'No' }}</span></td>
                <td>
                    <div class="btn-group">
                        <a href="{{ route('admin.pet-adoptions.edit', $pet) }}" class="btn-admin btn-admin-info btn-admin-sm"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.pet-adoptions.destroy', $pet) }}" method="POST" onsubmit="return confirm('Delete this pet?')">
                            @csrf @method('DELETE')
                            <button class="btn-admin btn-admin-danger btn-admin-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;padding:30px;color:var(--admin-gray);">No pets added yet.</td></tr>
            @endforelse
        </tbody>
    </table></div>
    @if($pets->hasPages())
        <div class="admin-pagination" style="padding:16px;">{{ $pets->links() }}</div>
    @endif
</div>
@endsection
