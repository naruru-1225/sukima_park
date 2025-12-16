<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    use HasFactory;

    protected $table = 'MEMBER_TABLE';
    protected $primaryKey = 'USER_ID';
    public $timestamps = false;

    protected $fillable = [
        'EMAIL',
        'PASSWORD',
        'TEL',
        'BIRTH',
        'SHOW_BIRTH',
        'GENDER',
        'SHOW_GENDER',
        'IDENTITY',
        'USERNAME',
    ];

    protected $hidden = [
        'PASSWORD',
    ];

    protected $casts = [
        'BIRTH' => 'date',
        'SHOW_BIRTH' => 'boolean',
        'SHOW_GENDER' => 'boolean',
    ];

    // この会員が所有する土地
    public function lands()
    {
        return $this->hasMany(Land::class, 'USER_ID', 'USER_ID');
    }

    // この会員の貸出記録
    public function rentalRecords()
    {
        return $this->hasMany(RentalRecord::class, 'USER_ID', 'USER_ID');
    }

    // この会員の問い合わせ
    public function contacts()
    {
        return $this->hasMany(Contact::class, 'USER_ID', 'USER_ID');
    }

    // この会員が送信したチャット
    public function sentChats()
    {
        return $this->hasMany(Chat::class, 'USER_ID_FROM', 'USER_ID');
    }

    // この会員が受信したチャット
    public function receivedChats()
    {
        return $this->hasMany(Chat::class, 'USER_ID_TO', 'USER_ID');
    }
}
