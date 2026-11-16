# 🏫 Psycora – Sistem Monitoring Risiko Psikososial & Ketergantungan Gawai Remaja

[![Laravel Version](https://img.shields.io/badge/Laravel-v12.x-FF2D20?style=flat-flat&logo=laravel)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-^8.2-777BB4?style=flat-flat&logo=php)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-v4.0-38BDF8?style=flat-flat&logo=tailwind-css)](https://tailwindcss.com)
[![AlpineJS](https://img.shields.io/badge/AlpineJS-v3.x-8BC0D0?style=flat-flat&logo=alpine.js)](https://alpinejs.dev)

---

## 📌 Tentang Psycora

**Psycora** adalah platform berbasis web interaktif yang dirancang khusus untuk memantau kondisi psikososial serta mendeteksi tingkat ketergantungan gawai (gadget addiction) pada remaja secara mandiri (*kuesioner mandiri*). 

Melalui pendekatan preventif terintegrasi, platform ini menggabungkan **tiga instrumen penilaian klinis terpercaya** ke dalam satu sesi kuesioner terpadu, didukung oleh visualisasi data analitis (*Business Intelligence*) untuk Guru Bimbingan Konseling (BK), dan chatbot interaktif berbasis **LLM Gemini** untuk memberikan edukasi kesehatan mental dan manajemen waktu secara langsung kepada siswa.

Tujuan utama sistem ini adalah mendeteksi tanda-tanda gangguan perilaku sejak dini agar Guru BK atau pihak sekolah dapat mengambil tindakan pencegahan (*preventif*) yang tepat sebelum berkembang menjadi gangguan klinis yang lebih serius.

---

## 👥 Arsitektur Peran & Fitur Utama

Sistem Psycora membagi hak akses dan fungsionalitas ke dalam tiga peran utama:

### 1. 🎓 Peran: Siswa
Siswa bertindak sebagai subjek utama yang memantau kondisi kesehatan psikososial mereka secara mandiri (*kuesioner mandiri*).
- **Autentikasi Aman:** Login dan logout ke dalam sistem dengan kredensial terdaftar.
- **Kuesioner Terpadu (3-in-1):** Mengisi instrumen penilaian dalam satu sesi interaktif yang menggabungkan:
  - **SDQ (Strengths and Difficulties Questionnaire):** Mengukur aspek kekuatan dan kesulitan perilaku serta emosional siswa.
  - **PSC-17 (Pediatric Symptom Checklist-17):** Screening kondisi psikososial kognitif, emosi, dan atensi.
  - **SAS-SV (Smartphone Addiction Scale - Short Version):** Mengukur tingkat kecenderungan ketergantungan gawai.
- **Klasifikasi Risiko Otomatis:** Setelah pengisian, sistem menghitung skor secara otomatis dan menampilkan tingkat risiko siswa dengan empat kategori: **Risiko Rendah**, **Sedang**, **Tinggi**, dan **Sangat Tinggi**.
- **Chatbot Edukatif (LLM Gemini):** Fitur interaktif untuk berdialog mengenai penggunaan gawai yang sehat, kiat manajemen waktu, dan regulasi emosi.
- **Tampilan Dashboard Siswa:**
  - Skor ringkasan hasil penilaian psikososial terakhir.
  - Indikator tingkat intensitas penggunaan gawai.
  - Grafik riwayat pengisian kuesioner sebelumnya untuk melihat tren personal.

### 2. 👩‍🏫 Peran: Guru BK (Bimbingan Konseling)
Guru BK bertindak sebagai pemantau utama yang menganalisis data visual untuk mendukung keputusan intervensi cepat atau konseling terarah.
- **Visualisasi Data Kondisi Siswa:** Memantau kondisi kesehatan mental dan ketergantungan gawai secara berkala dan *real-time*.
- **Pelacakan Tren Risiko:** Mengamati perkembangan grafik naik-turun tingkat risiko siswa di sekolah secara individual maupun kolektif.
- **Pengambilan Kebijakan Preventif:** Mengunduh dan memanfaatkan ringkasan data kuesioner sebagai dasar rancangan tindakan pencegahan sebelum masalah siswa memburuk.
- **Tampilan Dashboard Guru BK:**
  - Grafik umum kondisi psikososial seluruh siswa sekolah.
  - Ringkasan statistik jumlah siswa di setiap kategori risiko (Rendah/Sedang/Tinggi/Sangat Tinggi).
  - Grafik tren bulanan tingkat ketergantungan gawai.
  - Distribusi status risiko siswa per kelas serta visualisasi skor rata-rata instrumen.

### 3. 🛡️ Peran: Admin
Admin bertindak sebagai pengelola teknis operasional sistem untuk memastikan data master platform berjalan dengan benar.
- **Manajemen Akun Pengguna:** Mengelola data master siswa dan Guru BK (Tambah, Edit, Hapus, Aktivasi).
- **Konfigurasi Soal Kuesioner:** Mengatur butir-butir pertanyaan dari instrumen SDQ, PSC-17, dan SAS-SV di dalam database untuk pemeliharaan berkala.

### 📊 Fitur Pendukung Terintegrasi: Dashboard Business Intelligence (BI)
Dashboard BI makro dirancang khusus bagi manajemen sekolah dan Guru BK untuk melakukan analisis data psikososial mendalam:
- **Filter Kondisi Demografis:** Grafik visualisasi kondisi psikososial berdasarkan kelas dan gender siswa.
- **Tren Perkembangan Bulanan:** Grafik historis perubahan kondisi mental siswa sekolah dari bulan ke bulan.
- **Analisis Korelasi Khusus:** Visualisasi hubungan korelatif antara skor ketergantungan gawai (**SAS-SV**) dengan skor gangguan perilaku perilaku/emosi siswa (**SDQ & PSC-17**).

---
