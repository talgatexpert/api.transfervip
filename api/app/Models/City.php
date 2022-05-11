<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JetBrains\PhpStorm\ArrayShape;
use Spatie\Translatable\HasTranslations;

class City extends Model
{
    use HasFactory, HasTranslations, Sluggable;

    protected $fillable = ['name'];

    protected $casts = ['translations' => 'object'];
    public array $translatable = ['translations'];

    public function scopeWhereSlug($query, $data)
    {
        $query->where('slug', $data);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
