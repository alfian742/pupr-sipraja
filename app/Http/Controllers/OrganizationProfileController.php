<?php

namespace App\Http\Controllers;

use App\Models\OrganizationProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrganizationProfileController extends Controller
{
    public function structure()
    {
        $organizationProfile = OrganizationProfile::first();

        return view('dashboard.organization-profiles.organization-structure.index', compact('organizationProfile'));
    }

    public function upsertStructure(Request $request)
    {
        $request->validate([
            'organization_structure' => 'required|url',
        ], [
            'organization_structure.required' => 'Link struktur organisasi wajib diisi.',
            'organization_structure.url' => 'Link harus berupa URL yang valid.',
        ]);

        $url = $request->input('organization_structure');

        preg_match('/\/file\/d\/([^\/]+)/', $url, $matches);
        $fileId = $matches[1] ?? null;

        $organizationStructure = $fileId ? "https://drive.google.com/file/d/{$fileId}/preview" : null;

        DB::beginTransaction();

        try {
            OrganizationProfile::updateOrCreate(
                ['id' => 1],
                [
                    'organization_structure' => $organizationStructure,
                    'organization_structure_modified_by' => Auth::user()->id,
                    'organization_structure_modified_at' => now(),
                ]
            );

            DB::commit();

            return redirect()->route('dashboard.organization-profiles.organization-structure')->with('success', 'Struktur organisasi berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui struktur organisasi: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data gagal diperbarui.')
                ->withInput();
        }
    }

    public function visionMission()
    {
        $organizationProfile = OrganizationProfile::first();

        return view('dashboard.organization-profiles.vision-and-mission.index', compact('organizationProfile'));
    }

    public function upsertVisionAndMission(Request $request)
    {
        $request->validate([
            'organization_summary' => 'required|string',
            'organization_vision' => 'required|string',
            'organization_mission' => 'required|string',
        ], [
            'organization_summary.required' => 'Ringkasan organisasi wajib diisi.',
            'organization_vision.required' => 'Visi organisasi wajib diisi.',
            'organization_mission.required' => 'Misi organisasi wajib diisi.',
        ]);

        DB::beginTransaction();

        try {
            OrganizationProfile::updateOrCreate(
                ['id' => 1],
                [
                    'organization_about' => $request->organization_about,
                    'organization_summary' => $request->organization_summary,
                    'organization_vision' => $request->organization_vision,
                    'organization_mission' => $request->organization_mission,
                    'profile_modified_by' => Auth::user()->id,
                    'profile_modified_at' => now(),
                ]
            );

            DB::commit();

            return redirect()->route('dashboard.organization-profiles.vision-and-mission')->with('success', 'Visi dan Misi organisasi berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui data visi dan misi: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data gagal diperbarui.')
                ->withInput();
        }
    }
}
