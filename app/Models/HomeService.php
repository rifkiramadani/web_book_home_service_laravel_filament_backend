<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\ServiceBenefit;
use App\Models\TransactionDetail;
use App\Models\ServiceTestimonial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HomeService extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'about',
        'category_id',
        'is_popular',
        'price',
        'duration',
    ];

    // MUTATOR
    public function setNameAttribute($value) {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function serviceTestimonials() {
        return $this->hasMany(ServiceTestimonial::class);
    }

    public function serviceBenefits() {
        return $this->hasMany(ServiceBenefit::class);
    }

    public function transactionDetails() {
        return $this->hasMany(TransactionDetail::class);
    }
}
