<?php

// ─────────────────────────────────────────────────────────────────────────────
// Tambahkan baris-baris ini ke dalam file routes/api.php
// di dalam middleware group 'auth:api' yang sudah ada.
// ─────────────────────────────────────────────────────────────────────────────

use App\Http\Controllers\ProfilController;

// (Contoh struktur lengkap routes/api.php)

Route::middleware('auth:api')->group(function () {

    // ... route-route lain (jadwal, mata kuliah, tugas, catatan) ...

    // ── Profil Mahasiswa ──────────────────────────────────────────────────────
    Route::prefix('profil')->group(function () {
        Route::get('/',          [ProfilController::class, 'show']);        // GET    /api/profil
        Route::put('/',          [ProfilController::class, 'update']);      // PUT    /api/profil
        Route::post('/foto',     [ProfilController::class, 'uploadFoto']);  // POST   /api/profil/foto
        Route::delete('/foto',   [ProfilController::class, 'deleteFoto']); // DELETE /api/profil/foto
    });
});
