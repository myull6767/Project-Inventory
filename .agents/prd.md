Product Requirement Document (PRD)
Project Name: LJN Inventory Management System

Stack: Laravel 13 + PHP 8.3 + MySQL + TailwindCSS + Vanilla JavaScript (Axios)
1. Konsep Aplikasi

Aplikasi berbasis web untuk mengelola inventaris barang, pelacakan stok di berbagai area (Gudang, Packing, Total), serta pencatatan barang masuk dan keluar (transaksi). Sistem menggunakan pengamanan Role-Based Access Control (RBAC) untuk membedakan hak akses antara Admin dan Staff.
2. Hak Akses (Role-Based Access Control)

Tidak ada fitur registrasi publik. Akun dibuat langsung di database atau melalui Halaman Admin.

    Staff: * Melihat Dashboard.

        Melihat Master Barang.

        Menginput Barang Masuk.

        Menginput Transaksi/Barang Keluar.

    Admin: * Memiliki semua hak akses Staff.

        Mengakses Admin Page untuk:

            Menambah/mengelola User baru (Staff/Admin).

            Menambah/mengelola Kode Toko.

            Menambah/mengelola Daftar Supplier (Resource Barang Masuk).

3. Fitur Utama & MVP
1. Login & Autentikasi

    Halaman login minimalis dan aman menggunakan Laravel Session.

    Tidak ada tombol/fitur Register.

    Proteksi CSRF pada form login dan pembatasan Rate Limiting login untuk mencegah brute-force.

2. Dashboard

    Stock Alert: Menampilkan daftar barang yang total stoknya menipis atau berada di bawah ambang batas minimum.

    Recent Transaction: Menampilkan 5-10 transaksi barang masuk dan barang keluar terbaru.

    Statistic: Grafik atau ringkasan total item barang, total transaksi bulan ini, dan aktivitas gudang.

3. Master Barang

    Komponen data barang: Kode Barang (Unique), Nama Barang, dan Stok Awal.

    Detail Stok Awal dipecah menjadi 3 kolom:

        Stok Gudang

        Stok Packing

        Total Stok (Total=Gudang+Packing)

    Behavior: Stok awal ini bersifat mengunci dan hanya akan berubah secara otomatis jika terjadi Transaksi (Barang Masuk/Keluar) atau saat user Admin/Staff melakukan Report/Adjustment resmi.

4. Barang Masuk (Inbound)

    Form input barang masuk mencakup:

        Pilihan Barang (Nama & Kode).

        Jumlah (Quantity) yang masuk.

        Sumber/Asal Barang (Daftar Supplier).

    Behavior: Setelah disubmit, jumlah barang masuk akan langsung menambah nilai Stok Gudang dan memperbarui Total Stok di Master Barang.

5. Transaksi / Barang Keluar (Outbound)

    Form input transaksi mencakup:

        Pilihan Barang (Menampilkan Nama, Kode Barang, dan Stok Saat Ini sebelum transaksi dilakukan sebagai acuan).

        Quantity barang yang keluar.

        Pilihan Kode Toko (Mewakili Nama Toko tujuan).

    Format Kode Toko Khusus: User dapat menambahkan angka/suffix di belakang kode toko saat transaksi (contoh: Kode Toko TKO-01 bisa diinput menjadi TKO-01-2 untuk penanda cabang/kluster spesifik).

    Behavior: Setelah transaksi sukses, stok di master barang akan otomatis berkurang. Nilai stok sebelum transaksi akan tersimpan di history transaksi tersebut sebagai data histori "Stok Awal Transaksi".

6. Admin Page (Khusus Role: Admin)

Halaman khusus yang diproteksi menggunakan Middleware Laravel EnsureUserIsAdmin. Fitur di dalamnya:

    Manajemen User: Form tambah User Baru (Nama, Email, Password, Role [Admin/Staff]).

    Manajemen Toko: Input data master Kode Toko dan Nama Toko.

    Manajemen Supplier: Input data master nama supplier / resource barang masuk.

4. Struktur Database (Schema Migration)
SQL

-- 1. users
users (id, name, email, password, role_id, timestamps)
roles (id, name) -- isi: admin, staff

-- 2. master_barangs
barangs (id, kode_barang [unique], nama_barang, stok_gudang, stok_packing, total_stok, stock_threshold, timestamps)

-- 3. suppliers
suppliers (id, nama_supplier, kode_supplier, timestamps)

-- 4. tokos
tokos (id, kode_toko [unique], nama_toko, timestamps)

-- 5. barang_masuks
barang_masuks (id, barang_id, supplier_id, quantity, timestamps)

-- 6. transaksi_keluars
transaksi_keluars (id, barang_id, kode_toko_inputed [varchar untuk custom angka], quantity, stok_awal_snapshot, timestamps)

5. Security & Input Handling

    XSS Protection: Semua input teks (Nama Barang, Nama Toko) wajib melewati sanitasi/escaping bawaan Blade {{ $variable }}.

    Data Validation: * Quantity barang masuk/keluar wajib berupa integer dan bernilai positif (>0).

        Transaksi barang keluar wajib divalidasi agar tidak melebihi stok yang tersedia (anti-minus stock).

    Route Security: Semua endpoint Admin wajib dibungkus dalam Route Group dengan middleware pengecekan Role Admin.

6. Antarmuka (Frontend)

    Menggunakan TailwindCSS dengan tema Light Mode yang cerah, bersih, dan profesional sesuai dengan warna korporat (Aksen Oranye #FF6600 dan Biru #0033CC).

    Menggunakan Vanilla JavaScript + Axios untuk proses pencarian barang interaktif saat input transaksi (auto-fill stok saat ini).

    Dilengkapi komponen Toast Notification jika transaksi berhasil atau ketika stok barang habis.