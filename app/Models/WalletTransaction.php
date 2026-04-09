<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $fillable = ['user_id', 'amount', 'type', 'remarks', 'created_by','status'];

public function manager() {
    return $this->belongsTo(User::class, 'user_id');
}

public function creator() {
    return $this->belongsTo(User::class, 'created_by');
}
public function user()
    {
        return $this->belongsTo(User::class);
    }
}
