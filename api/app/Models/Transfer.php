<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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


    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function cars()
    {
        return $this->belongsToMany(Car::class, 'transfer_cars', 'transfer_id', 'car_id');
    }

    public function setCars($ids)
    {
        $this->cars()->sync($ids);
    }
}
