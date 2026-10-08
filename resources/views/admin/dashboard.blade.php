@extends('layouts.admin')
@section('title', 'Dashboard')

@section('styles')
<style>
/* ===== WELCOME HEADER ===== */
.dash-header {
    background: linear-gradient(135deg, #1A1B2F 0%, #252742 100%);
    border-radius: 16px;
    padding: 28px 32px;
    margin-bottom: 28px;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.dash-header h1 { font-size: 22px; font-weight: 700; margin: 0 0 5px; }
.dash-header p  { font-size: 14px; color: rgba(255,255,255,0.6); margin: 0; }
.dash-date-box {
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 12px;
    padding: 12px 22px;
    text-align: center;
    flex-shrink: 0;
}
.dash-date-box .day  { font-size: 32px; font-weight: 800; line-height: 1; }
.dash-date-box .mon  { font-size: 13px; color: rgba(255,255,255,0.65); margin-top: 3px; }

/* ===== STAT GRIDS ===== */
.stats-row-4 {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 16px;
}
.stats-row-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 28px;
}

/* ===== STAT CARD ===== */
.stat-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 18px;
    border-radius: 14px;
    background: #fff;
    border: 1px solid #eee;
    text-decoration: none !important;
    color: #1f2937 !important;
    transition: all 0.22s ease;
    position: relative;
    overflow: hidden;
}
.stat-card::before {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 3px;
    background: var(--accent, #ccc);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.22s ease;
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 28px rgba(0,0,0,0.08);
    text-decoration: none !important;
}
.stat-card:hover::before { transform: scaleX(1); }
.stat-card:active { transform: scale(0.98); }
.stat-card * { text-decoration: none !important; }

.stat-icon {
    width: 54px; height: 54px;
    border-radius: 13px;
    display: flex; align-items: center; justify-content: center;
    font-size: 21px; flex-shrink: 0;
}
.stat-info { flex: 1; min-width: 0; }
.stat-info h3 { font-size: 27px; font-weight: 800; margin: 0; line-height: 1; color: #111827; }
.stat-info p  { margin: 5px 0 0; font-size: 13px; color: #6b7280; font-weight: 500; }
.stat-info small { font-size: 12px; font-weight: 600; }

.stat-pill {
    position: absolute;
    top: 11px; right: 11px;
    padding: 2px 9px;
    border-radius: 99px;
    font-size: 10px; font-weight: 700;
    line-height: 1.5;
}
.stat-pill-danger  { background: #FEE2E2; color: #B91C1C; }
.stat-pill-warning { background: #FEF3C7; color: #D97706; }

/* ===== PANELS ===== */
.dash-panels {
    display: grid;
    grid-template-columns: 3fr 2fr;
    gap: 20px;
}
.panel {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #eee;
    overflow: hidden;
}
.panel-header {
    padding: 16px 20px;
    border-bottom: 1px solid #f3f4f6;
    display: flex; align-items: center; justify-content: space-between;
}
.panel-header h2 { font-size: 15px; font-weight: 700; color: #111827; margin: 0; }
.panel-header a  { font-size: 13px; color: var(--admin-primary); text-decoration: none; font-weight: 500; }
.panel-header a:hover { text-decoration: underline; }

/* ===== TABLE ===== */
.data-table { width: 100%; border-collapse: collapse; }
.data-table th {
    padding: 11px 14px;
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.06em;
    color: #9ca3af; background: #fafafa;
    border-bottom: 1px solid #f0f0f0; text-align: left;
}
.data-table td {
    padding: 13px 14px;
    font-size: 13.5px; color: #374151;
    border-bottom: 1px solid #f9f9f9;
    vertical-align: middle;
}
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover { background: #fafafa; }

/* ===== BADGES ===== */
.badge {
    display: inline-flex; align-items: center;
    padding: 3px 9px; border-radius: 99px;
    font-size: 11.5px; font-weight: 600;
}
.badge-appointment { background: #E0F2FE; color: #0284C7; }
.badge-test        { background: #F0FDF4; color: #16A34A; }
.badge-adoption    { background: #FFF7ED; color: #EA580C; }
.badge-vaccination { background: #F5F3FF; color: #7C3AED; }
.badge-warning     { background: #FEF3C7; color: #D97706; }
.badge-success     { background: #D1FAE5; color: #065F46; }
.badge-danger      { background: #FEE2E2; color: #B91C1C; }
.badge-info        { background: #DBEAFE; color: #1D4ED8; }
.badge-secondary   { background: #F3F4F6; color: #6B7280; }
.badge-unread      { background: #FEF3C7; color: #B45309; }
.badge-read        { background: #F3F4F6; color: #9CA3AF; }

/* ===== MESSAGE ROWS ===== */
.msg-row {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 13px 20px;
    border-bottom: 1px solid #f9f9f9;
    text-decoration: none; color: inherit;
    transition: background 0.15s;
}
.msg-row:last-child { border-bottom: none; }
.msg-row:hover { background: #fafafa; text-decoration: none; color: inherit; }

.msg-avatar {
    width: 38px; height: 38px; border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 14px; font-weight: 700;
    flex-shrink: 0; text-transform: uppercase;
}
.msg-body  { flex: 1; min-width: 0; }
.msg-name  { font-size: 13.5px; font-weight: 600; color: #111827; margin: 0 0 3px; }
.msg-subj  { font-size: 12.5px; color: #9ca3af; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.msg-meta  { display: flex; flex-direction: column; align-items: flex-end; gap: 5px; flex-shrink: 0; }
.msg-time  { font-size: 11px; color: #c4c8d0; white-space: nowrap; }

/* ===== NO DATA ===== */
.no-data {
    padding: 44px 20px; text-align: center; color: #c4c8d0;
}
.no-data i { font-size: 36px; margin-bottom: 10px; display: block; }
.no-data p { font-size: 13.5px; margin: 0; }

/* ===== RESPONSIVE ===== */
@media (max-width: 1280px) {
    .stats-row-4 { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 960px) {
    .dash-panels { grid-template-columns: 1fr; }
    .stats-row-3 { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 640px) {
    .stats-row-4, .stats-row-3 { grid-template-columns: 1fr; }
    .dash-header { flex-direction: column; gap: 16px; }
    .dash-date-box { align-self: flex-start; }
}
</style>
@endsection

@section('content')

{{-- ===== WELCOME HEADER ===== --}}
<div class="dash-header">
    <div>
        <h1>Welcome back, Admin! 👋</h1>
        <p>Here's everything happening with your pet store right now.</p>
    </div>
    <div class="dash-date-box">
        <div class="day">{{ now()->format('d') }}</div>
        <div class="mon">{{ now()->format('M Y') }}</div>
    </div>
</div>

{{-- ===== ROW 1: Booking Stats ===== --}}
<div class="stats-row-4">

    <a href="{{ route('admin.appointment-bookings.index') }}" class="stat-card" style="--accent:#0284C7;">
        <div class="stat-icon" style="background:#E0F2FE;color:#0284C7;">
            <i class="fas fa-user-md"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['appointments'] }}</h3>
            <p>Appointments</p>
        </div>
    </a>

    <a href="{{ route('admin.test-bookings.index') }}" class="stat-card" style="--accent:#16A34A;">
        <div class="stat-icon" style="background:#F0FDF4;color:#16A34A;">
            <i class="fas fa-vial"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['tests'] }}</h3>
            <p>Diagnostic Tests</p>
        </div>
    </a>

    <a href="{{ route('admin.adoption-bookings.index') }}" class="stat-card" style="--accent:#EA580C;">
        <div class="stat-icon" style="background:#FFF7ED;color:#EA580C;">
            <i class="fas fa-paw"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['adoptions'] }}</h3>
            <p>Adoption Requests</p>
        </div>
    </a>

    <a href="{{ route('admin.bookings.index') }}" class="stat-card" style="--accent:#7C3AED;">
        <div class="stat-icon" style="background:#F5F3FF;color:#7C3AED;">
            <i class="fas fa-syringe"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['vaccinations'] }}</h3>
            <p>Vaccinations</p>
        </div>
    </a>

</div>

{{-- ===== ROW 2: Feature Stats ===== --}}
<div class="stats-row-3">

    <a href="{{ route('admin.pet-adoptions.index') }}" class="stat-card" style="--accent:#0D9488;">
        <div class="stat-icon" style="background:#CCFBF1;color:#0D9488;">
            <i class="fas fa-dog"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['pets_available'] }}</h3>
            <p>Pets Available for Adoption</p>
            <small style="color:#0D9488;">Listed &amp; Active</small>
        </div>
    </a>

    <a href="{{ route('admin.pending') }}" class="stat-card" style="--accent:#D97706;">
        <div class="stat-icon" style="background:#FEF3C7;color:#D97706;">
            <i class="fas fa-hourglass-half"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['pending'] }}</h3>
            <p>Total Pending Requests</p>
            <small style="color:#D97706;">All modules combined</small>
        </div>
        @if($stats['pending'] > 0)
            <span class="stat-pill stat-pill-danger">Action Needed</span>
        @endif
    </a>

    <a href="{{ route('admin.contacts.index') }}" class="stat-card" style="--accent:#DB2777;">
        <div class="stat-icon" style="background:#FCE7F3;color:#DB2777;">
            <i class="fas fa-envelope-open-text"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['messages'] }}</h3>
            <p>Contact Messages</p>
            @if($stats['unread_messages'] > 0)
                <small style="color:#D97706;">{{ $stats['unread_messages'] }} unread</small>
            @else
                <small style="color:#10B981;">All read</small>
            @endif
        </div>
        @if($stats['unread_messages'] > 0)
            <span class="stat-pill stat-pill-warning">{{ $stats['unread_messages'] }} New</span>
        @endif
    </a>

</div>

{{-- ===== PANELS ===== --}}
<div class="dash-panels">

    {{-- Recent Pending Bookings --}}
    <div class="panel">
        <div class="panel-header">
            <h2>
                <i class="fas fa-clock" style="color:#D97706;margin-right:7px;"></i>
                Recent Pending Bookings
            </h2>
            <a href="{{ route('admin.pending') }}">View All Pending →</a>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Pet</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentBookings as $booking)
                <tr>
                    <td>
                        <div style="font-weight:600;color:#111827;line-height:1.3;">{{ $booking->name }}</div>
                        <div style="font-size:12px;color:#9ca3af;margin-top:2px;">
                            {{ $booking->phone ?? $booking->email ?? '' }}
                        </div>
                    </td>
                    <td>
                        @php
                            $typeClass = match($booking->type) {
                                'Appointment' => 'badge-appointment',
                                'Test'        => 'badge-test',
                                'Adoption'    => 'badge-adoption',
                                'Vaccination' => 'badge-vaccination',
                                default       => 'badge-secondary',
                            };
                            $typeIcon = match($booking->type) {
                                'Appointment' => 'fa-user-md',
                                'Test'        => 'fa-vial',
                                'Adoption'    => 'fa-paw',
                                'Vaccination' => 'fa-syringe',
                                default       => 'fa-circle',
                            };
                        @endphp
                        <span class="badge {{ $typeClass }}">
                            <i class="fas {{ $typeIcon }}" style="margin-right:4px;font-size:10px;"></i>
                            {{ $booking->type }}
                        </span>
                    </td>
                    <td style="color:#374151;">
                        @if($booking->type === 'Adoption')
                            {{ $booking->pet->name ?? '—' }}
                        @else
                            {{ $booking->pet_name ?? '—' }}
                        @endif
                    </td>
                    <td style="color:#9ca3af;font-size:12.5px;">
                        {{ \Carbon\Carbon::parse($booking->created_at)->format('M d, Y') }}
                    </td>
                    <td>
                        <span class="badge badge-warning">Pending</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="no-data">
                            <i class="fas fa-check-circle" style="color:#10B981;"></i>
                            <p>No pending bookings — all clear!</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Recent Messages --}}
    <div class="panel">
        <div class="panel-header">
            <h2>
                <i class="fas fa-envelope" style="color:#DB2777;margin-right:7px;"></i>
                Recent Messages
            </h2>
            <a href="{{ route('admin.contacts.index') }}">View All →</a>
        </div>

        @forelse($recentMessages as $msg)
            <a href="{{ route('admin.contacts.show', $msg->id) }}" class="msg-row">
                <div class="msg-avatar">{{ strtoupper(substr($msg->name, 0, 1)) }}</div>
                <div class="msg-body">
                    <p class="msg-name">{{ $msg->name }}</p>
                    <p class="msg-subj">
                        {{ $msg->subject ?? \Illuminate\Support\Str::limit($msg->message, 40) }}
                    </p>
                </div>
                <div class="msg-meta">
                    <span class="msg-time">{{ \Carbon\Carbon::parse($msg->created_at)->diffForHumans() }}</span>
                    @if(!$msg->is_read)
                        <span class="badge badge-unread">New</span>
                    @else
                        <span class="badge badge-read">Read</span>
                    @endif
                </div>
            </a>
        @empty
            <div class="no-data">
                <i class="fas fa-inbox" style="color:#d1d5db;"></i>
                <p>No messages yet</p>
            </div>
        @endforelse
    </div>

</div>

@endsection
