# Mini RFQ System (Laravel 12 & Alpine.js)

Aplikasi **Request for Quotation (RFQ)** yang dibangun dengan arsitektur modern menggunakan **Laravel 12** sebagai API Backend (mendukung *database persistence*, validasi ketat, dan *database transactions*) serta **Alpine.js & Tailwind CSS** di sisi Frontend dengan integrasi **ERP Master Data** secara *real-time*.

---

## 🚀 Tech Stack

* **Backend:** Laravel 12 (PHP 8.2+)
* **Frontend:** Alpine.js 3.x, Tailwind CSS (CDN)
* **Database:** MySQL
* **Arsitektur:** API-Driven Backend dengan Blade View & Reactive Alpine.js Frontend

---

## ✨ Fitur Utama

1. **Integrasi Master Data ERP:** Form pemilihan produk terhubung langsung dengan tabel master `products` (memuat SKU dan Harga Satuan standar gudang).
2. **Kalkulasi Real-time:** Perhitungan otomatis untuk `line_total` per produk dan *Subtotal Keseluruhan* menggunakan reaktifitas Alpine.js.
3. **Database Transaction:** Proses penyimpanan data menggunakan `DB::transaction` untuk memastikan integritas data antara tabel *header* (`rfqs`) dan *detail* (`rfq_items`).
4. **Validasi Backend Ketat:** Menggunakan `StoreRfqRequest` untuk memvalidasi input dari sisi server (SKU unik, harga non-negatif, dan kuantitas integer).
5. **Auto-Reset Form:** Form input, keranjang item, dan pilihan *dropdown* produk akan otomatis kembali bersih ke kondisi awal setelah RFQ berhasil dikirim.

---

## 📂 Struktur Database

* **`products`** (ERP Master Data): Menyimpan katalog produk resmi, SKU unik, stok gudang, dan harga standar.
* **`rfqs`** (Header RFQ): Menyimpan informasi perusahaan pemohon, status (`DRAFT`), dan subtotal penawaran.
* **`rfq_items`** (Detail RFQ): Menyimpan relasi *One-to-Many* dari RFQ ke produk yang diminta beserta *qty*, harga satuan, dan *line total*.

---

## ⚙️ Cara Instalasi & Menjalankan Project

Ikuti langkah-langkah berikut di terminal/command prompt:

1. **Clone / Buka Project Folder**
   ```bash
   cd nama-project-laravel