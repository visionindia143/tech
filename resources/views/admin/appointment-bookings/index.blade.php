@extends('layouts.admin')
@section('title', 'Appointment Bookings')

@section('content')
    <div class="panel">
        <div class="panel-header">
            <h2>Appointment Bookings</h2>
            <form action="{{ route('admin.appointment-bookings.index') }}" method="GET" class="filter-bar" style="margin:0;">
                <input type="text" name="search" placeholder="Search name/email/phone..." value="{{ request('search') }}">
                <select name="status" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <select name="payment_status" onchange="this.form.submit()">
                    <option value="">All Payments</option>
                    <option value="not_required" {{ request('payment_status') == 'not_required' ? 'selected' : '' }}>Not
                        Required</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending
                    </option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed
                    </option>
                </select>
                <button type="submit" class="btn-admin btn-admin-outline btn-admin-sm"><i
                        class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Pet</th>
                        <th>Service</th>
                        <th>City</th>
                        <th>Date</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>
                                <strong>{{ $booking->name }}</strong><br>
                                <small style="color:var(--admin-gray)">{{ $booking->phone }} &bull;
                                    {{ $booking->email }}</small>
                            </td>
                            <td>
                                {{ $booking->pet_name }}<br>
                                <small style="color:var(--admin-gray)">{{ $booking->pet_type }}</small>
                            </td>
                            <td>
                                @if ($booking->service)
                                    {{ $booking->service->title }}
                                @elseif($booking->appointmentType)
                                    {{ $booking->appointmentType->title }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $booking->city->name ?? '-' }}</td>
                            <td>{{ $booking->preferred_date->format('M d, Y') }}</td>
                            <td>
                                @php
                                    $payBadge = match ($booking->payment_status) {
                                        'paid' => 'badge-success',
                                        'pending' => 'badge-warning',
                                        'failed' => 'badge-danger',
                                        'not_required' => 'badge-secondary',
                                        default => 'badge-secondary',
                                    };
                                @endphp
                                <span
                                    class="badge {{ $payBadge }}">{{ ucfirst(str_replace('_', ' ', $booking->payment_status)) }}</span>
                                @if ($booking->amount)
                                    <br><small
                                        style="color:var(--admin-gray)">₹{{ number_format($booking->amount) }}</small>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusBadge = match ($booking->status) {
                                        'pending' => 'badge-warning',
                                        'confirmed' => 'badge-info',
                                        'completed' => 'badge-success',
                                        'cancelled' => 'badge-danger',
                                        default => 'badge-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $statusBadge }}">{{ ucfirst($booking->status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.appointment-bookings.show', $booking) }}"
                                    class="btn-admin btn-admin-info btn-admin-sm"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:30px;color:var(--admin-gray);">No
                                appointment bookings found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($bookings->hasPages())
            <div style="padding:16px 24px;">{{ $bookings->appends(request()->query())->links() }}</div>
        @endif
    </div>
@endsection
