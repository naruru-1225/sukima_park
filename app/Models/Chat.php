<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id_from',
        'user_id_to',
        'message',
        'image',
        'sent_date',
        'sent_time',
    ];

    protected $casts = [
        'sent_date' => 'date',
    ];

    // リレーション: このメッセージの送信者
    public function sender()
    {
        return $this->belongsTo(Member::class, 'user_id_from');
    }

    // リレーション: このメッセージの受信者
    public function receiver()
    {
        return $this->belongsTo(Member::class, 'user_id_to');
    }
}
