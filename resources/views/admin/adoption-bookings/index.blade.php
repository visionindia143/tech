@extends('layouts.admin')
@section('title', 'Adoption Bookings')

@section('content')
{{-- Filter bar --}}
<form method="GET" class="filter-bar" style="margin-bottom:20px;">
    <select name="status" class="form-control" style="max-width:160px;" onchange="this.form.submit()">
        <option value="">All Statuses</option>
        @foreach(['pending','confirmed','completed','cancelled'] as $s)
            <option value="{{ $s }}" {{ request('status')==$s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <select name="payment_status" class="form-control" style="max-width:180px;" onchange="this.form.submit()">
        <option value="">All Payments</option>
        @foreach(['not_required','pending','paid','failed'] as $p)
            <option value="{{ $p }}" {{ request('payment_status')==$p ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$p)) }}</option>
        @endforeach
    </select>
    @if(request()->hasAny(['status','payment_status']))
        <a href="{{ route('admin.adoption-bookings.index') }}" class="btn-admin btn-admin-outline btn-admin-sm">Clear</a>
    @endif
</form>

<div class="panel">
    <div class="table-responsive"><table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Pet</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Amount</th>
                <th>Payment</th>
                <th>Status</th>
                <th>WhatsApp</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
            <tr>
                <td><strong>#{{ $booking->id }}</strong></td>
                <td>
                    @if($booking->pet)
                        <a href="{{ route('admin.pet-adoptions.edit', $booking->pet) }}" style="color:var(--admin-primary);font-weight:600;">
                            {{ $booking->pet->name }}
                        </a>
                    @else
                        <span style="color:var(--admin-gray);">Deleted</span>
                    @endif
                </td>
                <td>{{ $booking->name }}<br><small style="color:var(--admin-gray);">{{ $booking->email }}</small></td>
                <td>{{ $booking->phone }}</td>
                <td>{{ $booking->amount ? '₹'.number_format($booking->amount) : '—' }}</td>
                <td>{!! $booking->payment_badge !!}</td>
                <td>{!! $booking->status_badge !!}</td>
                <td>
                    @if($booking->whatsapp_sent)
                        <span class="badge badge-success"><i class="fab fa-whatsapp"></i> Sent</span>
                    @else
                        <span class="badge badge-secondary">—</span>
                    @endif
                </td>
                <td><small>{{ $booking->created_at->format('d M Y') }}</small></td>
                <td>
                    <a href="{{ route('admin.adoption-bookings.show', $booking) }}" class="btn-admin btn-admin-info btn-admin-sm"><i class="fas fa-eye"></i></a>
                </td>
            </tr>
            @empty
            <tr><td colspan="10" style="text-align:center;padding:30px;color:var(--admin-gray);">No bookings found.</td></tr>
            @endforelse
        </tbody>
    </table></div>
    @if($bookings->hasPages())
        <div class="admin-pagination" style="padding:16px;">{{ $bookings->links() }}</div>
    @endif
</div>
@endsection
