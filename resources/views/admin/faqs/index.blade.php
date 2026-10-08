@extends('layouts.admin')
@section('title', 'FAQs')
@section('actions')
    <a href="{{ route('admin.faqs.create') }}" class="btn-admin btn-admin-primary">
        <i class="fas fa-plus"></i> Add FAQ
    </a>
@endsection

@section('content')
<div class="panel">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Question</th>
                    <th>Answer Preview</th>
                    <th style="width:100px;">Status</th>
                    <th style="width:90px;">Order</th>
                    <th style="width:110px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($faqs as $faq)
                <tr>
                    <td style="color:var(--admin-gray);font-size:0.85rem;">{{ $faq->id }}</td>
                    <td>
                        <div style="font-weight:600;color:var(--admin-dark);margin-bottom:2px;">
                            {{ Str::limit($faq->question, 60) }}
                        </div>
                    </td>
                    <td style="color:var(--admin-gray);font-size:0.88rem;">
                        {{ Str::limit($faq->answer, 70) }}
                    </td>
                   <td>
    <form action="{{ route('admin.faqs.toggle-status', $faq) }}" method="POST">
        @csrf
        @method('PATCH')

        <button type="submit"
                style="border:none;background:none;padding:0;cursor:pointer;">
            <span class="badge {{ $faq->is_active ? 'badge-success' : 'badge-secondary' }}">
                {{ $faq->is_active ? 'Active' : 'Inactive' }}
            </span>
        </button>
    </form>
</td>
                    <td style="color:var(--admin-gray);font-size:0.9rem;">{{ $faq->sort_order }}</td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn-admin btn-admin-info btn-admin-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST"
                                  onsubmit="return confirm('Delete this FAQ?')">
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
                            <i class="fas fa-question-circle" style="font-size:2.5rem;margin-bottom:12px;display:block;opacity:.4;"></i>
                            <div style="font-weight:600;margin-bottom:6px;color:var(--admin-dark);">No FAQs yet</div>
                            <div style="font-size:0.88rem;margin-bottom:20px;">Add your first FAQ to help customers find answers quickly.</div>
                            <a href="{{ route('admin.faqs.create') }}" class="btn-admin btn-admin-primary">
                                <i class="fas fa-plus"></i> Add FAQ
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
