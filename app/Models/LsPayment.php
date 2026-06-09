<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LsPayment extends Model
{
    use HasFactory;

    protected $table = 'ls_payments';

    protected $guarded = ['id'];

    // protected $casts = [
    //     'document_date' => 'date',
    //     'saved_date' => 'date',
    //     'spp_date' => 'date',
    //     'spm_date' => 'date',
    //     'sp2d_date' => 'date',
    //     'transfer_date' => 'date',

    //     'realization_value' => 'decimal:2',
    //     'deposit_value' => 'decimal:2',
    //     'spd_value' => 'decimal:2',
    //     'sp2d_value' => 'decimal:2',
    // ];

    public function realizations()
    {
        return $this->hasMany(Realization::class, 'ls_payment_id');
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
