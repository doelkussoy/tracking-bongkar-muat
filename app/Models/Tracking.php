<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    use HasFactory;

    /**
     * Izinkan seluruh field diisi secara mass-assignment
     * (lebih fleksibel untuk Livewire).
     */
    protected $guarded = [];

    /**
     * Cast automatic untuk semua field datetime.
     */
    protected $casts = [
        'security_start' => 'datetime',
        'security_end'   => 'datetime',

        'loading_start'  => 'datetime',
        'loading_end'    => 'datetime',

        'ttb_start'      => 'datetime',
        'ttb_end'        => 'datetime',

        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',

        'distribution_at'=> 'datetime',
    ];

    /**
     * Helper opsional untuk pengecekan stage di Blade.
     */
    public function isCompleted()
    {
        return $this->current_stage === 'completed';
    }

    public function isCanceled()
    {
        return $this->current_stage === 'canceled';
    }

    public function isWaiting()
    {
        return !in_array($this->current_stage, ['completed', 'canceled']);
    }
}
