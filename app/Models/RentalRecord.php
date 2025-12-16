<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'price_unit',
        'rental_start_date',
        'rental_end_date',
        'rental_start_time',
        'rental_end_time',
        'land_id',
        'user_id',
    ];

    protected $casts = [
        'rental_start_date' => 'date',
        'rental_end_date' => 'date',
    ];

    // リレーション: この記録の土地
    public function land()
    {
        return $this->belongsTo(Land::class, 'land_id');
    }

    // リレーション: この記録のレンタル者
    public function renter()
    {
        return $this->belongsTo(Member::class, 'user_id');
    }

    // リレーション: この記録のレビュー
    public function review()
    {
        return $this->hasOne(ReviewComment::class, 'record_id');
    }
}
