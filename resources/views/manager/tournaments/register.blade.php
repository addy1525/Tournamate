@extends('layouts.dashboard')

@section('title', 'Register for Tournament')
@section('page-title', 'Tournament Registration')

@push('styles')
    <style>
        /* Modal Overlay */
        .registration-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(8, 10, 15, 0.85);
            backdrop-filter: blur(12px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: var(--spacing-xl);
        }

        /* Modal Container */
        .registration-modal {
            background: rgba(30, 41, 59, 0.45);
            backdrop-filter: blur(25px);
            border-radius: 20px;
            max-width: 850px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
        }

        /* Custom Scrollbar for Modal */
        .registration-modal::-webkit-scrollbar {
            width: 8px;
        }
        .registration-modal::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.2);
            border-radius: 0 20px 20px 0;
        }
        .registration-modal::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.12);
            border-radius: 4px;
        }
        .registration-modal::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        /* Progress Stepper */
        .progress-stepper {
            display: flex;
            justify-content: space-around;
            padding: 1.5rem var(--spacing-xl);
            background: rgba(15, 23, 42, 0.5);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .step {
            display: flex;
            align-items: center;
            opacity: 0.55;
            transition: all 0.3s ease;
        }

        .step.active, .step.completed {
            opacity: 1;
        }

        .step-indicator {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(15, 23, 42, 0.4);
            border: 2px solid rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-right: var(--spacing-md);
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .step.active .step-indicator {
            background: var(--color-electric-blue);
            border-color: var(--color-electric-blue);
            box-shadow: 0 0 15px rgba(0, 132, 255, 0.45);
            color: white;
        }

        .step.completed .step-indicator {
            background: var(--color-rugby-green);
            border-color: var(--color-rugby-green);
            color: white;
            box-shadow: 0 0 15px rgba(0, 168, 107, 0.45);
        }

        .step-info {
            display: flex;
            flex-direction: column;
        }

        .step-title {
            font-weight: 650;
            font-size: var(--font-size-sm);
            color: var(--color-text-secondary);
        }

        .step.active .step-title {
            color: var(--color-text-primary);
        }

        .step-description {
            font-size: var(--font-size-xs);
            color: var(--color-text-tertiary);
            margin-top: 1px;
        }

        /* Modal Body */
        .modal-body {
            padding: var(--spacing-xl) 2.5rem;
            flex: 1;
        }

        .phase {
            display: none;
            animation: fadeIn 0.4s ease forwards;
        }

        .phase.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Shortcut Select Card */
        .shortcut-card {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.6), rgba(15, 23, 42, 0.4));
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        /* Form Grid - 2 Column Layout */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        @media (max-width: 768px) {
            .form-group.full-width {
                grid-column: span 1;
            }
        }

        .form-group label {
            display: block;
            margin-bottom: var(--spacing-xs);
            font-size: 0.85rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.75);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            background: rgba(15, 23, 42, 0.4) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-radius: 10px;
            color: var(--color-text-primary);
            font-size: var(--font-size-base);
            transition: all 0.3s ease;
            height: auto !important;
            line-height: 1.5 !important;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--color-electric-blue) !important;
            box-shadow: 0 0 12px rgba(0, 132, 255, 0.25);
            background: rgba(15, 23, 42, 0.55) !important;
        }

        select.form-control {
            background-color: #0f172a !important;
            color: #ffffff !important;
            appearance: menulist !important;
            height: auto !important;
            line-height: 1.5 !important;
        }

        /* Custom File Upload Dropzone */
        .logo-upload-dropzone {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 2px dashed rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 1.5rem;
            background: rgba(15, 23, 42, 0.25);
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            margin-top: 4px;
        }

        .logo-upload-dropzone:hover {
            border-color: var(--color-electric-blue);
            background: rgba(0, 132, 255, 0.05);
        }

        .logo-upload-dropzone i {
            font-size: 2rem;
            color: rgba(255, 255, 255, 0.35);
            margin-bottom: var(--spacing-sm);
            transition: color 0.3s ease;
        }

        .logo-upload-dropzone:hover i {
            color: var(--color-electric-blue);
        }

        .upload-title {
            font-weight: 600;
            color: var(--color-text-primary);
            font-size: var(--font-size-sm);
        }

        .upload-subtitle {
            font-size: var(--font-size-xs);
            color: var(--color-text-tertiary);
            margin-top: 2px;
        }

        .logo-preview-container {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            margin-top: 1rem;
        }

        .logo-preview {
            display: none;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--color-rugby-green);
            box-shadow: 0 0 12px rgba(0, 168, 107, 0.3);
        }

        .logo-preview.active {
            display: block;
        }

        .btn-remove-logo {
            background: rgba(239, 68, 68, 0.08);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
            padding: 5px 12px;
            border-radius: 6px;
            font-size: var(--font-size-xs);
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
        }

        .btn-remove-logo:hover {
            background: rgba(239, 68, 68, 0.18);
            border-color: #ef4444;
        }

        /* Summary Card */
        .summary-card {
            background: linear-gradient(135deg, rgba(0, 168, 107, 0.08), rgba(0, 132, 255, 0.03));
            border: 1px solid rgba(0, 168, 107, 0.25);
            border-radius: 14px;
            padding: var(--spacing-xl);
            margin-bottom: var(--spacing-xl);
            box-shadow: 0 4px 25px rgba(0, 168, 107, 0.05);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: var(--spacing-md) 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            font-size: var(--font-size-base);
        }

        .summary-row:last-child {
            border-bottom: none;
            font-size: var(--font-size-xl);
            font-weight: 700;
            color: var(--color-rugby-green-light);
            padding-top: 1rem;
        }

        /* Modal Footer */
        .modal-footer {
            padding: 1.25rem 2.5rem;
            background: rgba(15, 23, 42, 0.5);
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            justify-content: space-between;
            position: sticky;
            bottom: 0;
            z-index: 10;
        }
    </style>
