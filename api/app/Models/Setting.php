<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use function Symfony\Component\Translation\t;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key', 'value',
    ];


    public function getValueAttribute($value)
    {
        if ((int)$this->serialized) {
            return json_decode($value, true);
        }
        return $value;
    }


    public function scopeMeta($q)
    {
        return $q->where('key', 'meta');
    }
    public function scopeSiteInfo($q)
    {
        return $q->where('key', 'site_inf');
    }
    public function scopeLogo($q)
    {
        return $q->where('key', 'logo');
    }

    public function scopeName($q)
    {
        return $q->where('key', 'name');
    }


}
