@extends('layouts.dashboard')

@section('title', 'Create New Team')
@section('page-title', 'Register New Team')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Register New Team</h1>
        <p class="page-subtitle">Add and enroll a new team into a tournament</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="alert-icon"><i class="fas fa-exclamation-circle"></i></div>
            <div class="alert-content">
                <div class="alert-title">There were some errors</div>
                <ul style="margin: 0; padding-left: var(--spacing-lg);">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="card" style="max-width: 800px; margin: 0 auto; background: var(--color-bg-secondary); border: 1px solid var(--color-border); border-radius: 16px; overflow: hidden;">
        <div class="card-header" style="background: var(--color-bg-tertiary); border-bottom: 1px solid var(--color-border); padding: 1.25rem 1.5rem;">
            <h3 class="card-title text-white font-weight-bold m-0" style="font-size: 1.15rem; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-tshirt" style="color: var(--color-rugby-green-light);"></i>
                Team & Registration Details
            </h3>
        </div>
        <div class="card-body" style="padding: 1.5rem;">
            <form action="{{ route('manage-teams.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                    <div class="form-group">
                        <label class="form-label required" style="color: #fff; font-weight: 600;">Team Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Wellington Warriors"
                            value="{{ old('name') }}" required style="background: var(--color-bg-tertiary) !important; color: #fff !important; border: 1px solid var(--color-border);">
                    </div>

                    <div class="form-group">
                        <label class="form-label required" style="color: #fff; font-weight: 600;">Assign Team Manager</label>
                        <select name="manager_id" class="form-control" required style="background: var(--color-bg-tertiary) !important; color: #fff !important; border: 1px solid var(--color-border);">
                            <option value="">Select Manager...</option>
                            @foreach($managers as $manager)
                                <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                                    {{ $manager->name }} ({{ $manager->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                    <div class="form-group">
                        <label class="form-label required" style="color: #fff; font-weight: 600;">Contact Phone Number</label>
                        <input type="text" name="phone_number" class="form-control" placeholder="e.g. 0123456789"
                            value="{{ old('phone_number') }}" required style="background: var(--color-bg-tertiary) !important; color: #fff !important; border: 1px solid var(--color-border);">
                    </div>

                    <div class="form-group">
                        <label class="form-label required" style="color: #fff; font-weight: 600;">Target Tournament</label>
                        <select name="tournament_id" id="tournament_id_admin" class="form-control" required style="background: var(--color-bg-tertiary) !important; color: #fff !important; border: 1px solid var(--color-border);">
                            <option value="">Select Tournament...</option>
                            @if(isset($tournaments))
                                @foreach($tournaments as $t)
                                    <option value="{{ $t->id }}" data-fee="{{ $t->fee ?? 250 }}" data-categories="{{ $t->categories }}" {{ old('tournament_id') == $t->id ? 'selected' : '' }}>
                                        {{ $t->name }} (Fee: RM {{ number_format($t->fee ?? 250, 2) }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                    <div class="form-group">
                        <label class="form-label required" style="color: #fff; font-weight: 600;">Tournament Category</label>
                        <select name="registered_category" id="registered_category_admin" class="form-control" required style="background: var(--color-bg-tertiary) !important; color: #fff !important; border: 1px solid var(--color-border);">
                            <option value="">Select Category...</option>
                            <option value="Men Under 23">Men Under 23</option>
                            <option value="Open">Open</option>
                            <option value="Veteran">Veteran</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label required" style="color: #fff; font-weight: 600;">Payment Status</label>
                        <select name="payment_status" id="payment_status_admin" class="form-control" required style="background: var(--color-bg-tertiary) !important; color: #fff !important; border: 1px solid var(--color-border);">
                            <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Paid (Cash / Direct Transfer)</option>
                            <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>Pending / Unpaid</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                    <div class="form-group">
                        <label class="form-label" style="color: #fff; font-weight: 600;">Amount Paid (RM)</label>
                        <input type="number" step="0.01" name="amount_paid" id="amount_paid_admin" class="form-control" placeholder="Enter amount paid" value="{{ old('amount_paid') }}" style="background: var(--color-bg-tertiary) !important; color: #fff !important; border: 1px solid var(--color-border);">
                    </div>

                    <div class="form-group">
                        <label class="form-label" style="color: #fff; font-weight: 600;">Team Logo (Optional)</label>
                        <input type="file" name="logo" class="form-control-file" accept="image/*" style="color: #fff; background: var(--color-bg-tertiary); padding: 8px; border-radius: 8px; border: 1px solid var(--color-border); width: 100%;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label" style="color: #fff; font-weight: 600;">Club / Team Address</label>
                    <textarea name="address" class="form-control" rows="3"
                        placeholder="Enter team address (optional)" style="background: var(--color-bg-tertiary) !important; color: #fff !important; border: 1px solid var(--color-border);">{{ old('address') }}</textarea>
                </div>

                <div style="display: flex; gap: var(--spacing-md); justify-content: flex-end; border-top: 1px solid var(--color-border); padding-top: 1.25rem;">
                    <a href="{{ route('manage-teams.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-success font-weight-bold" style="box-shadow: 0 4px 12px rgba(0, 168, 107, 0.2);">
                        <i class="fas fa-check-circle mr-1"></i> Register Team
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tournamentSelect = document.getElementById('tournament_id_admin');
            const categorySelect = document.getElementById('registered_category_admin');
            const amountInput = document.getElementById('amount_paid_admin');

            if (tournamentSelect && categorySelect) {
                tournamentSelect.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];
                    const categoriesStr = selectedOption.getAttribute('data-categories');
                    const fee = selectedOption.getAttribute('data-fee');

                    if (amountInput && fee) {
                        amountInput.value = fee;
                    }

                    categorySelect.innerHTML = '<option value="">Select Category...</option>';
                    if (categoriesStr && categoriesStr.trim() !== '') {
                        const cats = categoriesStr.split(',').map(c => c.trim()).filter(c => c.length > 0);
                        cats.forEach(cat => {
                            const opt = document.createElement('option');
                            opt.value = cat;
                            opt.textContent = cat;
                            categorySelect.appendChild(opt);
                        });
                    } else {
                        ['Men Under 23', 'Open', 'Veteran'].forEach(cat => {
                            const opt = document.createElement('option');
                            opt.value = cat;
                            opt.textContent = cat;
                            categorySelect.appendChild(opt);
                        });
                    }
                });
            }
        });
    </script>
@endsection