@extends('layouts.dashboard')

@section('title', 'Operations')
@section('page-title', 'Operations Center')

@section('content')
    <div class="grid grid-cols-2" style="gap: var(--spacing-xl);">


        <!-- Safety & Weather -->
        <a href="{{ route('admin.safety.index') }}" class="card hover-card operation-card">
            <div class="card-body"
                style="text-align: center; padding: var(--spacing-4xl) var(--spacing-xl); display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%;">
                <div
                    style="width: 80px; height: 80px; background: rgba(0, 212, 255, 0.1); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin-bottom: var(--spacing-lg);">
                    <i class="fas fa-cloud-bolt" style="font-size: 3rem; color: var(--color-electric-blue);"></i>
                </div>
                <h2 class="card-title" style="font-size: var(--font-size-2xl); margin-bottom: var(--spacing-sm);">Safety &
                    Weather</h2>
                <p style="color: var(--color-text-muted); font-size: var(--font-size-lg);">Monitor WBGT, lightning alerts,
                    and protocols</p>
            </div>
        </a>
    </div>
@endsection

@push('styles')
    <style>
        .operation-card {
            transition: all 0.3s ease;
            border: 1px solid transparent;
            text-decoration: none;
            color: inherit;
            min-height: 300px;
        }

        .operation-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
            border-color: var(--color-border-light);
            text-decoration: none;
            color: inherit;
        }
    </style>
@endpush