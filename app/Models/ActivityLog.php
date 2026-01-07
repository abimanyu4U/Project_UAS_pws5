<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    // Kolom ini harus ada agar log bisa disimpan ke database
    protected $fillable = [
        'user_id', 
        'action', 
        'description'
    ];

    // Relasi ke User (opsional, untuk melihat siapa yang melakukan aksi)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}