<?php

namespace App\Models;

use App\Models\HomeService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceBenefit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'home_service_id',
    ];

    public function homeService() {
        return $this->belongsTo(HomeService::class, 'home_service_id');
    }
}
