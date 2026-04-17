<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;

#[Fillable(['name', 'name_ur', 'role', 'pin'])]
#[Hidden(['pin', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'pin' => 'hashed', // Ensures the PIN is automatically hashed/checked correctly
        ];
    }

    /**
     * Since you are using 'pin' instead of 'password' for Laravel's Auth,
     * this method tells Laravel where to look for the credential.
     */
    public function getAuthPassword()
    {
        return $this->pin;
    }
    public function expenses() {
    return $this->hasMany(Expense::class);
}

public function transactions() {
    return $this->hasMany(WalletTransaction::class);
}
public function getDisplayNameAttribute()
{
    return app()->getLocale() === 'ur'
        ? ($this->name_ur ?? $this->name)
        : $this->name;
}
// app/Models/User.php

public function hasRole($role)
{
    // Adjust 'role' to match your actual database column name
    return $this->role === $role;
}
}


