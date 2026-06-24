<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.users.index');
    }

    /**
     *  get data for DataTables  
     */
    public function getData()
    {
        if (request()->ajax()) {
            $query = User::query()->orderBy('last_login_at', 'DESC');

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('last_login_at', function ($data) {
                    return $data->last_login_at ? Carbon::parse($data->last_login_at)->locale(app()->getLocale())->diffForHumans() : '-';
                })
                ->editColumn('email_verified_at', function ($data) {
                    return $data->email_verified_at ? Carbon::parse($data->email_verified_at)->locale(app()->getLocale())->translatedFormat('d F Y H:i') : '-';
                })
                ->editColumn('is_active', function ($data) {
                    if ($data->is_active) {
                        return '<span class="badge bg-success px-1">Aktif</span>';
                    } else {
                        return '<span class="badge bg-danger px-1">Tidak Aktif</span>';
                    }
                })
                ->editColumn('role', function ($data) {
                    if ($data->role === 'superadmin') {
                        return '<span class="badge bg-primary px-1">Super Admin</span>';
                    } elseif ($data->role === 'admin') {
                        return '<span class="badge bg-indigo px-1">Admin</span>';
                    } elseif ($data->role === 'operator') {
                        return '<span class="badge bg-secondary px-1">Operator</span>';
                    } elseif ($data->role === 'head_of_department') {
                        return '<span class="badge bg-blue px-1">Pimpinan</span>';
                    } else {
                        return '<span class="badge bg-warning px-1">User</span>';
                    }
                })
                ->addColumn('action', function ($data) {

                    $editRoute          = route('dashboard.users.edit', $data->id);
                    $setStatusRoute     = route('dashboard.users.set-status', $data->id);
                    $resetPasswordRoute = route('dashboard.users.reset-password', $data->id);
                    $deleteRoute        = route('dashboard.users.destroy', $data->id);

                    $isActive   = (bool) $data->is_active;
                    $nextStatus = $isActive ? 0 : 1;
                    $statusBtn  = $isActive ? 'btn-danger' : 'btn-success';
                    $statusIcon = $isActive ? 'fa-lock' : 'fa-unlock';
                    $statusText = $isActive ? 'Nonaktifkan Akun' : 'Aktifkan Akun';

                    return '
                    <div class="d-flex justify-content-center" style="gap: 0.5rem">

                        <!-- Toggle Status -->
                        <form id="set-status-form-' . $data->id . '" action="' . $setStatusRoute . '" method="POST" class="d-inline">
                            ' . csrf_field() . '
                            ' . method_field('PUT') . '
                            <input type="hidden" name="is_active" value="' . $nextStatus . '">
                            <button type="button"
                                class="btn btn-sm ' . $statusBtn . '"
                                onclick="confirmSetStatus(' . $data->id . ', ' . ($isActive ? 'true' : 'false') . ')"
                                title="' . $statusText . '">
                                <i class="fa ' . $statusIcon . '"></i>
                            </button>
                        </form>

                        <!-- Reset Password -->
                        <form id="reset-password-form-' . $data->id . '" action="' . $resetPasswordRoute . '" method="POST" class="d-inline">
                            ' . csrf_field() . '
                            ' . method_field('PUT') . '
                            <button type="button"
                                class="btn btn-sm btn-warning"
                                onclick="confirmResetPassword(' . $data->id . ')"
                                title="Reset Kata Sandi">
                                <i class="fa fa-key"></i>
                            </button>
                        </form>

                        <!-- Edit -->
                        <a href="' . $editRoute . '" class="btn btn-sm btn-indigo" title="Edit">
                            <i class="fa fa-pencil"></i>
                        </a>

                        <!-- Delete -->
                        <form id="delete-form-' . $data->id . '" action="' . $deleteRoute . '" method="POST" class="d-inline">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="button"
                                class="btn btn-sm btn-danger"
                                onclick="confirmDelete(' . $data->id . ')"
                                title="Hapus">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>

                    </div>
                    ';
                })
                // Tambahkan kolom lain yang mungkin tidak ada di DB, misal kolom nomor urut jika diperlukan
                ->rawColumns(['is_active', 'role', 'action'])->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:dns|unique:users,email',
            'role' => 'required|in:fasilitator,admin,superadmin',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'role.required' => 'Peran wajib dipilih.',
            'role.in' => 'Peran yang dipilih tidak valid.',
        ]);

        DB::beginTransaction();

        try {
            $generatePassword = Str::password(8);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($generatePassword),
                'is_active' => true,
                'role' => $validated['role'],
            ]);

            $user->forceFill([
                'email_verified_at' => now(),
            ])->save();

            DB::commit();

            return redirect()
                ->route('dashboard.users.index')
                ->with('success', "Pengguna baru berhasil ditambahkan, {$validated['name']} ({$validated['email']}) dan kata sandi '$generatePassword'.");
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal menambahkan pengguna: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, pengguna gagal ditambahkan.')
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('dashboard.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:dns|unique:users,email,' . $user->id,
            'role' => 'required|in:fasilitator,admin,superadmin',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'role.required' => 'Peran wajib dipilih.',
            'role.in' => 'Peran yang dipilih tidak valid.',
        ]);

        DB::beginTransaction();

        try {
            $user->update([
                'name' => $validated['name'],
                'role' => $validated['role'],
            ]);

            if ($user->email !== $validated['email']) { // Jika email diubah, set email_verified_at lagi
                $user->forceFill([
                    'email' => $validated['email'],
                    'email_verified_at' => now(),
                ])->save();
            }

            DB::commit();

            return redirect()
                ->route('dashboard.users.index')
                ->with('success', 'Pengguna berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan pengguna: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, pengguna gagal diperbarui.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        DB::beginTransaction();

        try {
            $user->delete();

            DB::commit();

            return redirect()
                ->route('dashboard.users.index')
                ->with('success', 'Pengguna telah dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal menghapus pengguna: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, pengguna gagal dihapus.');
        }
    }

    public function setStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);

        DB::beginTransaction();

        try {
            $user->update([
                'is_active' => $request->boolean('is_active')
            ]);

            DB::commit();

            $message = $user->is_active
                ? "Akun {$user->name} ({$user->email}) telah diaktifkan."
                : "Akun {$user->name} ({$user->email}) telah dinonaktifkan.";

            return redirect()
                ->route('dashboard.users.index')
                ->with('success', $message);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal mengubah status akun: ' . $e->getMessage());

            return back()->with('error', 'Gagal mengubah status akun.');
        }
    }

    /**
     * Reset password of the specified user.
     */
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        DB::beginTransaction();

        try {
            $generatePassword = Str::password(8);

            $user->password = Hash::make($generatePassword);

            $user->update();

            DB::commit();

            return redirect()->route('dashboard.users.index')
                ->with('success', "Kata sandi {$user->name} ({$user->email}) telah direset menjadi '{$generatePassword}'.");
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal mereset kata sandi: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal mereset kata sandi.');
        }
    }
}
