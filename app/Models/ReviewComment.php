<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'land_review',
        'land_comment',
        'user_review',
        'user_comment',
        'date',
        'user_id',
        'land_id',
        'record_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // リレーション: このレビューを書いた会員
    public function reviewer()
    {
        return $this->belongsTo(Member::class, 'user_id');
    }

    // リレーション: このレビューの土地
    public function land()
    {
        return $this->belongsTo(Land::class, 'land_id');
    }

    // リレーション: このレビューの貸出記録
    public function rentalRecord()
    {
        return $this->belongsTo(RentalRecord::class, 'record_id');
    }
}
