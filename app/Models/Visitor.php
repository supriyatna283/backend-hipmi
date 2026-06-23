<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'country',
        'city',
        'browser',
        'platform',
        'device',
        'referer',
        'url',
        'session_id',
        'visited_at',
    ];
    protected $cast = [
    'visited_at' => 'datetime',
];
    public function scopeToday($query)
    {
        return $query->whereDate('visited_at', Carbon::today());
    }

    
}

