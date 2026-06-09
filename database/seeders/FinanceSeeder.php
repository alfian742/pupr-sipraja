<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\LsPayment;
use App\Models\Realization;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FinanceSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            'CIPTA KARYA',
            'BINA MARGA',
            'SDA',
            'SEK'
        ];

        $baseDate = Carbon::create(2025, 1, 1);

        for ($i = 1; $i <= 1000; $i++) {

            $department = $departments[$i % count($departments)];

            $contractValue = 20000000 + ($i * 30000000);
            $realizationValue = 20000000 + (($i % 5) * 30000000);

            $contract = Contract::create([
                'contract_number' => 'CNT-2025-' . str_pad($i, 4, '0', STR_PAD_LEFT),

                'contract_start_date' => $baseDate->copy()->addDays($i),
                'contract_end_date' => $baseDate->copy()->addDays($i + 120),

                'third_party_name' => 'PT Rekanan ' . $i,

                'activity_code' => '1.03.02.01.' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'sub_account_code' => '5.2.01.01.' . str_pad($i, 4, '0', STR_PAD_LEFT),

                'activity_description' => 'Pekerjaan Infrastruktur ' . $department,
                'department' => $department,

                'budget_value' => $contractValue + 50000000,
                'contract_value' => $contractValue,

                'fund_source' => 'APBD',
                'bast_number' => 'BAST-' . str_pad($i, 3, '0', STR_PAD_LEFT),

                'created_by' => 1,
                'updated_by' => 1,
            ]);

            /*
            |--------------------------------------------------------------------------
            | LS PAYMENT
            |--------------------------------------------------------------------------
            */

            // sebagian dibuat tidak cocok
            $accountCode = ($i % 7 === 0)
                ? '5.9.99.99.999'
                : $contract->sub_account_code;

            $lsPayment = LsPayment::create([

                'skpd_code' => '1.03',
                'skpd_name' => 'Dinas PUPR',

                'sub_skpd_code' => '1.03.01',
                'sub_skpd_name' => 'DPUPR',

                'function_code' => '03',
                'function_name' => 'Pekerjaan Umum',

                'sub_function_code' => '03.01',
                'sub_function_name' => 'Infrastruktur',

                'affair_code' => '03',
                'affair_name' => 'Pekerjaan Umum',

                'field_affair_code' => '03.01',
                'field_affair_name' => $department,

                'program_code' => 'PRG-' . $i,
                'program_name' => 'Program Infrastruktur',

                'activity_code' => $contract->activity_code,
                'activity_name' => 'Kegiatan Infrastruktur',

                'sub_activity_code' => 'SUB-' . $i,
                'sub_activity_name' => 'Sub Kegiatan Infrastruktur',

                'account_code' => $accountCode,
                'account_name' => 'Belanja Modal',

                'document_number' => 'DOC-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'document_type' => 'SPP',
                'transaction_type' => 'LS',
                'dpt_number' => 'DPT-' . $i,

                'document_date' => $baseDate->copy()->addDays($i + 30),
                'document_description' => 'Pembayaran Termin ' . (($i % 3) + 1),

                'realization_value' => $realizationValue,
                'deposit_value' => 0,

                'nip' => '19800101000000' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'personnel_name' => 'Pejabat ' . $department,

                'saved_date' => $baseDate->copy()->addDays($i + 30),

                'spd_number' => 'SPD-' . $i,
                'spd_period' => '2025',
                'spd_value' => $contractValue,
                'spd_stage' => 'Tahap 1',
                'sub_stage_name' => 'Sub Tahap',
                'apbd_stage' => 'APBD Murni',

                'spp_number' => 'SPP-' . $i,
                'spp_date' => $baseDate->copy()->addDays($i + 40),

                'spm_number' => 'SPM-' . $i,
                'spm_date' => $baseDate->copy()->addDays($i + 45),

                'sp2d_number' => 'SP2D-' . $i,
                'sp2d_date' => $baseDate->copy()->addDays($i + 50),

                'transfer_date' => $baseDate->copy()->addDays($i + 51),
                'sp2d_value' => $realizationValue,

                'created_by' => 1,
                'updated_by' => 1,
            ]);

            /*
            |--------------------------------------------------------------------------
            | REALIZATION
            |--------------------------------------------------------------------------
            */

            $matchStatus = (
                $contract->activity_code === $lsPayment->activity_code &&
                $contract->sub_account_code === $lsPayment->account_code
            ) ? 'SAMA' : 'BEDA';

            Realization::create([
                'verification_date' => now(),

                'contract_id' => $contract->id,
                'ls_payment_id' => $lsPayment->id,

                'match_status' => $matchStatus,

                'verified_by' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ]);
        }
    }
}
