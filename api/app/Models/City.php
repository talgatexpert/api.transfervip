<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JetBrains\PhpStorm\ArrayShape;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
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

    public static function getByLanguage(array $translations, string $language = 'turkish')
    {
        $code = request('language');

        if ($code) {
            switch ($code) {
                case "russian":
                    $language = 'russian';
                    break;
                case "english":
                    $language = 'english';
                    break;
                case "turkish":
                    $language = 'turkish';
                    break;
                default:
                    $language = "turkish";
                    break;
            }
        }
        return $translations[$language] ?? null;
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
