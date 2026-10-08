@extends('layouts.admin')
@section('title', 'Nutrition Products')
@section('actions')
    <a href="{{ route('admin.nutrition-products.create') }}" class="btn-admin btn-admin-primary"><i class="fas fa-plus"></i> New Product</a>
@endsection

@section('content')
<div class="panel">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Pet Type</th>
                    <th>Price</th>
                    <th>Price Visibility</th>
                    <th>Status</th>
                    <th style="width:120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            <strong>{{ $product->name }}</strong>
                            @if($product->description)
                                <br><small style="color:var(--admin-gray)">{{ Str::limit($product->description, 80) }}</small>
                            @endif
                        </td>
                        <td>{{ $product->petType->icon ?? '' }} {{ $product->petType->name ?? '-' }}</td>
                        <td>{{ $product->price ? '₹' . number_format($product->price, 2) : '-' }}</td>
                        <td>
                            <span class="badge {{ $product->show_price ? 'badge-success' : 'badge-secondary' }}">
                                {{ $product->show_price ? 'Show price' : 'Hide price' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $product->is_active ? 'badge-success' : 'badge-secondary' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.nutrition-products.edit', $product) }}" class="btn-admin btn-admin-info btn-admin-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.nutrition-products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-admin btn-admin-danger btn-admin-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:30px;color:var(--admin-gray);">No nutrition products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
        <div style="padding:16px 20px;">{{ $products->links() }}</div>
    @endif
</div>
@endsection
