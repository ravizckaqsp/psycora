# Psycora – Sistem Monitoring Risiko Psikososial Remaja (Role: Siswa)

## Status Audit Project

Setelah analisis mendalam, berikut yang **sudah ada** vs **yang belum ada**:

### ✅ Sudah Ada
| File | Status |
|------|--------|
| Semua migrations (13 file) | ✅ Lengkap |
| Models: User, QuestionnaireResponse, RiskClassification, Question, SdqAnswer, Psc17Answer, SassvAnswer | ✅ Ada tapi perlu perbaikan |
| `QuestionnaireController.php` | ✅ Logic scoring sudah ada |
| `SiswaDashboardController.php` | ⚠️ Stub kosong |
| Routes siswa dasar | ✅ Ada tapi kurang |
| QuestionSeeder, RoleSeeder, AdminSeeder | ✅ Ada |
| `DatabaseSeeder.php` | ⚠️ Ada bug syntax (double `{`) |
| `siswa/dashboard.blade.php` | ⚠️ Basic, tidak dinamis |
| `auth/login.blade.php` | ⚠️ Plain Breeze, belum premium |
| `layouts/app.blade.php` | ⚠️ Default Breeze, belum siswa-layout |

### ❌ Belum Ada (Yang Harus Dibuat)
| Yang Dibutuhkan | Prioritas |
|----------------|-----------|
| Model `Classes` + `ChatbotLog` | Tinggi |
| `SiswaSeeder` (dummy student data) | Tinggi |
| Layout siswa premium (`layouts/siswa.blade.php`) | Tinggi |
| Login page premium dengan health-tech UI | Tinggi |
| Dashboard siswa dinamis (data real) | Tinggi |
| Kuesioner wizard views (index, sdq, psc17, sassv) | Tinggi |
| Hasil kuesioner view + Chart.js | Tinggi |
| Riwayat kuesioner (controller + view) | Tinggi |
| Chatbot controller + view + service | Tinggi |
| Routes riwayat & chatbot | Tinggi |
| `ScoringService` (refactor dari controller) | Sedang |
| App CSS premium (custom design tokens) | Sedang |

---

## Proposed Changes

### 1. Bug Fix & Model Completion

#### [MODIFY] `DatabaseSeeder.php`
- Fix bug double `{` yang menyebabkan syntax error

#### [NEW] `app/Models/Classes.php`
- Model untuk tabel kelas siswa

#### [NEW] `app/Models/ChatbotLog.php`
- Model untuk menyimpan percakapan chatbot

#### [MODIFY] `app/Models/RiskClassification.php`
- Tambah `belongsTo(QuestionnaireResponse::class)`

---

### 2. Services (Clean Architecture)

#### [NEW] `app/Services/ScoringService.php`
- Ekstrak logic `calculateAndClassify` dari controller ke service class
- Method: `calculate(QuestionnaireResponse $response): array`

#### [NEW] `app/Services/ChatbotService.php`
- Rule-based chatbot engine
- Topics: gawai sehat, kesehatan psikososial, manajemen waktu, aktivitas positif, FAQ
- Method: `respond(string $message, ?User $user): array`

---

### 3. Controllers

#### [MODIFY] `SiswaDashboardController.php`
- Query last completed response + risk classification
- Query riwayat 5 terakhir
- Pass semua data ke view

#### [MODIFY] `QuestionnaireController.php`
- Inject `ScoringService`
- Ubah wizard menjadi **one question per screen** dengan step tracking via session

#### [NEW] `app/Http/Controllers/Siswa/RiwayatController.php`
- `index()`: daftar semua riwayat kuesioner user
- `show($id)`: detail hasil kuesioner

#### [NEW] `app/Http/Controllers/Siswa/ChatbotController.php`
- `index()`: halaman chatbot
- `chat(Request $request)`: API endpoint, return JSON response

---

### 4. Seeders

#### [MODIFY] `DatabaseSeeder.php`
- Fix bug, tambah `SiswaSeeder::class`

