@extends('layouts.admin')
@section('title', 'Testimonials')
@section('actions')
    <a href="{{ route('admin.testimonials.create') }}" class="btn-admin btn-admin-primary">
        <i class="fas fa-plus"></i> Add Testimonial
    </a>
@endsection

@section('content')
<div class="panel">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Message</th>
                    <th style="width:110px;">Rating</th>
                    <th style="width:100px;">Status</th>
                    <th style="width:80px;">Order</th>
                    <th style="width:110px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($testimonials as $testimonial)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            @if($testimonial->image)
                                <img src="{{ $testimonial->image_url }}" alt="{{ $testimonial->name }}"
                                     style="width:42px;height:42px;border-radius:50%;object-fit:cover;flex-shrink:0;border:2px solid var(--admin-border);">
                            @else
                                <div style="width:42px;height:42px;border-radius:50%;background:var(--admin-primary);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:white;font-weight:700;font-size:1rem;">
                                    {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div style="font-weight:600;color:var(--admin-dark);">{{ $testimonial->name }}</div>
                                @if($testimonial->designation)
                                    <div style="font-size:0.8rem;color:var(--admin-gray);">{{ $testimonial->designation }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="color:var(--admin-gray);font-size:0.88rem;">
                        {{ Str::limit($testimonial->message, 75) }}
                    </td>
                    <td>
                        <div style="display:flex;gap:2px;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star" style="font-size:0.8rem;color:{{ $i <= $testimonial->rating ? '#F59E0B' : '#D1D5DB' }};"></i>
                            @endfor
                        </div>
                        <div style="font-size:0.78rem;color:var(--admin-gray);margin-top:2px;">{{ $testimonial->rating }}/5</div>
                    </td>
                    <td>
                        <span class="badge {{ $testimonial->is_active ? 'badge-success' : 'badge-secondary' }}">
                            {{ $testimonial->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td style="color:var(--admin-gray);font-size:0.9rem;">{{ $testimonial->sort_order }}</td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn-admin btn-admin-info btn-admin-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST"
                                  onsubmit="return confirm('Delete this testimonial?')">
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
                            <i class="fas fa-comments" style="font-size:2.5rem;margin-bottom:12px;display:block;opacity:.4;"></i>
                            <div style="font-weight:600;margin-bottom:6px;color:var(--admin-dark);">No testimonials yet</div>
                            <div style="font-size:0.88rem;margin-bottom:20px;">Add customer testimonials to build trust with new visitors.</div>
                            <a href="{{ route('admin.testimonials.create') }}" class="btn-admin btn-admin-primary">
                                <i class="fas fa-plus"></i> Add Testimonial
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
