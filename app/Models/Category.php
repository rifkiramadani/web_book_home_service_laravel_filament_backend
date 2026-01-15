<?php

namespace App\Models;

use App\Models\HomeService;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'photo',
        'photo_white'
    ];

    // MUTATOR
    public function setNameAttribute($value) {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function homeServices() {
        return $this->hasMany(HomeService::class);
    }

    //untuk relasi data home service yang dimiliki oleh category (POPULAR)
    public function popularHomeServices() {
        return $this->hasMany(HomeService::class)
            ->where('is_popular', true)
            ->orderBy('created_at', 'DESC');
    }
}
