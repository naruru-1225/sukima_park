<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'user_id',
        'date',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // リレーション: この問い合わせの送信者
    public function sender()
    {
        return $this->belongsTo(Member::class, 'user_id');
    }

    // リレーション: この問い合わせへの返信
    public function replies()
    {
        return $this->hasMany(Reply::class, 'contact_id');
    }
}
