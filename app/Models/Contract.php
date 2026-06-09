<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $table = 'contracts';

    protected $guarded = ['id'];

    // protected $casts = [
    //     'contract_start_date' => 'date',
    //     'contract_end_date' => 'date',

    //     'budget_value' => 'decimal:2',
    //     'contract_value' => 'decimal:2',
    // ];

    public function realizations()
    {
        return $this->hasMany(Realization::class, 'contract_id');
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
