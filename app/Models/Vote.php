<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'amount',
        'currency',
        'description',
        'channels',
        'candidate_id',
        'payer_phone',

    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

}
