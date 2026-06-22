# Automated Testing Laravel

Project menggunakan testing bawaan Laravel/Pest tanpa Xdebug, PCOV, atau code coverage otomatis. Seluruh command dijalankan dengan PowerShell dari root project:

```powershell
Set-Location 'C:\laragon\www\sim-op-polda'
```

Setiap script berhenti dengan exit code non-zero dan pesan `ERROR` ketika pemeriksaan atau test gagal. Tidak ada script suite utama yang menjalankan `migrate:fresh`.

## Strategi Pengujian

Pengujian laporan Kerja Praktek menggunakan:

- Laravel Feature Test dan integration testing;
- `RefreshDatabase` untuk isolasi database testing;
- HTTP assertions untuk status dan redirect;
- database assertions seperti `assertDatabaseHas` dan `assertDatabaseMissing`;
- session dan authentication assertions;
- `Storage::fake()` untuk isolasi filesystem;
- `UploadedFile::fake()` untuk pengujian upload;
- authorization testing untuk pemisahan akses admin dan kendaraan;
- white-box manual untuk menelusuri alur controller, transaksi, kondisi, dan business rule.

Code coverage otomatis tidak digunakan. Kecukupan pengujian didokumentasikan berdasarkan test case, assertion, modul, hasil integration test, dan penelusuran white-box manual.

## Status Script

| Script | Status | Tujuan |
|---|---|---|
| `01-diagnose-php.ps1` | Opsional | Diagnosis PHP CLI; tidak diperlukan untuk menjalankan test. |
| `02-install-xdebug.ps1` | Tidak digunakan/opsional | Arsip utilitas Xdebug; tidak dipanggil suite utama. |
| `03-verify-testing-environment.ps1` | Digunakan | Memastikan MySQL memakai `sim_op_polda_testing` dan menampilkan status migration. |
| `04-run-tests.ps1` | Digunakan | Menjalankan seluruh test, file tertentu, atau filter dan menyimpan output. |
| `05-run-testing-suite.ps1` | Digunakan | Menjalankan test per modul lalu seluruh suite. |
| `06-generate-test-report.ps1` | Digunakan | Membuat ringkasan Markdown dari hasil test terbaru. |

## Verifikasi Environment

```powershell
powershell.exe -NoProfile -ExecutionPolicy Bypass -File '.\scripts\testing\03-verify-testing-environment.ps1'
```

Script menghentikan proses apabila driver bukan `mysql` atau database aktif bukan `sim_op_polda_testing`. Script hanya menjalankan `optimize:clear` dan `migrate:status --env=testing`, bukan `migrate:fresh`.

## Menjalankan Test

Seluruh test:

```powershell
.\scripts\testing\04-run-tests.ps1
```

Test berdasarkan file/direktori:

```powershell
.\scripts\testing\04-run-tests.ps1 `
    -TestPath 'tests\Feature\Authentication\UniversalLoginTest.php'

.\scripts\testing\04-run-tests.ps1 `
    -TestPath 'tests\Feature\Perjalanan'
```

Test berdasarkan filter:

```powershell
.\scripts\testing\04-run-tests.ps1 -Filter 'Perjalanan'
```

Berhenti pada kegagalan pertama:

```powershell
.\scripts\testing\04-run-tests.ps1 -StopOnFailure
```

Output tetap ditampilkan di terminal dan disimpan sebagai `storage/app/testing-results/tests-<timestamp>.txt`.

## Menjalankan Suite Bertahap

```powershell
.\scripts\testing\05-run-testing-suite.ps1
```

Urutan suite:

1. verifikasi environment testing;
2. authentication starter-kit dan login universal;
3. middleware;
4. penugasan dan otorisasi penugasan;
5. perjalanan;
6. laporan kerusakan;
7. perbaikan;
8. odometer;
9. export;
10. seluruh automated test.

Setiap tahap menghasilkan log test tersendiri. Output orkestrasi lengkap disimpan sebagai `testing-suite-<timestamp>.txt`.

## Membuat Ringkasan Markdown

Jalankan setelah test penuh atau suite selesai:

```powershell
.\scripts\testing\06-generate-test-report.ps1
```

Hasil disimpan pada:

```text
storage/app/testing-results/ringkasan-pengujian.md
```

Generator tidak mengarang data. Nilai yang tidak tersedia dalam output TestDox ditulis sebagai `perlu diisi manual`. PHPUnit/Pest hanya memberikan jumlah assertion total, sehingga assertion per modul perlu dilengkapi manual bila dibutuhkan dalam laporan.

## White-Box Manual

Dokumentasikan pemeriksaan manual berikut di laporan KP:

1. controller dan middleware yang dilewati setiap request;
2. validasi input dan cabang kondisi yang diuji;
3. perubahan status model sebelum dan sesudah request;
4. transaksi database dan penanganan kegagalan;
5. otorisasi admin, kendaraan, dan kepemilikan penugasan;
6. penggunaan fake storage agar file asli tidak berubah;
7. business rule yang belum memiliki automated test.

## Test Skipped

Test skipped tidak dihapus:

- tiga test verifikasi email: model `User` tidak menerapkan `MustVerifyEmail`;
- dua test registrasi: registrasi publik merupakan alur starter-kit dan user hasil registrasi tidak mempunyai role aplikasi.

Evaluasi business rule harus dilakukan sebelum test tersebut diaktifkan, diubah, atau dihapus.

## Xdebug dan PCOV

Xdebug dan PCOV tidak diperlukan dan tidak digunakan dalam strategi testing saat ini. `02-install-xdebug.ps1` dipertahankan hanya sebagai utilitas opsional untuk kemungkinan kebutuhan terpisah di masa depan dan tidak pernah dipanggil oleh `05-run-testing-suite.ps1`.