#### [NEW] `database/seeders/SiswaSeeder.php`
- 5 dummy siswa dengan password `password`
- Masing-masing punya 2-3 riwayat kuesioner lengkap

---

### 5. Routes

#### [MODIFY] `routes/web.php`
- Tambah rute riwayat: `siswa.riwayat.index`, `siswa.riwayat.show`
- Tambah rute chatbot: `siswa.chatbot.index`, `siswa.chatbot.chat`

---

### 6. Views (Premium Health-Tech UI)

#### Design System
- Font: **Inter** (Google Fonts)
- Warna primer: Indigo-Violet gradient
- Dark/Light mode via Tailwind `dark:` prefix
- Smooth transitions, micro-animations

#### [MODIFY] `resources/views/auth/login.blade.php`
- Full redesign: split-screen, left panel branding, right panel form
- Health-tech aesthetic dengan gradient dan ilustrasi

#### [MODIFY] `resources/views/layouts/app.blade.php`
- Siswa-specific layout dengan sidebar navigation

#### [NEW] `resources/views/layouts/siswa.blade.php`
- Sidebar with: Dashboard, Kuesioner, Riwayat, Chatbot, Logout
- Mobile-first dengan hamburger menu
- Active state detection

#### [MODIFY] `resources/views/siswa/dashboard.blade.php`
- Welcome banner dinamis
- Card: Skor terakhir, Level risiko (color-coded badge)
- Progress bar kuesioner completion
- Mini chart perkembangan (Chart.js)
- CTA button ke kuesioner

#### [NEW] `resources/views/siswa/questionnaire/index.blade.php`
- Halaman "Mulai Kuesioner" dengan info 3 instrumen
- Status completion per instrumen

#### [NEW] `resources/views/siswa/questionnaire/wizard.blade.php`
- **One question per screen** universal wizard
- Animated progress bar
- Question counter
- Back/Next buttons
- Auto-save indicator
- Alpine.js untuk transisi slide

#### [NEW] `resources/views/siswa/questionnaire/result.blade.php`
- Summary cards: SDQ, PSC-17, SAS-SV scores
- Radar/Bar chart dengan Chart.js
- Level risiko (colored badge + icon)
- Rekomendasi edukatif per level
- Tombol "Lihat Riwayat"

#### [NEW] `resources/views/siswa/riwayat/index.blade.php`
- Table/card daftar semua kuesioner
- Badge warna level risiko
- Link ke detail
- Line chart perkembangan skor

#### [NEW] `resources/views/siswa/riwayat/show.blade.php`
- Detail lengkap satu kuesioner
- Chart radar per instrumen

#### [NEW] `resources/views/siswa/chatbot/index.blade.php`
- Bubble UI conversational
- Quick reply chips
- Typing indicator animasi
- Topic categories sidebar
- Alpine.js + Fetch API

---

### 7. CSS

#### [MODIFY] `resources/css/app.css`
- Custom design tokens
- Scrollbar styling
- Chat bubble styles
- Wizard transition animations

---

## Urutan Implementasi

1. Fix `DatabaseSeeder.php` bug
2. Buat `Classes` + `ChatbotLog` models
3. Buat `ScoringService` + `ChatbotService`
4. Update `SiswaDashboardController`
5. Refactor `QuestionnaireController` (wizard per soal)
6. Buat `RiwayatController` + `ChatbotController`
7. Update routes
8. Buat `SiswaSeeder`
9. Redesign login page
10. Buat `layouts/siswa.blade.php`
11. Update `siswa/dashboard.blade.php`
12. Buat semua questionnaire views
13. Buat result view + Chart.js
14. Buat riwayat views
15. Buat chatbot view
16. Update CSS

## Verification Plan

- `php artisan migrate:fresh --seed` – pastikan semua seeder jalan
- Login dengan akun siswa dummy
- Test full kuesioner wizard SDQ → PSC17 → SAS-SV
- Cek halaman hasil dengan chart
- Cek riwayat
- Test chatbot dengan berbagai keyword
