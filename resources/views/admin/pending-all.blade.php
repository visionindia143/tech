@extends('layouts.admin')
@section('title', 'All Pending Requests')

@section('styles')
<style>
/* ===== PAGE HEADER ===== */
.page-top {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 24px;
}
.page-top h1 { font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 4px; }
.page-top p  { color: #9ca3af; font-size: 13.5px; margin: 0; }
.btn-back {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px; border-radius: 10px;
    background: #f3f4f6; color: #374151;
    font-size: 13px; font-weight: 600;
    text-decoration: none; transition: background 0.18s;
}
.btn-back:hover { background: #e5e7eb; text-decoration: none; color: #374151; }

/* ===== SUMMARY CARDS ===== */
.pending-summary {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 14px;
    margin-bottom: 22px;
}
.sum-card {
    background: #fff;
    border-radius: 13px;
    border: 1px solid #eee;
    padding: 18px 16px;
    text-align: center;
}
.sum-card h3 { font-size: 30px; font-weight: 800; margin: 0 0 5px; line-height: 1; }
.sum-card p  { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: #9ca3af; margin: 0; }

/* ===== FILTER TABS ===== */
.filter-tabs {
    display: flex; gap: 8px; flex-wrap: wrap;
    margin-bottom: 18px;
}
.filter-tab {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 16px; border-radius: 99px;
    font-size: 13px; font-weight: 600;
    cursor: pointer; border: 2px solid transparent;
    background: #f3f4f6; color: #6b7280;
    transition: all 0.18s;
    user-select: none;
}
.filter-tab:hover { background: #e5e7eb; color: #374151; }
.filter-tab.active {
    background: var(--admin-primary);
    color: #fff;
    border-color: var(--admin-primary);
}
.tab-count {
    background: rgba(0,0,0,0.08);
    border-radius: 99px;
    padding: 1px 7px; font-size: 11px;
}
.filter-tab.active .tab-count { background: rgba(255,255,255,0.25); }

/* ===== PANEL ===== */
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
.showing-info { font-size: 13px; color: #9ca3af; }

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
    padding: 12px 14px;
    font-size: 13.5px; color: #374151;
    border-bottom: 1px solid #f9f9f9;
    vertical-align: middle;
}
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover { background: #fafafa; }
.data-table tbody tr.row-hidden { display: none; }

/* ===== BADGES ===== */
.badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 99px;
    font-size: 11.5px; font-weight: 600;
}
.badge-appointment { background: #E0F2FE; color: #0284C7; }
.badge-test        { background: #F0FDF4; color: #16A34A; }
.badge-adoption    { background: #FFF7ED; color: #EA580C; }
.badge-vaccination { background: #F5F3FF; color: #7C3AED; }
.badge-warning     { background: #FEF3C7; color: #D97706; }
.badge-secondary   { background: #F3F4F6; color: #6B7280; }

/* ===== BTN VIEW ===== */
.btn-view {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 13px; border-radius: 8px;
    font-size: 12px; font-weight: 600;
    background: var(--admin-primary); color: #fff;
    text-decoration: none; transition: opacity 0.18s;
}
.btn-view:hover { opacity: 0.82; color: #fff; text-decoration: none; }

/* ===== NO DATA ===== */
.no-data {
    padding: 60px 20px; text-align: center; color: #c4c8d0;
}
.no-data i  { font-size: 48px; margin-bottom: 16px; display: block; }
.no-data h3 { font-size: 18px; color: #374151; margin-bottom: 6px; }
.no-data p  { font-size: 13.5px; margin: 0; }

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .pending-summary { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 680px) {
    .pending-summary { grid-template-columns: repeat(2, 1fr); }
    .page-top { flex-direction: column; align-items: flex-start; gap: 12px; }
    .data-table th:nth-child(3),
    .data-table td:nth-child(3) { display: none; }
}
</style>
@endsection

@section('content')

{{-- Page Header --}}
<div class="page-top">
    <div>
        <h1>All Pending Requests</h1>
        <p>All booking requests across every module that are awaiting action</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

{{-- Summary Cards --}}
<div class="pending-summary">
    <div class="sum-card">
        <h3 style="color:#111827;">{{ $counts['total'] }}</h3>
        <p>Total</p>
    </div>
    <div class="sum-card">
        <h3 style="color:#0284C7;">{{ $counts['appointments'] }}</h3>
        <p>Appointments</p>
    </div>
    <div class="sum-card">
        <h3 style="color:#16A34A;">{{ $counts['tests'] }}</h3>
        <p>Tests</p>
    </div>
    <div class="sum-card">
        <h3 style="color:#EA580C;">{{ $counts['adoptions'] }}</h3>
        <p>Adoptions</p>
    </div>
    <div class="sum-card">
        <h3 style="color:#7C3AED;">{{ $counts['vaccinations'] }}</h3>
        <p>Vaccinations</p>
    </div>
</div>

{{-- Filter Tabs --}}
<div class="filter-tabs">
    <button class="filter-tab active" onclick="filterRows('all', this)">
        <i class="fas fa-list"></i> All
        <span class="tab-count">{{ $counts['total'] }}</span>
    </button>
    <button class="filter-tab" onclick="filterRows('appointment', this)">
        <i class="fas fa-user-md"></i> Appointments
        <span class="tab-count">{{ $counts['appointments'] }}</span>
    </button>
    <button class="filter-tab" onclick="filterRows('test', this)">
        <i class="fas fa-vial"></i> Tests
        <span class="tab-count">{{ $counts['tests'] }}</span>
    </button>
    <button class="filter-tab" onclick="filterRows('adoption', this)">
        <i class="fas fa-paw"></i> Adoptions
        <span class="tab-count">{{ $counts['adoptions'] }}</span>
    </button>
    <button class="filter-tab" onclick="filterRows('vaccination', this)">
        <i class="fas fa-syringe"></i> Vaccinations
        <span class="tab-count">{{ $counts['vaccinations'] }}</span>
    </button>
</div>

{{-- Table --}}
<div class="panel">
    <div class="panel-header">
        <h2>
            <i class="fas fa-hourglass-half" style="color:#D97706;margin-right:8px;"></i>
            Pending Requests
        </h2>
        <span class="showing-info" id="showing-label">
            Showing {{ $counts['total'] }} of {{ $counts['total'] }} requests
        </span>
    </div>

    @if($allPending->isEmpty())
        <div class="no-data">
            <i class="fas fa-check-circle" style="color:#10B981;"></i>
            <h3>All Clear!</h3>
            <p>No pending requests right now. Great job staying on top of things!</p>
        </div>
    @else
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:36px;">#</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Type</th>
                    <th>Pet / Item</th>
                    <th>Submitted</th>
                    <th style="width:80px;">Action</th>
                </tr>
            </thead>
            <tbody id="pending-tbody">
                @foreach($allPending->values() as $i => $booking)
                <tr data-type="{{ strtolower($booking->type) }}">
                    <td style="color:#c4c8d0;font-size:12px;">{{ $i + 1 }}</td>
                    <td>
                        <div style="font-weight:600;color:#111827;">{{ $booking->name }}</div>
                    </td>
                    <td>
                        @if(!empty($booking->phone))
                            <div style="font-size:12.5px;">
                                <i class="fas fa-phone fa-xs" style="color:#c4c8d0;margin-right:4px;"></i>
                                {{ $booking->phone }}
                            </div>
                        @endif
                        @if(!empty($booking->email))
                            <div style="font-size:12px;color:#9ca3af;">
                                <i class="fas fa-envelope fa-xs" style="margin-right:4px;"></i>
                                {{ $booking->email }}
                            </div>
                        @endif
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
                            <i class="fas {{ $typeIcon }}"></i>
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
                    <td>
                        <div style="font-size:13px;color:#6b7280;">
                            {{ \Carbon\Carbon::parse($booking->created_at)->format('M d, Y') }}
                        </div>
                        <div style="font-size:11.5px;color:#c4c8d0;">
                            {{ \Carbon\Carbon::parse($booking->created_at)->diffForHumans() }}
                        </div>
                    </td>
                    <td>
                        <a href="{{ $booking->link }}" class="btn-view">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection

@section('scripts')
<script>
(function () {
    var total = {{ $counts['total'] }};

    window.filterRows = function (type, btn) {
        document.querySelectorAll('.filter-tab').forEach(function (t) { t.classList.remove('active'); });
        btn.classList.add('active');

        var rows = document.querySelectorAll('#pending-tbody tr');
        var visible = 0;

        rows.forEach(function (row) {
            if (type === 'all' || row.dataset.type === type) {
                row.classList.remove('row-hidden');
                visible++;
            } else {
                row.classList.add('row-hidden');
            }
        });

        var label = type === 'all' ? 'all types' : type + 's';
        document.getElementById('showing-label').textContent =
            'Showing ' + visible + ' of ' + total + ' requests (' + label + ')';
    };
}());
</script>
@endsection
