<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'description', 'name', 'permissions'
    ];

    public function scopeCompany($query)
    {
        $query->where('name', 'company');
    }
    public function scopeAdmin($query)
    {
        $query->where('name', 'admin');
    }
    public function scopeSuperAdmin($query)
    {
        $query->where('name', 'super_admin');
    }
    public function scopeTravel($query)
    {
        $query->where('name', 'travel');
    }
    public function scopeAgency($query)
    {
        $query->where('name', 'travel');
    }

}
