# 🚚 Sistem Tracking Bongkar Muat - PT CBA Chemical Industry

Sistem Tracking Bongkar Muat adalah aplikasi berbasis web yang dirancang untuk memantau, mencatat, dan mengoptimalkan alur kerja kendaraan logistik di lingkungan pabrik **PT CBA Chemical Industry**. Aplikasi ini memungkinkan pemantauan durasi setiap tahapan proses (security, loading, dokumentasi) secara realtime.

---

## 🌟 Fitur Utama

- **Real-time Dashboard**: Pantau status kendaraan yang sedang diproses secara langsung.
- **Multi-Role Workflow**: Alur kerja yang terintegrasi antara Security, Petugas Loading, dan Officer TTB/SJ.
- **Public Input (Supir)**: Driver atau vendor dapat menginput data kendaraan mereka secara mandiri melalui modal publik untuk mempercepat pendaftaran.
- **Reporting & Export**: Ekspor laporan harian atau periode tertentu ke format Excel (.xlsx).
- **Live Update Widget**: Widget di halaman depan yang menampilkan pembaruan status terakhir secara otomatis.
- **Bulk Management**: Fitur admin untuk penghapusan data massal dan koreksi data master.

---

## 🔄 Alur Kerja (Workflow)

Sistem membagi proses menjadi beberapa tahap berurutan:

1.  **Registrasi (Security In)**: Input data awal kendaraan (Plat nomor, vendor, driver) oleh Security atau mandiri oleh Supir.
2.  **Proses Bongkar/Muat**: Petugas Loading memulai proses (Loading Started) dan mencatat saat selesai (Loading Ended).
3.  **Administrasi TTB/SJ**: Officer TTB memproses dokumen serah terima barang (TTB Started/Ended).
4.  **Distribusi**: Penyerahan dokumen kembali ke Supir.
5.  **Finalisasi (Security Out)**: Security melakukan verifikasi akhir dan mencatat waktu keluar kendaraan (Completed).

---

## 👥 Peran Pengguna (User Roles)

| Role | Deskripsi |
| :--- | :--- |
| **Admin** | Akses penuh: Edit data master, hapus data, kelola user, dan ekspor laporan. |
| **Security** | Mencatat kendaraan masuk/keluar dan verifikasi input mandiri supir. |
| **Loading** | Bertanggung jawab mencatat durasi proses fisik bongkar atau muat barang. |
| **TTB/SJ** | Mengelola proses administrasi dokumen dan surat jalan. |

---

## 🛠️ Teknologi yang Digunakan

- **Backend**: [Laravel 10](https://laravel.com/)
- **Frontend Interactivity**: [Livewire 3](https://livewire.laravel.com/) & [Alpine.js](https://alpinejs.dev/)
- **Styling**: [Tailwind CSS](https://tailwindcss.com/)
- **Database**: MySQL / MariaDB
- **Excel Processing**: [Laravel Excel (Maatwebsite)](https://docs.laravel-excel.com/)

---

## 🚀 Instalasi & Setup

### Prasyarat
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL Database

### Langkah-langkah
1.  **Clone Repository**
    ```bash
    git clone [repository-url]
    cd tracking-bongkar-muat
    ```

2.  **Instal Dependensi**
    ```bash
    composer install
    npm install
    ```

3.  **Konfigurasi Environment**
    Copy file `.env.example` menjadi `.env` dan sesuaikan konfigurasi database.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Database Migration**
    ```bash
    php artisan migrate
    ```

5.  **Build Assets & Run**
    ```bash
    npm run dev
    php artisan serve
    ```

---

## 📄 Lisensi
Sistem ini dikembangkan secara internal untuk **PT CBA Chemical Industry**.

---
*Dibuat dengan ❤️ untuk efisiensi logistik.*
