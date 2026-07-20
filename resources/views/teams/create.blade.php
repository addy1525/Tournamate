@extends('layouts.dashboard')

@section('title', 'Register New Team')
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

    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <div class="card-header" style="background: var(--color-bg-tertiary); border-bottom: 1px solid var(--color-border); padding: 1.25rem 1.5rem;">
            <h2 class="card-title text-white font-weight-bold m-0" style="font-size: 1.15rem; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-tshirt" style="color: var(--color-rugby-green-light);"></i>
                Team & Registration Details
            </h2>
        </div>
        <div class="card-body" style="padding: 1.5rem;">
            <form action="{{ route('teams.store') }}" method="POST">
                @csrf

                @if(auth()->user() && auth()->user()->role === \App\Models\User::ROLE_ADMIN)
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                        <div class="form-group">
                            <label class="form-label required">Team Name</label>
                            <input type="text" name="name" class="form-input" placeholder="e.g. Wellington Warriors"
                                value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Assign Team Manager</label>
                            <select name="manager_id" class="form-select" required style="background: var(--color-bg-tertiary) !important; color: #fff !important;">
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
                            <label class="form-label required">Contact Phone Number</label>
                            <input type="text" name="phone_number" class="form-input" placeholder="e.g. 0123456789"
                                value="{{ old('phone_number') }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Target Tournament</label>
                            <select name="tournament_id" id="tournament_id" class="form-select" required style="background: var(--color-bg-tertiary) !important; color: #fff !important;">
                                <option value="">Select Tournament...</option>
                                @foreach($tournaments as $t)
                                    <option value="{{ $t->id }}" data-fee="{{ $t->fee ?? 250 }}" data-categories="{{ $t->categories }}" {{ old('tournament_id') == $t->id ? 'selected' : '' }}>
                                        {{ $t->name }} (Fee: RM {{ number_format($t->fee ?? 250, 2) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                        <div class="form-group">
                            <label class="form-label required">Tournament Category</label>
                            <select name="registered_category" id="registered_category" class="form-select" required style="background: var(--color-bg-tertiary) !important; color: #fff !important;">
                                <option value="">Select Category...</option>
                                <option value="Men Under 23">Men Under 23</option>
                                <option value="Open">Open</option>
                                <option value="Veteran">Veteran</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Payment Status</label>
                            <select name="payment_status" id="payment_status" class="form-select" required style="background: var(--color-bg-tertiary) !important; color: #fff !important;">
                                <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Paid (Cash / Direct Transfer)</option>
                                <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>Pending / Unpaid</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 1.25rem;">
                        <label class="form-label">Amount Paid (RM)</label>
                        <input type="number" step="0.01" name="amount_paid" id="amount_paid" class="form-input" placeholder="Enter amount paid" value="{{ old('amount_paid') }}">
                    </div>
                @else
                    <div class="form-group" style="margin-bottom: 1.25rem;">
                        <label class="form-label required">Team Name</label>
                        <input type="text" name="name" class="form-input" placeholder="Enter team name"
                            value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group" style="margin-bottom: 1.25rem;">
                        <label class="form-label required">Manager Name</label>
                        <input type="text" name="manager_name" class="form-input" placeholder="Enter manager name"
                            value="{{ old('manager_name', auth()->user()->name) }}" required>
                    </div>

                    <div class="form-group" style="margin-bottom: 1.25rem;">
                        <label class="form-label required">Phone Number</label>
                        <input type="text" name="phone_number" class="form-input" placeholder="Enter phone number"
                            value="{{ old('phone_number', auth()->user()->phone_number ?? '') }}" required>
                    </div>
                @endif

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Club / Team Address</label>
                    <textarea name="address" class="form-input" rows="3"
                        placeholder="Enter team address (optional)">{{ old('address') }}</textarea>
                </div>

                <div style="display: flex; gap: var(--spacing-md); justify-content: flex-end; border-top: 1px solid var(--color-border); padding-top: 1.25rem;">
                    <a href="{{ route('teams.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary font-weight-bold" style="background: linear-gradient(135deg, var(--color-rugby-green), var(--color-rugby-green-dark));">
                        <i class="fas fa-check-circle mr-1"></i> Register Team
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tournamentSelect = document.getElementById('tournament_id');
            const categorySelect = document.getElementById('registered_category');
            const paymentSelect = document.getElementById('payment_status');
            const amountInput = document.getElementById('amount_paid');

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