@endpush

@section('content')
    <div class="registration-modal-overlay">
        <div class="registration-modal">
            <!-- Progress Stepper -->
            <div class="progress-stepper">
                <div class="step active" data-step="1">
                    <div class="step-indicator">
                        <i class="fas fa-check" style="display:none;"></i>
                        <span class="step-number">1</span>
                    </div>
                    <div class="step-info">
                        <div class="step-title">Team Identity</div>
                        <div class="step-description">Basic details</div>
                    </div>
                </div>
                <div class="step" data-step="2">
                    <div class="step-indicator">
                        <i class="fas fa-check" style="display:none;"></i>
                        <span class="step-number">2</span>
                    </div>
                    <div class="step-info">
                        <div class="step-title">Payment</div>
                        <div class="step-description">Secure checkout</div>
                    </div>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form id="registration-form">
                    @csrf

                    <!-- Phase 1: Team Identity -->
                    <div class="phase active" data-phase="1">
                        <h2 style="margin-bottom: 0.5rem; color: var(--color-text-primary); font-weight: 700; font-size: 1.4rem;">Register Your Squad</h2>
                        <p style="color: rgba(255,255,255,0.45); font-size: 0.85rem; margin-bottom: 1.25rem;">
                            Registering for <strong style="color: rgba(255,255,255,0.75);">{{ $tournament->name }}</strong>
                            @if($tournament->registration_deadline)
                                · Deadline: <strong style="color: #fb923c;">{{ $tournament->registration_deadline->format('d M Y, H:i') }}</strong>
                            @endif
                        </p>

                        {{-- Team info banner (one-team rule) --}}
                        @if($myTeam)
                            <div style="display: flex; align-items: center; gap: 12px; background: rgba(0,168,107,0.08); border: 1px solid rgba(0,168,107,0.22); border-radius: 12px; padding: 0.85rem 1rem; margin-bottom: 1.25rem;">
                                <i class="fas fa-shield-alt" style="color: #34d399; font-size: 1.2rem;"></i>
                                <div style="flex: 1;">
                                    <div style="font-weight: 700; color: #f1f5f9; font-size: 0.9rem;">{{ $myTeam->name }}</div>
                                    <div style="font-size: 0.75rem; color: rgba(255,255,255,0.45); margin-top: 2px;">Your existing team · details auto-filled below</div>
                                </div>
                                <span style="font-size: 0.72rem; color: #34d399; background: rgba(0,168,107,0.15); border-radius: 20px; padding: 3px 10px; font-weight: 600;">Active</span>
                            </div>
                        @endif

                        @php
                            if (!empty($tournament->categories)) {
                                $categoryList = array_map('trim', explode(',', $tournament->categories));
                            } else {
                                $categoryList = ['Open', 'Veteran'];
                            }
                        @endphp

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="team_name">Team Name *</label>
                                <input type="text" id="team_name" name="team_name" class="form-control"
                                    value="{{ $myTeam->name ?? '' }}" required placeholder="e.g., Wellington Warriors"
                                    {{ $myTeam ? 'readonly' : '' }}>
                                @if($myTeam)
                                    <small style="color: rgba(255,255,255,0.35); font-size: 0.72rem; margin-top: 4px; display:block;">
                                        <i class="fas fa-lock" style="margin-right: 3px;"></i> Team name locked (one team per manager)
                                    </small>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="registered_category">Tournament Category *</label>
                                <select class="form-control" id="registered_category" name="registered_category" required style="background-color: #0f172a !important; color: #ffffff !important; height: auto !important; line-height: 1.5 !important;">
                                    <option value="">Select Category...</option>
                                    @foreach($categoryList as $cat)
                                        <option value="{{ $cat }}">{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="head_coach">Head Coach *</label>
                                <input type="text" id="head_coach" name="head_coach" class="form-control"
                                    value="{{ $myTeam->manager_name ?? Auth::user()->name }}" required
                                    placeholder="Coach Name">
                            </div>

                            <div class="form-group">
                                <label for="phone_number">Phone Number *</label>
                                <input type="tel" id="phone_number" name="phone_number" class="form-control"
                                    value="{{ $myTeam->phone_number ?? Auth::user()->phone ?? '' }}" required
                                    placeholder="e.g., 0123456789">
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 1rem;">
                            <label>Team Logo (Optional)</label>
                            <label for="team_logo" class="logo-upload-dropzone">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span class="upload-title">Click to upload logo</span>
                                <span class="upload-subtitle">PNG, JPG or GIF up to 2MB</span>
                                <input type="file" id="team_logo" name="team_logo" class="form-control" accept="image/*" style="display: none;">
                            </label>
                            <div class="logo-preview-container">
                                <img id="logo-preview" class="logo-preview" alt="Logo Preview">
                                <button type="button" id="btn-remove-logo" class="btn-remove-logo" style="display: none;">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>

                        </div>
                    </div>

                    <!-- Phase 2: Payment -->
                    <div class="phase" data-phase="2">
                        <h2 style="margin-bottom: var(--spacing-lg); color: var(--color-text-primary); font-weight: 700; font-size: 1.4rem;">Confirm & Pay</h2>

                        <div class="summary-card">
                            <h3 style="margin-bottom: var(--spacing-md); color: var(--color-text-primary); font-size: 1.1rem; font-weight: 600;">Registration Summary</h3>
                            <div class="summary-row">
                                <span class="text-tertiary">Tournament</span>
                                <span id="summary-tournament" class="font-weight-bold text-white">{{ $tournament->name }}</span>
                            </div>
                            <div class="summary-row">
                                <span class="text-tertiary">Registered Team</span>
                                <span id="summary-team" class="font-weight-bold text-white"></span>
                            </div>
                            <div class="summary-row">
                                <span class="text-tertiary">Tournament Fee</span>
                                <span class="font-weight-bold text-white">RM {{ number_format($tournament->fee ?? 250, 2) }}</span>
                            </div>
                        </div>

                        <div class="form-group text-center" style="background: rgba(255, 255, 255, 0.02); border: 1px dashed rgba(255,255,255,0.1); border-radius: 14px; padding: 2.5rem; margin-top: var(--spacing-lg);">
                            <i class="fas fa-credit-card text-success mb-3" style="font-size: 2.5rem; color: var(--color-rugby-green-light) !important;"></i>
                            <h4 class="text-white font-weight-bold mb-2">Proceed to Stripe Payment Gateway</h4>
                            <p class="text-tertiary mb-0" style="font-size: 0.9rem; max-width: 480px; margin: 0 auto; line-height: 1.5;">
                                You will be redirected to Stripe's secure checkout page to complete your yuran payment. FPX Online Banking, and major Credit/Debit Cards are accepted.
                            </p>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <div>
                    <a href="{{ route('manager.browse-tournaments') }}" class="btn btn-danger" id="btn-cancel" style="margin-right: var(--spacing-sm);">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="button" class="btn btn-secondary" id="btn-previous" style="display:none;">
                        <i class="fas fa-arrow-left"></i> Previous
                    </button>
                </div>
                <div>
                    <button type="button" class="btn btn-primary" id="btn-next">
                        Next <i class="fas fa-arrow-right"></i>
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-complete" style="display:none;">
                        <i class="fas fa-lock"></i> Complete Payment
                        <span class="loading-spinner"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Configuration
        const tournamentId = {{ $tournament->id }};
        const tournamentFee = {{ $tournament->fee ?? 250 }};

        let currentPhase = 1;
        let registrationId = null;

        document.addEventListener('DOMContentLoaded', function () {
            // Logo preview and remove
            const logoInput = document.getElementById('team_logo');
            const logoPreview = document.getElementById('logo-preview');
            const removeLogoBtn = document.getElementById('btn-remove-logo');

            logoInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        logoPreview.src = e.target.result;
                        logoPreview.classList.add('active');
                        if (removeLogoBtn) {
                            removeLogoBtn.style.display = 'inline-flex';
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });

            if (removeLogoBtn) {
                removeLogoBtn.addEventListener('click', function() {
                    logoInput.value = '';
                    logoPreview.src = '';
                    logoPreview.classList.remove('active');
                    removeLogoBtn.style.display = 'none';
                });
            }

            // Button handlers
            document.getElementById('btn-next').addEventListener('click', handleNext);
            document.getElementById('btn-previous').addEventListener('click', handlePrevious);
            document.getElementById('btn-complete').addEventListener('click', handlePayment);

            // Force select dropdown text to be visible
            document.addEventListener('change', function (e) {
                if (e.target.tagName === 'SELECT' && e.target.name && e.target.name.includes('position')) {
                    e.target.style.color = '#ffffff';
                    e.target.style.setProperty('color', '#ffffff', 'important');
                    e.target.style.webkitTextFillColor = '#ffffff';
                }
            });

            // Handle existing team selection
            const existingTeamSelect = document.getElementById('existing_team_select');
            if (existingTeamSelect) {
                existingTeamSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption.value) {
                        document.getElementById('team_name').value = selectedOption.getAttribute('data-name');
                        document.getElementById('head_coach').value = selectedOption.getAttribute('data-coach');
                        document.getElementById('phone_number').value = selectedOption.getAttribute('data-phone');
                    } else {
                        document.getElementById('team_name').value = '';
                    }
                });
            }
        });

        function handleNext() {
            if (currentPhase === 1) {
                // Validate Phase 1
                const teamName = document.getElementById('team_name').value.trim();
                const headCoach = document.getElementById('head_coach').value.trim();
                const phoneNumber = document.getElementById('phone_number').value.trim();
                const categoryEl = document.getElementById('registered_category');

                if (!teamName || !headCoach || !phoneNumber) {
                    alert('Please fill in all required fields');
                    return;
                }
                
                if (categoryEl && categoryEl.type === 'select-one' && !categoryEl.value) {
                    alert('Please select a tournament category');
                    return;
                }

                // Submit team data and go to payment
                submitRegistrationData();
            }
        }

        function handlePrevious() {
            if (currentPhase > 1) {
                goToPhase(currentPhase - 1);
            }
        }

        function goToPhase(phaseNumber) {
            // Update current phase
            currentPhase = phaseNumber;

            // Hide all phases
            document.querySelectorAll('.phase').forEach(p => p.classList.remove('active'));

            // Show target phase
            document.querySelector(`.phase[data-phase="${phaseNumber}"]`).classList.add('active');

            // Update stepper
            document.querySelectorAll('.step').forEach((step, index) => {
                const stepNum = index + 1;
                const indicator = step.querySelector('.step-indicator');
                const checkIcon = step.querySelector('.fa-check');
                const numberSpan = step.querySelector('.step-number');

                if (stepNum < phaseNumber) {
                    step.classList.add('completed');
                    step.classList.remove('active');
                    checkIcon.style.display = 'inline';
                    numberSpan.style.display = 'none';
                } else if (stepNum === phaseNumber) {
                    step.classList.add('active');
                    step.classList.remove('completed');
                    checkIcon.style.display = 'none';
                    numberSpan.style.display = 'inline';
                } else {
                    step.classList.remove('active', 'completed');
                    checkIcon.style.display = 'none';
                    numberSpan.style.display = 'inline';
                }
            });

            // Update buttons
            document.getElementById('btn-previous').style.display = phaseNumber > 1 ? 'block' : 'none';
            document.getElementById('btn-next').style.display = phaseNumber < 2 ? 'block' : 'none';
            document.getElementById('btn-complete').style.display = phaseNumber === 2 ? 'block' : 'none';

            // Update summary when reaching phase 2
            if (phaseNumber === 2) {
                updateSummary();
            }
        }

        function submitRegistrationData() {
            const formData = new FormData();
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            formData.append('team_name', document.getElementById('team_name').value);
            formData.append('head_coach', document.getElementById('head_coach').value);
            formData.append('phone_number', document.getElementById('phone_number').value);
            if (document.getElementById('registered_category')) {
                formData.append('registered_category', document.getElementById('registered_category').value);
            }

            const logoFile = document.getElementById('team_logo').files[0];
            if (logoFile) {
                formData.append('team_logo', logoFile);
            }

            // Show loading
            document.getElementById('btn-next').disabled = true;

            fetch(`/manager/tournaments/${tournamentId}/register`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        registrationId = data.registration_id;
                        goToPhase(2);
                    } else {
                        alert('Error: ' + (data.message || 'Failed to submit registration'));
                    }
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                })
                .finally(() => {
                    document.getElementById('btn-next').disabled = false;
                });
        }

        function updateSummary() {
            document.getElementById('summary-team').textContent = document.getElementById('team_name').value;
        }

        async function handlePayment() {
            if (!registrationId) {
                alert('Registration ID not found. Please try again.');
                return;
            }

            const completeBtn = document.getElementById('btn-complete');
            const spinner = completeBtn.querySelector('.loading-spinner');
            completeBtn.disabled = true;
            spinner.style.display = 'inline-block';

            try {
                // Create Stripe Checkout Session
                const sessionResponse = await fetch(`/manager/tournaments/${tournamentId}/checkout-session`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ registration_id: registrationId })
                });

                const sessionData = await sessionResponse.json();

                if (!sessionData.success) {
                    throw new Error(sessionData.message || 'Failed to create checkout session');
                }

                // Redirect to Stripe Checkout hosted page
                window.location.href = sessionData.url;

            } catch (error) {
                alert('Error: ' + error.message);
                completeBtn.disabled = false;
                spinner.style.display = 'none';
            }
        }
    </script>
@endpush