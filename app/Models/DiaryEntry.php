<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiaryEntry extends Model
{
    use HasFactory;

    protected $fillable = ['diary_profile_id', 'title', 'price', 'is_cleared', 'image'];

    public function profile()
    {
        return $this->belongsTo(DiaryProfile::class, 'diary_profile_id');
    }
}
