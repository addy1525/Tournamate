@extends('layouts.dashboard')

@section('title', 'Manage Managers')
@section('page-title', 'Team Manager Registrations')

@section('content')
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-6 col-md-3 mb-3">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between position-relative overflow-hidden h-100">
                <div style="z-index: 2;">
                    <div class="text-tertiary text-uppercase font-weight-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Total Managers</div>
                    <div class="text-white font-weight-bold mt-1 neon-stat-value" style="font-size: 1.8rem; line-height: 1;">
                        {{ $managers->count() }}
                    </div>
                </div>
                <div class="position-absolute" style="right: -10px; top: -10px; opacity: 0.08; font-size: 4rem; color: white;">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between position-relative overflow-hidden h-100" style="border-left: 3px solid var(--color-warning);">
                <div style="z-index: 2;">
                    <div class="text-tertiary text-uppercase font-weight-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Pending Approval</div>
                    <div class="text-white font-weight-bold mt-1 neon-stat-value" style="font-size: 1.8rem; line-height: 1; color: var(--color-warning);">
                        {{ $managers->where('status', 'pending')->count() }}
                    </div>
                </div>
                <div class="position-absolute" style="right: -10px; top: -10px; opacity: 0.08; font-size: 4rem; color: white;">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between position-relative overflow-hidden h-100" style="border-left: 3px solid var(--color-rugby-green-light);">
                <div style="z-index: 2;">
                    <div class="text-tertiary text-uppercase font-weight-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Active</div>
                    <div class="text-white font-weight-bold mt-1 neon-stat-value" style="font-size: 1.8rem; line-height: 1; color: var(--color-rugby-green-light);">
                        {{ $managers->where('status', 'active')->count() }}
                    </div>
                </div>
                <div class="position-absolute" style="right: -10px; top: -10px; opacity: 0.08; font-size: 4rem; color: white;">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between position-relative overflow-hidden h-100" style="border-left: 3px solid var(--color-danger);">
                <div style="z-index: 2;">
                    <div class="text-tertiary text-uppercase font-weight-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Inactive</div>
                    <div class="text-white font-weight-bold mt-1 neon-stat-value" style="font-size: 1.8rem; line-height: 1; color: var(--color-danger);">
                        {{ $managers->where('status', 'inactive')->count() }}
                    </div>
                </div>
                <div class="position-absolute" style="right: -10px; top: -10px; opacity: 0.08; font-size: 4rem; color: white;">
                    <i class="fas fa-user-slash"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Managers List Card -->
    <div class="card mb-4" style="background: var(--color-bg-secondary); border: 1px solid var(--color-border); border-radius: 16px; overflow: hidden;">
        <div class="card-header" style="background: var(--color-bg-tertiary); border-bottom: 1px solid var(--color-border); padding: 1.25rem 1.5rem;">
            <h3 class="card-title text-white font-weight-bold m-0" style="font-size: 1.15rem; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-user-tie" style="color: var(--color-rugby-green-light);"></i>
                Team Managers Directory
            </h3>
        </div>
        
        <div class="card-body" style="padding: 0;">
            @if($managers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover" style="width: 100%; margin: 0; color: var(--color-text-secondary); border-collapse: collapse;">
                        <thead>
                            <tr style="background: rgba(0,0,0,0.15); border-bottom: 1px solid var(--color-border);">
                                <th style="padding: 15px 20px; text-align: left; font-weight: 700; color: #fff; border-bottom: none;">Manager</th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 700; color: #fff; border-bottom: none;">Email</th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 700; color: #fff; border-bottom: none;">Phone</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 700; width: 120px; color: #fff; border-bottom: none;">Status</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 700; width: 150px; color: #fff; border-bottom: none;">Registered</th>
                                <th style="padding: 15px 20px; text-align: right; width: 220px; color: #fff; border-bottom: none;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($managers as $manager)
                                <tr style="border-bottom: 1px solid var(--color-border);">
                                    <td style="padding: 15px 20px; font-weight: 600; color: #fff; border-top: none;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($manager->name) }}&background=00d4ff&color=fff" alt="{{ $manager->name }}"
                                                style="width: 32px; height: 32px; border-radius: 50%; border: 1px solid var(--color-border-light);">
                                            <span>{{ $manager->name }}</span>
                                        </div>
                                    </td>
                                    <td style="padding: 15px 20px; border-top: none;">{{ $manager->email }}</td>
                                    <td style="padding: 15px 20px; border-top: none;">{{ $manager->phone_number ?? '-' }}</td>
                                    <td style="padding: 15px 20px; text-align: center; border-top: none;">
                                        @if($manager->status === 'pending')
                                            <span class="badge" style="background: rgba(251, 191, 36, 0.1); color: var(--color-warning); border: 1px solid rgba(251, 191, 36, 0.25); font-weight: 700;">Pending</span>
                                        @elseif($manager->status === 'active')
                                            <span class="badge" style="background: rgba(0, 168, 107, 0.12); color: var(--color-rugby-green-light); border: 1px solid rgba(0, 168, 107, 0.25); font-weight: 700;">Active</span>
                                        @else
                                            <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: var(--color-danger); border: 1px solid rgba(239, 68, 68, 0.25); font-weight: 700;">Inactive</span>
                                        @endif
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center; color: var(--color-text-muted); border-top: none; font-size: 0.85rem;">
                                        {{ $manager->created_at->format('M d, Y') }}
                                    </td>
                                    <td style="padding: 15px 20px; text-align: right; border-top: none;">
                                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                            @if($manager->status === 'pending')
                                                <form method="POST" action="{{ route('admin.users.approve', $manager->id) }}"
                                                    style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm font-weight-bold"
                                                        onclick="return confirm('Approve this manager?')" style="padding: 4px 10px; font-size: 0.78rem;">
                                                        Approve
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.users.reject', $manager->id) }}"
                                                    style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm font-weight-bold"
                                                        onclick="return confirm('Reject this manager?')" style="padding: 4px 10px; font-size: 0.78rem;">
                                                        Reject
                                                    </button>
                                                </form>
                                            @elseif($manager->status === 'active')
                                                <form method="POST" action="{{ route('admin.users.reject', $manager->id) }}"
                                                    style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm font-weight-bold"
                                                        onclick="return confirm('Deactivate this manager?')" style="padding: 4px 10px; font-size: 0.78rem;">
                                                        Deactivate
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('admin.users.approve', $manager->id) }}"
                                                    style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm font-weight-bold"
                                                        onclick="return confirm('Reactivate this manager?')" style="padding: 4px 10px; font-size: 0.78rem;">
                                                        Reactivate
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align: center; padding: 4rem 2rem; color: var(--color-text-muted);">
                    <div style="font-size: 3.5rem; margin-bottom: 1.5rem; opacity: 0.5;">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem;">No Managers Found</h3>
                    <p style="color: var(--color-text-secondary); max-width: 450px; margin: 0 auto;">
                        Team managers will appear here once they register. You can approve or reject their applications to allow them to manage teams.
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection