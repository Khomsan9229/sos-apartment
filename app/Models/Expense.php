<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table = 'expenses';

    const UPDATED_AT = null;

    protected $fillable = [
        'title',
        'description',
        'amount',
        'expense_date',
        'category',
    ];
}
