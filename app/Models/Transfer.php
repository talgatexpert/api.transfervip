<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transfer extends Model
{
    use HasFactory, SoftDeletes;


    protected $dates = [
        'created_at', 'updated_at', 'started_at', 'ended_at'
    ];

    protected $casts = [
        'created_at' => 'date:d.m.Y',
        'updated_at' => 'date:d.m.Y',
        'started_at' => 'date:d.m.Y',
        'ended_at' => 'date:d.m.Y',
    ];

    public function startCity()
    {
        return $this->belongsTo(City::class, 'start_city_id');
    }
    public function endCity()
    {
        return $this->belongsTo(City::class, 'finish_city_id');
    }

    public function cars()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}
