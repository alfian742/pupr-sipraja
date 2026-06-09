<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realization extends Model
{
    use HasFactory;

    protected $table = 'realizations';

    protected $guarded = ['id'];

    // protected $casts = [
    //     'verification_date' => 'datetime',
    // ];

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function lsPayment()
    {
        return $this->belongsTo(LsPayment::class, 'ls_payment_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
