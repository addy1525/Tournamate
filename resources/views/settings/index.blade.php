@extends('layouts.dashboard')

@section('title', 'Settings')
@section('page-title', 'System Settings')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div
                        style="display: flex; flex-direction: column; align-items: center; margin-bottom: var(--spacing-xl);">
                        <div
                            style="width: 100px; height: 100px; background: var(--color-bg-tertiary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: var(--color-text-primary); margin-bottom: var(--spacing-md);">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <h3 style="margin: 0;">{{ Auth::user()->name }}</h3>
                        <p style="color: var(--color-text-muted);">{{ ucfirst(Auth::user()->role) }}</p>
                    </div>

                    <div class="nav-menu">
                        <a href="#" class="nav-link active" style="margin-bottom: var(--spacing-xs);">
                            <span class="nav-icon"><i class="fas fa-user-circle"></i></span>
                            <span class="nav-text">Profile</span>
                        </a>
                        <a href="#" class="nav-link" style="margin-bottom: var(--spacing-xs);">
                            <span class="nav-icon"><i class="fas fa-lock"></i></span>
                            <span class="nav-text">Security</span>
                        </a>
                        <a href="#" class="nav-link" style="margin-bottom: var(--spacing-xs);">
                            <span class="nav-icon"><i class="fas fa-bell"></i></span>
                            <span class="nav-text">Notifications</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Profile</h3>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-group" style="margin-bottom: var(--spacing-lg);">
                            <label
                                style="display: block; margin-bottom: var(--spacing-xs); color: var(--color-text-secondary);">Full
                                Name</label>
                            <input type="text" class="form-input" value="{{ Auth::user()->name }}">
                        </div>

                        <div class="form-group" style="margin-bottom: var(--spacing-lg);">
                            <label
                                style="display: block; margin-bottom: var(--spacing-xs); color: var(--color-text-secondary);">Email
                                Address</label>
                            <input type="email" class="form-input" value="{{ Auth::user()->email }}">
                        </div>

                        <div class="form-group" style="margin-bottom: var(--spacing-xl);">
                            <label
                                style="display: block; margin-bottom: var(--spacing-xs); color: var(--color-text-secondary);">Bio</label>
                            <textarea class="form-input" rows="4" placeholder="Tell us about yourself..."></textarea>
                        </div>

                        <div style="display: flex; justify-content: flex-end;">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection