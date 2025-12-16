<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Land extends Model
{
    use HasFactory;

    protected $table = 'LAND_TABLE';
    protected $primaryKey = 'LAND_ID';
    public $timestamps = false;

    protected $fillable = [
        'PEREFECTURES',
        'CITY',
        'STREET_ADDRESS',
        'AREA',
        'IMAGE',
        'TITLE_DEED',
        'DESCRIPTION',
        'RENTAL_START_DATE',
        'RENTAL_END_DATE',
        'RENTAL_START_TIME',
        'RENTAL_END_TIME',
        'PRICE',
        'PRICE_UNIT',
        'USER_ID',
        'STATUS',
    ];

    protected $casts = [
        'AREA' => 'decimal:2',
        'RENTAL_START_DATE' => 'date',
        'RENTAL_END_DATE' => 'date',
        'STATUS' => 'boolean',
    ];

    // この土地の所有者
    public function owner()
    {
        return $this->belongsTo(Member::class, 'USER_ID', 'USER_ID');
    }

    // この土地の貸出記録
    public function rentalRecords()
    {
        return $this->hasMany(RentalRecord::class, 'LAND_ID', 'LAND_ID');
    }

    // この土地のレビュー
    public function reviews()
    {
        return $this->hasMany(ReviewComment::class, 'LAND_ID', 'LAND_ID');
    }
}
