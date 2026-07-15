@extends('layouts.dashboard')

@section('title', 'Assign Teams')
@section('page-title', 'Assign Teams to Tournament')

@push('styles')
<style>
    .team-selection-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: var(--spacing-md);
        margin-top: 1rem;
    }
    
    .team-selection-card {
        background: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: 12px;
        padding: 1.25rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        user-select: none;
    }
    
    .team-selection-card:hover {
        border-color: var(--color-border-light);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .team-selection-card.selected {
        border-color: var(--color-rugby-green-light);
        background: rgba(0, 168, 107, 0.06);
        box-shadow: 0 0 15px rgba(0, 168, 107, 0.1);
    }
    
    .team-checkbox-wrapper {
        position: absolute;
        top: 1rem;
        right: 1rem;
    }
    
    .custom-check-indicator {
        width: 20px;
        height: 20px;
        border-radius: 6px;
        border: 2px solid var(--color-border-light);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
    }
    
    .team-selection-card.selected .custom-check-indicator {
        border-color: var(--color-rugby-green-light);
        background: var(--color-rugby-green);
    }
    
    .team-selection-card.selected .custom-check-indicator i {
        display: block;
        color: #fff;
        font-size: 0.7rem;
    }
    
    .custom-check-indicator i {
        display: none;
    }

    .team-card-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: var(--color-bg-tertiary);
        border: 1px solid var(--color-border-light);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: var(--color-text-secondary);
        font-size: 1rem;
        flex-shrink: 0;
    }

    .team-selection-card.selected .team-card-avatar {
        border-color: var(--color-rugby-green-light);
        background: rgba(0, 168, 107, 0.12);
        color: var(--color-rugby-green-light);
    }

    .search-box-wrapper {
        max-width: 400px;
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('content')
    <div class="card mb-4" style="background: var(--color-bg-secondary); border: 1px solid var(--color-border); border-radius: 16px; overflow: hidden;">
        <div class="card-header" style="background: var(--color-bg-tertiary); border-bottom: 1px solid var(--color-border); padding: 1.25rem 1.5rem;">
            <h3 class="card-title text-white font-weight-bold m-0" style="font-size: 1.15rem; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-users-cog" style="color: var(--color-rugby-green-light);"></i>
                Select Teams for: <strong class="text-white">{{ $tournament->name }}</strong>
            </h3>
        </div>
        
        <form action="{{ route('admin.tournaments.updateTeams', $tournament->id) }}" method="POST" id="assign-teams-form">
            @csrf
            <div class="card-body" style="padding: 1.75rem;">
                
                <!-- Client-side Search -->
                <div class="search-box-wrapper">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="background: var(--color-bg-primary); border-color: var(--color-border); color: var(--color-text-muted);">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                        <input type="text" id="team-search" class="form-control" placeholder="Search teams..." style="background: var(--color-bg-primary); border-color: var(--color-border); color: #fff;">
                    </div>
                </div>

                <div class="team-selection-grid">
                    @foreach($teams as $team)
                        @php
                            $isChecked = $tournament->teams->contains($team->id);
                        @endphp
                        <div class="team-selection-card {{ $isChecked ? 'selected' : '' }}" data-team-id="{{ $team->id }}">
                            <div class="team-card-avatar">
                                {{ strtoupper(substr($team->name, 0, 2)) }}
                            </div>
                            <div style="min-width: 0; flex: 1; padding-right: 20px;">
                                <div style="font-weight: 700; color: #fff; font-size: 0.95rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $team->name }}
                                </div>
                                <div style="font-size: 0.78rem; color: var(--color-text-muted); margin-top: 2px;">
                                    Manager: {{ $team->manager->name ?? $team->manager_name }}
                                </div>
                            </div>
                            
                            <div class="team-checkbox-wrapper">
                                <input type="checkbox" name="teams[]" value="{{ $team->id }}" id="team_{{ $team->id }}" 
                                    style="display: none;" {{ $isChecked ? 'checked' : '' }}>
                                <div class="custom-check-indicator">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card-footer" style="background: rgba(0, 0, 0, 0.15); border-top: 1px solid var(--color-border); padding: 1.25rem 1.5rem;">
                <button type="submit" class="btn btn-success font-weight-bold shadow-glow-green px-4 py-2">
                    <i class="fas fa-save mr-1"></i> Save Assignments
                </button>
                <a href="{{ route('admin.tournaments.index') }}" class="btn btn-secondary float-right px-4 py-2">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle selection on card click
        const cards = document.querySelectorAll('.team-selection-card');
        cards.forEach(card => {
            card.addEventListener('click', function(e) {
                // Prevent toggling if clicked directly on checkbox elements (just in case)
                if (e.target.type === 'checkbox') return;
                
                const checkbox = this.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
                
                if (checkbox.checked) {
                    this.classList.add('selected');
                } else {
                    this.classList.remove('selected');
                }
            });
        });

        // Search Filter
        const searchInput = document.getElementById('team-search');
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            cards.forEach(card => {
                const teamName = card.querySelector('div[style*="font-weight: 700"]').textContent.toLowerCase();
                const managerName = card.querySelector('div[style*="font-size: 0.78rem"]').textContent.toLowerCase();
                
                if (teamName.includes(query) || managerName.includes(query)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush
