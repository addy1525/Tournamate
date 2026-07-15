@extends('layouts.dashboard')

@section('title', 'Manage Referees')
@section('page-title', 'Referee Management')

@section('content')
    <div style="display: flex; justify-content: flex-end; margin-bottom: 1.5rem;">
        <a href="{{ route('admin.referees.create') }}" class="btn btn-success font-weight-bold shadow-glow-green px-4 rounded-pill">
            <i class="fas fa-user-plus mr-1"></i> Add New Referee
        </a>
    </div>

    <div class="card mb-4" style="background: var(--color-bg-secondary); border: 1px solid var(--color-border); border-radius: 16px; overflow: hidden;">
        <div class="card-header" style="background: var(--color-bg-tertiary); border-bottom: 1px solid var(--color-border); padding: 1.25rem 1.5rem;">
            <h3 class="card-title text-white font-weight-bold m-0" style="font-size: 1.15rem; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-whistle" style="color: var(--color-rugby-green-light);"></i>
                Registered Referees
            </h3>
        </div>
        
        <div class="card-body" style="padding: 0;">
            @if($referees->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover" style="width: 100%; margin: 0; color: var(--color-text-secondary); border-collapse: collapse;">
                        <thead>
                            <tr style="background: rgba(0,0,0,0.15); border-bottom: 1px solid var(--color-border);">
                                <th style="padding: 15px 20px; text-align: left; font-weight: 700; color: #fff; border-bottom: none;">Referee</th>
                                <th style="padding: 15px 20px; text-align: left; font-weight: 700; color: #fff; border-bottom: none;">Email</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 700; width: 120px; color: #fff; border-bottom: none;">Status</th>
                                <th style="padding: 15px 20px; text-align: center; font-weight: 700; width: 150px; color: #fff; border-bottom: none;">Created At</th>
                                <th style="padding: 15px 20px; text-align: right; width: 120px; color: #fff; border-bottom: none;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($referees as $referee)
                                <tr style="border-bottom: 1px solid var(--color-border);">
                                    <td style="padding: 15px 20px; font-weight: 600; color: #fff; border-top: none;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($referee->name) }}&background=00a86b&color=fff" alt="{{ $referee->name }}"
                                                style="width: 32px; height: 32px; border-radius: 50%; border: 1px solid var(--color-border-light);">
                                            <span>{{ $referee->name }}</span>
                                        </div>
                                    </td>
                                    <td style="padding: 15px 20px; border-top: none;">{{ $referee->email }}</td>
                                    <td style="padding: 15px 20px; text-align: center; border-top: none;">
                                        <span class="badge" style="background: rgba(0, 168, 107, 0.12); color: var(--color-rugby-green-light); border: 1px solid rgba(0, 168, 107, 0.25); font-weight: 700;">
                                            {{ ucfirst($referee->status ?? 'active') }}
                                        </span>
                                    </td>
                                    <td style="padding: 15px 20px; text-align: center; color: var(--color-text-muted); border-top: none;">
                                        {{ $referee->created_at->format('M d, Y') }}
                                    </td>
                                    <td style="padding: 15px 20px; text-align: right; border-top: none;">
                                        <form method="POST" action="{{ route('admin.referees.destroy', $referee->id) }}"
                                            style="display: inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this referee?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0" title="Delete Referee" style="font-size: 1.1rem; border: none; background: transparent;">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align: center; padding: 4rem 2rem; color: var(--color-text-muted);">
                    <div style="font-size: 3.5rem; margin-bottom: 1.5rem; opacity: 0.5;">
                        <i class="fas fa-whistle"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem;">No Referees Found</h3>
                    <p style="color: var(--color-text-secondary); max-width: 450px; margin: 0 auto 1.5rem;">
                        Create referee accounts to allow them to manage match fixtures and record scores.
                    </p>
                    <a href="{{ route('admin.referees.create') }}" class="btn btn-success font-weight-bold shadow-glow-green px-4 rounded-pill">
                        <i class="fas fa-plus"></i> Add New Referee
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection