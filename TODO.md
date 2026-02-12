# TODO: Fix Route [dokter.create] Not Defined Error

- [ ] Add use statements for DokterController and JadwalController at the top of routes/web.php
- [ ] In the admin group, remove Route::get('/dokter', fn() => view('admin.dokter.index'));
- [ ] Add Route::resource('dokter', DokterController::class); in the admin group
- [ ] Verify routes are registered correctly by running php artisan route:list

## PEMBATASAN RESERVASI PASIEN

### Aturan

- Satu pasien hanya boleh memiliki 1 reservasi pada waktu yang sama.

### Definisi “waktu yang sama”

- Tanggal sama
- Poli sama
- Sesi waktu sama (pagi / siang / sore / malam)
- Status reservasi aktif (menunggu / terverifikasi)

### Logika

Jika pasien sudah punya reservasi aktif di sesi waktu yang sama, maka:
- ❌ Reservasi ditolak
