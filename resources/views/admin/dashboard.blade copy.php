@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="stats-grid">
    <a href="{{ route('admin.blogs.index') }}" class="stat-card" style="text-decoration:none;color:inherit;transition:box-shadow 0.18s,transform 0.18s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.10)';this.style.transform='translateY(-2px)'" onmouseout="this.style.boxShadow='';this.style.transform=''">
        <div class="stat-icon" style="background:#FFF3E0;color:#FF6B35;">
            <i class="fas fa-blog"></i>
        </div>
        <div>
            <h3>{{ $stats['blogs'] }}</h3>
            <p>Total Blogs</p>
        </div>
    </a>
    <a href="{{ route('admin.bookings.index') }}" class="stat-card" style="text-decoration:none;color:inherit;transition:box-shadow 0.18s,transform 0.18s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.10)';this.style.transform='translateY(-2px)'" onmouseout="this.style.boxShadow='';this.style.transform=''">
        <div class="stat-icon" style="background:#ECFDF5;color:#10B981;">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div>
            <h3>{{ $stats['bookings'] }}</h3>
            <p>Total Bookings</p>
        </div>
    </a>
    <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}" class="stat-card" style="text-decoration:none;color:inherit;transition:box-shadow 0.18s,transform 0.18s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.10)';this.style.transform='translateY(-2px)'" onmouseout="this.style.boxShadow='';this.style.transform=''">
        <div class="stat-icon" style="background:#FFFBEB;color:#F59E0B;">
            <i class="fas fa-clock"></i>
        </div>
        <div>
            <h3>{{ $stats['pending_bookings'] }}</h3>
            <p>Pending Bookings</p>
        </div>
    </a>
    <a href="{{ route('admin.contacts.index', ['status' => 'unread']) }}" class="stat-card" style="text-decoration:none;color:inherit;transition:box-shadow 0.18s,transform 0.18s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.10)';this.style.transform='translateY(-2px)'" onmouseout="this.style.boxShadow='';this.style.transform=''">
        <div class="stat-icon" style="background:#EFF6FF;color:#3B82F6;">
            <i class="fas fa-envelope"></i>
        </div>
        <div>
            <h3>{{ $stats['contacts'] }}</h3>
            <p>Unread Messages</p>
        </div>
    </a>
</div>

{{-- Recent Bookings --}}
<div class="panel">
    <div class="panel-header">
        <h2>Recent Vaccination Bookings</h2>
        <a href="{{ route('admin.bookings.index') }}" class="btn-admin btn-admin-outline btn-admin-sm">View All</a>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Pet</th>
                <th>Vaccination</th>
                <th>City</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentBookings as $booking)
            <tr>
                <td>
                    <strong>{{ $booking->name }}</strong><br>
                    <small style="color:var(--admin-gray)">{{ $booking->email }}</small>
                </td>
                <td>{{ $booking->pet_name }} ({{ $booking->pet_type }})</td>
                <td>{{ $booking->vaccinationType->name ?? '-' }}</td>
                <td>{{ $booking->city->name ?? '-' }}</td>
                <td>{{ $booking->preferred_date->format('M d, Y') }}</td>
                <td>
                    @if($booking->status === 'pending')
                        <span class="badge badge-warning">Pending</span>
                    @elseif($booking->status === 'confirmed')
                        <span class="badge badge-info">Confirmed</span>
                    @elseif($booking->status === 'completed')
                        <span class="badge badge-success">Completed</span>
                    @else
                        <span class="badge badge-danger">Cancelled</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:30px;color:var(--admin-gray);">No bookings yet</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
