<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'amount',
        'status',
        'hr_remarks',
        'expense_date',
        'image',
        'description'
    ];

    /**
     * The attributes that should be cast.
     * This tells Laravel to treat expense_date as a Carbon instance.
     */
  protected $casts = [
    'expense_date' => 'datetime',
    'created_at'   => 'datetime', // Add this
];

    // Relationships
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function category() {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }
}
