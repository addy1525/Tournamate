@extends('layouts.dashboard')

@section('title', 'Team Directory')
@section('page-title', 'Manage Teams')

@section('content')
    <div style="display: flex; justify-content: flex-end; margin-bottom: 1.5rem;">
        <a href="{{ route('manage-teams.create') }}" class="btn btn-success font-weight-bold shadow-glow-green px-4 rounded-pill">
            <i class="fas fa-plus mr-1"></i> Add New Team
        </a>
    </div>

    <div class="card mb-4" style="background: var(--color-bg-secondary); border: 1px solid var(--color-border); border-radius: 16px; overflow: hidden;">
        <div class="card-header" style="background: var(--color-bg-tertiary); border-bottom: 1px solid var(--color-border); padding: 1.25rem 1.5rem;">
            <h3 class="card-title text-white font-weight-bold m-0" style="font-size: 1.15rem; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-tshirt" style="color: var(--color-rugby-green-light);"></i>
                Registered Rugby Teams
            </h3>
        </div>
        
        <div class="card-body" style="padding: 0;">
            @if($teams->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover" style="width: 100%; margin: 0; color: var(--color-text-secondary); border-collapse: collapse;">
                        <thead>
                            <tr style="background: rgba(0,0,0,0.15); border-bottom: 1px solid var(--color-border);">
                                <th style="padding: 15px 20px; text-align: left; font-weight: 700; color: #fff; border-bottom: none;">Team</th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 700; color: #fff; border-bottom: none;">Manager</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 700; width: 150px; color: #fff; border-bottom: none;">Payment Status</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 700; width: 150px; color: #fff; border-bottom: none;">Amount Paid</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 700; width: 120px; color: #fff; border-bottom: none;">Receipt</th>
                                <th style="padding: 15px 20px; text-align: right; width: 120px; color: #fff; border-bottom: none;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teams as $team)
                                <tr style="border-bottom: 1px solid var(--color-border);">
                                    <td style="padding: 15px 20px; font-weight: 600; color: #fff; border-top: none;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            @if($team->logo)
                                                <img src="{{ Storage::url($team->logo) }}" alt="{{ $team->name }}"
                                                    style="width: 36px; height: 36px; border-radius: 50%; border: 1px solid var(--color-border-light); object-fit: cover;">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($team->name) }}&background=00a86b&color=fff"
                                                    alt="{{ $team->name }}" style="width: 36px; height: 36px; border-radius: 50%; border: 1px solid var(--color-border-light);">
                                            @endif
                                            <span>{{ $team->name }}</span>
                                        </div>
                                    </td>
                                    <td style="padding: 15px 20px; border-top: none;">{{ $team->manager->name ?? $team->manager_name }}</td>
                                    <td style="padding: 15px 20px; text-align: center; border-top: none;">
                                        @if($team->payment_status == 'paid')
                                            <span class="badge" style="background: rgba(0, 168, 107, 0.12); color: var(--color-rugby-green-light); border: 1px solid rgba(0, 168, 107, 0.25); font-weight: 700;">Paid</span>
                                        @elseif($team->payment_status == 'partial')
                                            <span class="badge" style="background: rgba(251, 191, 36, 0.1); color: var(--color-warning); border: 1px solid rgba(251, 191, 36, 0.25); font-weight: 700;">Partial</span>
                                        @else
                                            <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: var(--color-danger); border: 1px solid rgba(239, 68, 68, 0.25); font-weight: 700;">Unpaid</span>
                                        @endif
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center; font-weight: 600; color: #fff; border-top: none;">
                                        RM {{ number_format($team->amount_paid, 2) }}
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center; border-top: none;">
                                        @if($team->receipt_path)
                                            <a href="{{ Storage::url($team->receipt_path) }}" target="_blank" style="color: var(--color-electric-blue); text-decoration: none;" class="font-weight-bold">
                                                <i class="fas fa-file-pdf mr-1"></i> View
                                            </a>
                                        @else
                                            <span style="color: var(--color-text-muted);">-</span>
                                        @endif
                                    </td>
                                    <td style="padding: 15px 20px; text-align: right; border-top: none;">
                                        <div style="display: flex; gap: 12px; justify-content: flex-end; align-items: center;">
                                            <a href="{{ route('manage-teams.edit', $team->id) }}" style="color: var(--color-text-muted); font-size: 1.05rem;" title="Edit Team">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <form action="{{ route('manage-teams.destroy', $team->id) }}" method="POST"
                                                style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this team?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="color: var(--color-danger); font-size: 1.05rem; border: none; background: transparent; padding: 0; cursor: pointer;" title="Delete Team">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
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
                        <i class="fas fa-tshirt"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem;">No Teams Registered</h3>
                    <p style="color: var(--color-text-secondary); max-width: 450px; margin: 0 auto 1.5rem;">
                        Register teams to tournaments to manage fixtures, scoring, and Elo strength ratings.
                    </p>
                    <a href="{{ route('manage-teams.create') }}" class="btn btn-success font-weight-bold shadow-glow-green px-4 rounded-pill">
                        <i class="fas fa-plus"></i> Add New Team
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection