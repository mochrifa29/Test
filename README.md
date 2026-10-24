# Mini RFQ System (Laravel 12 & Alpine.js)

Aplikasi **Request for Quotation (RFQ)** yang dibangun dengan arsitektur modern menggunakan **Laravel 12** sebagai API Backend (mendukung *database persistence*, validasi ketat, dan *database transactions*) serta **Alpine.js & Tailwind CSS** di sisi Frontend dengan integrasi **ERP Master Data** secara *real-time*.

---

## 🚀 Tech Stack

- **Backend:** Laravel 12 (PHP 8.2+)
- **Frontend:** Alpine.js 3.x, Tailwind CSS (CDN)
- **Database:** MySQL
- **Arsitektur:** API-Driven Backend dengan Blade View & Reactive Alpine.js Frontend

---

## ✨ Fitur Utama

1. **Integrasi Master Data ERP:** Form pemilihan produk terhubung langsung dengan tabel master `products` (memuat SKU dan Harga Satuan standar gudang).
2. **Kalkulasi Real-time:** Perhitungan otomatis untuk `line_total` per produk dan *Subtotal Keseluruhan* menggunakan reaktifitas Alpine.js.
3. **Database Transaction:** Proses penyimpanan data menggunakan `DB::transaction` untuk memastikan integritas data antara tabel *header* (`rfqs`) dan *detail* (`rfq_items`).
4. **Validasi Backend Ketat:** Menggunakan `StoreRfqRequest` untuk memvalidasi input dari sisi server (SKU unik, harga non-negatif, dan kuantitas integer).
5. **Auto-Reset Form:** Form input, keranjang item, `line_total`, dan pilihan *dropdown* produk akan otomatis kembali bersih ke kondisi awal setelah RFQ berhasil dikirim.

---

## 📂 Struktur Database

- **`products`** (ERP Master Data): Menyimpan katalog produk resmi, SKU unik, stok gudang, dan harga standar.
- **`rfqs`** (Header RFQ): Menyimpan informasi perusahaan pemohon, status (`DRAFT`), dan subtotal penawaran.
- **`rfq_items`** (Detail RFQ): Menyimpan relasi *One-to-Many* dari RFQ ke produk yang diminta beserta *qty*, harga satuan, dan *line total*.

---

## ⚙️ Cara Instalasi & Menjalankan Project

Ikuti langkah-langkah berikut di terminal/command prompt:

1. **Clone / Buka Project Folder**
   ```bash
   git clone <repository-url>
   cd nama-project-laravel
   ```

2. **Install Dependensi PHP**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment (.env)**
   Salin file `.env.example` menjadi `.env` jika belum ada:
   ```bash
   cp .env.example .env
   ```
   Atur konfigurasi database MySQL Anda pada file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=mini_rfq_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate App Key**
   ```bash
   php artisan key:generate
   ```

5. **Jalankan Migrasi & Seeder**
   Perintah ini akan membuat tabel database sekaligus mengisi data master produk awal dari `ProductSeeder`:
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Jalankan Server Lokal**
   ```bash
   php artisan serve
   ```

7. **Akses Aplikasi di Browser**
   Buka browser dan kunjungi:
   👉 **`http://127.0.0.1:8000/rfq-form`**

---

## 🔌 API Endpoints

| Method | Endpoint | Deskripsi |
| :--- | :--- | :--- |
| `GET` | `/rfq-form` | Menampilkan halaman form interaktif RFQ |
| `GET` | `/api/products` | Mengambil data master produk ERP |
| `POST` | `/api/rfq` | Menyimpan data RFQ baru (disertai validasi & *transaction*) |

---

## 📄 Lisensi
Project ini dibuat untuk tujuan demo integrasi Laravel & Alpine.js.