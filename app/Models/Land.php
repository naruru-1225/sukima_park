<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Land extends Model
{
    use HasFactory;

    protected $fillable = [
        'prefectures',
        'city',
        'street_address',
        'area',
        'user_id',
    ];

    protected $casts = [
        'area' => 'decimal:2',
    ];

    // リレーション: この土地の所有者
    public function owner()
    {
        return $this->belongsTo(Member::class, 'user_id');
    }

    // リレーション: この土地の貸出記録
    public function rentalRecords()
    {
        return $this->hasMany(RentalRecord::class, 'land_id');
    }

    // リレーション: この土地のレビュー
    public function reviews()
    {
        return $this->hasMany(ReviewComment::class, 'land_id');
    }
}
