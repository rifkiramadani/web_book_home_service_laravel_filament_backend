<?php

namespace App\Models;

use App\Models\TransactionDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'booking_trx_id',
        'proof',
        'address',
        'city',
        'post_code',
        'started_time',
        'schedule_at',
        'sub_total',
        'total_amount',
        'total_tax_amount',
        'is_paid',
    ];

    //GENERATE UNIQUE booking_trx_id
    public static function generateUniqueTrxId() {
        $prefix = 'HOME-SERVICE';
        do {
            $randomString = $prefix . mt_rand(1000, 9999);
        } while(self::where('booking_trx_id', $randomString)->exists());

        return $randomString;
    }

    public function transactionDetails() {
        return $this->hasMany(TransactionDetail::class);
    }
}
