<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfilController extends Controller
{
    // ── GET /api/profil ───────────────────────────────────────────────────────

    /**
     * Tampilkan profil mahasiswa yang sedang login.
     */
    public function show(): JsonResponse
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diambil.',
            'data'    => $user,
        ]);
    }

    // ── PUT /api/profil ───────────────────────────────────────────────────────

    /**
     * Perbarui data profil (nama, NIM, program studi, angkatan, no HP).
     */
    public function update(Request $request): JsonResponse
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name'           => 'sometimes|string|max:255',
            'nim'            => 'sometimes|string|max:20|unique:users,nim,' . $user->id,
            'program_studi'  => 'sometimes|string|max:100',
            'angkatan'       => 'sometimes|string|max:10',
            'no_hp'          => 'sometimes|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data'    => $user->fresh(),
        ]);
    }

    // ── POST /api/profil/foto ─────────────────────────────────────────────────

    /**
     * Upload / ganti foto profil.
     * Menerima multipart/form-data dengan field "foto".
     */
    public function uploadFoto(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'foto' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = Auth::user();

        // Hapus foto lama jika ada
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        // Simpan foto baru ke storage/app/public/foto_profil/
        $path = $request->file('foto')->store('foto_profil', 'public');

        $user->update(['foto' => $path]);

        return response()->json([
            'success'   => true,
            'message'   => 'Foto profil berhasil diperbarui.',
            'foto_url'  => $user->fresh()->foto_url,
        ]);
    }

    // ── DELETE /api/profil/foto ───────────────────────────────────────────────

    /**
     * Hapus foto profil (set ke null).
     */
    public function deleteFoto(): JsonResponse
    {
        $user = Auth::user();

        if (!$user->foto) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada foto profil untuk dihapus.',
            ], 404);
        }

        if (Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        $user->update(['foto' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil dihapus.',
        ]);
    }
}
