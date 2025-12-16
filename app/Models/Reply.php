<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'user_id',
        'message',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // リレーション: この返信の元の問い合わせ
    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    // リレーション: この返信の送信者
    public function sender()
    {
        return $this->belongsTo(Member::class, 'user_id');
    }
}
