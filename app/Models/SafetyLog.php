<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SafetyLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'temperature',
        'humidity',
        'wind_speed',
        'wbgt',
        'lightning_risk',
        'alert_level',
        'notes',
    ];

    const ALERT_SAFE = 'safe';
    const ALERT_CAUTION = 'caution';
    const ALERT_WARNING = 'warning';
    const ALERT_DANGER = 'danger';

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public static function getAlertLevel($wbgt, $lightningRisk)
    {
        // Danger: WBGT >= 32°C or lightning within 10km
        if ($wbgt >= 32 || $lightningRisk <= 10) {
            return self::ALERT_DANGER;
        }
        // Warning: WBGT >= 30°C or lightning within 15km
        elseif ($wbgt >= 30 || $lightningRisk <= 15) {
            return self::ALERT_WARNING;
        }
        // Caution: WBGT >= 28°C or lightning within 20km
        elseif ($wbgt >= 28 || $lightningRisk <= 20) {
            return self::ALERT_CAUTION;
        }
        return self::ALERT_SAFE;
    }

    /**
     * Get formatted temperature with unit
     */
    public function getFormattedTemperatureAttribute()
    {
        return $this->temperature ? number_format($this->temperature, 1) . '°C' : 'N/A';
    }

    /**
     * Get formatted humidity with unit
     */
    public function getFormattedHumidityAttribute()
    {
        return $this->humidity ? number_format($this->humidity, 0) . '%' : 'N/A';
    }

    /**
     * Get formatted wind speed with unit
     */
    public function getFormattedWindSpeedAttribute()
    {
        return $this->wind_speed ? number_format($this->wind_speed, 1) . ' km/h' : 'N/A';
    }
}
