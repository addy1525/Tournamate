<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'manager_name', // Keeping for legacy/fallback
        'manager_id',
        'phone_number',
        'address',
        'logo',
        'head_coach',
        'payment_status',
        'amount_paid',
        'receipt_path',
        'rating',
    ];

    const PAYMENT_STATUS_UNPAID = 'unpaid';
    const PAYMENT_STATUS_PARTIAL = 'partial';
    const PAYMENT_STATUS_PAID = 'paid';

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function tournaments()
    {
        return $this->belongsToMany(Tournament::class, 'tournament_team');
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }
}