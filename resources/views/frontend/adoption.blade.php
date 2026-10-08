@extends('layouts.frontend')

@section('meta_title', 'Pets for Adoption - ' . $settings['site_name'])
@section('meta_description', 'Browse our adorable pets available for adoption. Give a loving home to a furry friend today.')

@section('content')
<div class="page-hero">
    <div class="container">
        <h1>Pets for Adoption</h1>
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a> / Adoption
        </div>
    </div>
</div>

<section class="section">
    <div class="container">

        {{-- Filters --}}
        <form method="GET" action="{{ route('adoption.index') }}" style="margin-bottom:36px;">
            <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;background:white;padding:20px 24px;border-radius:var(--radius-md);box-shadow:var(--shadow-sm);">
                <select name="type" class="form-control" style="max-width:160px;">
                    <option value="">All Types</option>
                    @foreach($petTypes as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
                <select name="gender" class="form-control" style="max-width:140px;">
                    <option value="">Any Gender</option>
                    <option value="male"    {{ request('gender') == 'male'    ? 'selected' : '' }}>Male</option>
                    <option value="female"  {{ request('gender') == 'female'  ? 'selected' : '' }}>Female</option>
                </select>
                <select name="size" class="form-control" style="max-width:140px;">
                    <option value="">Any Size</option>
                    <option value="small"   {{ request('size') == 'small'   ? 'selected' : '' }}>Small</option>
                    <option value="medium"  {{ request('size') == 'medium'  ? 'selected' : '' }}>Medium</option>
                    <option value="large"   {{ request('size') == 'large'   ? 'selected' : '' }}>Large</option>
                </select>
                <button type="submit" class="btn btn-primary" style="padding:10px 24px;">
                    <i class="fas fa-search"></i> Filter
                </button>
                @if(request()->hasAny(['type','gender','size']))
                    <a href="{{ route('adoption.index') }}" class="btn btn-outline" style="padding:10px 20px;">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
            </div>
        </form>

        {{-- Pet Grid --}}
        @if($pets->count())
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(290px,1fr));gap:28px;">
            @foreach($pets as $pet)
            <a href="{{ route('adoption.show', $pet->slug) }}" style="text-decoration:none;color:inherit;display:block;" class="fade-up">
                <div class="adoption-card" style="background:white;border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);transition:var(--transition);">
                    <div style="position:relative;overflow:hidden;height:240px;">
                        <img src="{{ $pet->first_image_url }}" alt="{{ $pet->name }}"
                             style="width:100%;height:100%;object-fit:cover;transition:transform 0.4s ease;">
                        @if($pet->is_featured)
                            <span style="position:absolute;top:14px;left:14px;background:var(--clr-primary);color:white;padding:4px 12px;border-radius:50px;font-size:0.75rem;font-weight:700;">Featured</span>
                        @endif
                        <div style="position:absolute;top:14px;right:14px;background:rgba(255,255,255,0.95);padding:4px 12px;border-radius:50px;font-size:0.75rem;font-weight:700;color:{{ $pet->status === 'available' ? 'var(--clr-accent)' : '#F59E0B' }};">
                            {{ ucfirst($pet->status) }}
                        </div>
                    </div>
                    <div style="padding:20px;">
                        <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:8px;">
                            <h3 style="font-size:1.2rem;font-weight:700;">{{ $pet->name }}</h3>
                            @if($pet->adoption_fee)
                                <span style="font-weight:800;color:var(--clr-primary);">₹{{ number_format($pet->adoption_fee) }}</span>
                            @else
                                <span style="font-weight:600;color:var(--clr-accent);font-size:0.85rem;">Free</span>
                            @endif
                        </div>
                        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px;">
                            @if($pet->breed)
                                <span style="background:var(--clr-light-gray);padding:3px 10px;border-radius:50px;font-size:0.78rem;color:var(--clr-gray);">
                                    {{ $pet->breed }}
                                </span>
                            @endif
                            @if($pet->age)
                                <span style="background:var(--clr-light-gray);padding:3px 10px;border-radius:50px;font-size:0.78rem;color:var(--clr-gray);">
                                    <i class="fas fa-birthday-cake"></i> {{ $pet->age }}
                                </span>
                            @endif
                            @if($pet->gender !== 'unknown')
                                <span style="background:var(--clr-light-gray);padding:3px 10px;border-radius:50px;font-size:0.78rem;color:var(--clr-gray);">
                                    <i class="fas fa-{{ $pet->gender === 'male' ? 'mars' : 'venus' }}"></i> {{ ucfirst($pet->gender) }}
                                </span>
                            @endif
                        </div>
                        @if($pet->short_description)
                            <p style="color:var(--clr-gray);font-size:0.88rem;line-height:1.6;margin-bottom:14px;">
                                {{ Str::limit($pet->short_description, 90) }}
                            </p>
                        @endif
                        <div style="display:flex;gap:8px;align-items:center;">
                            @if($pet->is_vaccinated)
                                <span style="font-size:0.75rem;color:var(--clr-accent);font-weight:600;"><i class="fas fa-syringe"></i> Vaccinated</span>
                            @endif
                            @if($pet->is_neutered)
                                <span style="font-size:0.75rem;color:var(--clr-accent);font-weight:600;"><i class="fas fa-check-circle"></i> Neutered</span>
                            @endif
                        </div>
                        <div style="margin-top:16px;color:var(--clr-primary);font-weight:600;font-size:0.9rem;">
                            View Details <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($pets->hasPages())
        <div style="margin-top:40px;display:flex;justify-content:center;">
            {{ $pets->withQueryString()->links() }}
        </div>
        @endif

        @else
        <div style="text-align:center;padding:80px 20px;">
            <div style="font-size:5rem;margin-bottom:20px;">🐾</div>
            <h3 style="font-size:1.5rem;margin-bottom:12px;">No Pets Available Right Now</h3>
            <p style="color:var(--clr-gray);">Check back soon or contact us to get notified when new pets arrive.</p>
            <a href="{{ route('contact') }}" class="btn btn-primary" style="margin-top:24px;">
                <i class="fas fa-bell"></i> Notify Me
            </a>
        </div>
        @endif
    </div>
</section>

<style>
.adoption-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-4px); }
.adoption-card:hover img { transform: scale(1.05); }
.form-control { padding:10px 14px;border:1px solid var(--clr-border);border-radius:var(--radius-sm);font-family:var(--font-body);font-size:0.9rem;background:white; }
.form-control:focus { outline:none;border-color:var(--clr-primary); }
</style>
@endsection
