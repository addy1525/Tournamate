@extends('layouts.dashboard')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="card" style="border: 1px solid var(--color-border); background: var(--color-bg-card); border-radius: var(--border-radius-lg); overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
    <div class="card-header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--color-border); background: var(--color-bg-tertiary);">
        <div style="display: flex; flex-direction: column; gap: 4px;">
            <h2 class="card-title" style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--color-text-primary); line-height: 1.2;">Your Notifications</h2>
            <p class="card-subtitle" style="margin: 0; font-size: 0.875rem; color: var(--color-text-tertiary);">Stay updated with the latest tournament activity and safety alerts</p>
        </div>
        
        @if(Auth::user()->unreadNotifications->count() > 0)
            <form action="{{ route('notifications.markAllRead') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn btn-outline-success" style="font-size: 0.875rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-check-double"></i> Mark all as read
                </button>
            </form>
        @endif
    </div>

    <div class="card-body" style="padding: 0;">
        @if($notifications->count() > 0)
            <div style="display: flex; flex-direction: column;">
                @foreach($notifications as $notification)
                    <div class="notification-item {{ $notification->read_at ? '' : 'unread' }}" 
                         style="display: flex; align-items: start; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--color-border); transition: background 0.2s; background: {{ $notification->read_at ? 'transparent' : 'rgba(0, 168, 107, 0.03)' }}; position: relative; cursor: pointer;"
                         onclick="window.location='{{ $notification->data['url'] ?? '#' }}'">
                        
                        {{-- Blue dot indicator for unread --}}
                        @if(!$notification->read_at)
                            <div style="width: 8px; height: 8px; background: var(--color-electric-blue); border-radius: 50%; position: absolute; left: 8px; top: 22px;"></div>
                        @endif

                        <div style="margin-right: 1.25rem; font-size: 1.5rem; display: flex; align-items: center; justify-content: center; width: 42px; height: 42px; background: {{ $notification->read_at ? 'var(--color-bg-tertiary)' : 'var(--color-bg-primary)' }}; border-radius: 50%; color: {{ $notification->data['color'] ?? 'var(--color-electric-blue)' }}; border: 1px solid var(--color-border);">
                            <i class="{{ $notification->data['icon'] ?? 'fas fa-info-circle' }}"></i>
                        </div>

                        <div style="flex: 1;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; flex-wrap: wrap; gap: 0.5rem;">
                                <h3 style="margin: 0; font-size: 1rem; font-weight: {{ $notification->read_at ? '600' : '700' }}; color: var(--color-text-primary);">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </h3>
                                <span style="font-size: 0.75rem; color: var(--color-text-tertiary); display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p style="margin: 0; font-size: 0.875rem; color: var(--color-text-secondary); line-height: 1.4;">
                                {{ $notification->data['message'] ?? '' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
            
            {{-- Pagination Links --}}
            <div style="padding: 1.5rem; border-top: 1px solid var(--color-border); display: flex; justify-content: center;">
                {{ $notifications->links() }}
            </div>
        @else
            <div style="padding: 5rem 2rem; text-align: center; color: var(--color-text-tertiary);">
                <i class="fas fa-bell-slash" style="font-size: 3rem; color: var(--color-border); margin-bottom: 1.5rem; display: block;"></i>
                <h3 style="margin: 0 0 8px 0; font-size: 1.25rem; font-weight: 600; color: var(--color-text-primary);">All Caught Up!</h3>
                <p style="margin: 0; font-size: 0.875rem; color: var(--color-text-secondary);">You have no notifications in your inbox.</p>
            </div>
        @endif
    </div>
</div>
@endsection
