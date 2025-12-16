<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Member extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'tel',
        'birth',
        'show_birth',
        'gender',
        'show_gender',
        'identity',
        'username',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'birth' => 'date',
        'show_birth' => 'boolean',
        'show_gender' => 'boolean',
    ];

    // リレーション: この会員が所有する土地
    public function lands()
    {
        return $this->hasMany(Land::class, 'user_id');
    }

    // リレーション: この会員の貸出記録
    public function rentalRecords()
    {
        return $this->hasMany(RentalRecord::class, 'user_id');
    }

    // リレーション: この会員の問い合わせ
    public function contacts()
    {
        return $this->hasMany(Contact::class, 'user_id');
    }

    // リレーション: この会員が送信したチャット
    public function sentChats()
    {
        return $this->hasMany(Chat::class, 'user_id_from');
    }

    // リレーション: この会員が受信したチャット
    public function receivedChats()
    {
        return $this->hasMany(Chat::class, 'user_id_to');
    }
}
