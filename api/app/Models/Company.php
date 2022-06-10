<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Company extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    const ACTIVE = 1;
    const AGREE_TERMS = 1;
    protected $fillable = ['name', 'tax', 'user_id', 'email', 'active', 'phone', 'agree_terms'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function is_active(): bool
    {
        return $this->active === self::ACTIVE;
    }

    public function scopeActive($query)
    {
        $query->where('active', self::ACTIVE);
    }
}
