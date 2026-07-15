<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'in:manager,spectator'],
            'invitation_code' => [
                'required_if:role,manager',
                'nullable',
                'string',
                function ($attribute, $value, $fail) use ($data) {
                    if (($data['role'] ?? '') === 'manager' && $value !== 'RUGBY2026') {
                        $fail('The invitation code is invalid.');
                    }
                }
            ],
            'team_name' => ['required_if:role,manager', 'nullable', 'string', 'max:255'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Determine status based on role
        $status = $data['role'] === User::ROLE_MANAGER 
            ? User::STATUS_PENDING 
            : User::STATUS_ACTIVE;

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone_number' => $data['phone_number'] ?? null,
            'address' => $data['address'] ?? null,
            'role' => $data['role'],
            'status' => $status,
        ]);

        if ($user->role === User::ROLE_MANAGER && !empty($data['team_name'])) {
            \App\Models\Team::create([
                'name' => $data['team_name'],
                'manager_id' => $user->id,
                'manager_name' => $user->name,
                'phone_number' => $user->phone_number,
                'address' => $user->address,
                'payment_status' => \App\Models\Team::PAYMENT_STATUS_UNPAID,
            ]);
        }

        return $user;
    }

    /**
     * Get the redirect path after registration.
     *
     * @return string
     */
    protected function redirectTo()
    {
        $user = auth()->user();

        // If manager with pending status, redirect to pending approval page
        if ($user->isManager() && $user->isPending()) {
            return route('pending.approval');
        }

        // Redirect based on role
        if ($user->isManager()) {
            return route('manager.dashboard');
        } elseif ($user->isSpectator()) {
            return route('spectator.dashboard');
        }

        return '/home';
    }
}
