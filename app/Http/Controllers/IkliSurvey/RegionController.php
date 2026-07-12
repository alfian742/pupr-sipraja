<?php

namespace App\Http\Controllers\IkliSurvey;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Village;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    // Daftar Kecamatan API
    public function districtList()
    {
        $districts = District::select('id', 'district_name', 'resident_count')
            ->orderBy('district_name', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $districts
        ]);
    }

    // Daftar Kelurahan/Desa API
    public function villageList(Request $request)
    {
        $request->validate([
            'district_id' => 'required|exists:districts,id'
        ]);

        $villages = Village::select('id', 'village_name', 'district_id')
            ->where('district_id', $request->district_id)
            ->orderBy('village_name', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $villages
        ]);
    }
}
