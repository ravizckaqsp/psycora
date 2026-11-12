<?php

namespace App\Services;

class ChatbotService
{
    /**
     * Pattern → [response, topic]
     */
    private array $patterns = [
        // Sapaan
        ['pattern' => '/halo|hai|hi|hello|selamat/i',
         'response' => "Halo! 👋 Saya PsyBot, asisten kesehatan psikososialmu. Saya bisa membantu kamu tentang:\n\n1. 📱 Penggunaan gadget sehat\n2. 🧠 Kesehatan psikososial\n3. ⏰ Manajemen waktu\n4. 🌟 Aktivitas positif\n5. ❓ Pertanyaan umum (FAQ)\n\nMau tanya apa hari ini?",
         'topic'    => 'greeting'],

        // Indikasi menyakiti diri / putus asa berat
        ['pattern' => '/menyakiti diri|self harm|melukai diri|bunuh diri|mengakhiri hidup|pengen mati|capek hidup|nggak kuat lagi|pengen nyerah|hidup gak berguna|mau mati aja/i',
        'response' => "Aku dengar kamu sedang merasa sangat berat sekarang, dan aku ingin kamu tahu perasaanmu itu valid. 💙\n\nTapi untuk hal seberat ini, penting banget kamu bicara langsung dengan orang yang bisa benar-benar membantu — bukan hanya chatbot.\n\n**Segera hubungi:**\n👩‍🏫 Guru BK di sekolah (bisa temui langsung besok atau hari ini)\n👨‍👩‍👧 Orang tua atau wali yang kamu percaya\n📞 Layanan Sehat Jiwa Kemenkes: **119 ext 8**\n\nKamu tidak sendirian, dan selalu ada orang yang siap mendengarkan dan membantu kamu. Boleh cerita sedikit, apa yang bikin kamu merasa seberat ini?",
        'topic'    => 'krisis'],

        // Bullying / perundungan
        ['pattern' => '/bully|bullying|dibully|diejek|dikucilkan|perundungan|dijahili|diganggu terus/i',
        'response' => "😔 **Kalau Kamu Mengalami Bullying:**\n\n• Kamu **tidak salah** dan tidak sendirian menghadapi ini\n• **Simpan bukti** jika ada (chat, foto, catatan kejadian)\n• **Ceritakan ke orang dewasa** yang kamu percaya — guru BK, wali kelas, atau orang tua\n• **Jangan balas dengan kekerasan**, itu bisa memperburuk situasi\n• Sekolah punya kewajiban melindungi kamu dari perundungan\n\n> Ini masalah serius. Aku sarankan kamu segera cerita ke Guru BK ya, mereka bisa bantu tindak lanjuti. 💙",
        'topic'    => 'bullying'],

        // Masalah pertemanan
        ['pattern' => '/teman|sahabat|musuhan|berantem sama teman|konflik pertemanan|dikhianati teman/i',
        'response' => "🤝 **Menghadapi Masalah Pertemanan:**\n\n• Beri diri kamu waktu untuk tenang sebelum bicara ke temanmu\n• Coba komunikasikan perasaanmu secara jujur & tanpa menyalahkan\n• Dengarkan juga sudut pandang temanmu\n• Nggak semua pertemanan harus dipertahankan — itu nggak apa-apa\n• Kalau situasinya berat, ceritakan ke guru BK atau orang dewasa yang kamu percaya\n\nSetiap orang pernah mengalami konflik pertemanan, kamu nggak sendirian kok 😊",
        'topic'    => 'pertemanan'],

        // Masalah keluarga
        ['pattern' => '/keluarga|orang tua|ayah|ibu|dimarahi|berantem sama ortu|kabur dari rumah/i',
        'response' => "🏠 **Menghadapi Masalah Keluarga:**\n\n• Perasaanmu itu wajar, banyak remaja mengalami hal serupa\n• Coba cari waktu yang tenang untuk bicara dari hati ke hati\n• Kalau sulit bicara langsung, coba tulis dulu apa yang ingin kamu sampaikan\n• Ingat, orang tua juga manusia yang bisa salah dan sedang belajar\n• Kalau situasinya membuatmu tidak aman, segera cerita ke guru BK\n\nKamu boleh cerita lebih lanjut kalau mau 💙",
        'topic'    => 'keluarga'],

        // Kepercayaan diri
        ['pattern' => '/percaya diri|minder|insecure|tidak berharga|gagal terus|ngerasa jelek|ngerasa bodoh/i',
        'response' => "🌱 **Membangun Kepercayaan Diri:**\n\n• Catat 3 hal baik tentang dirimu setiap hari, sekecil apapun\n• Bandingkan dirimu hanya dengan versi dirimu kemarin, bukan orang lain\n• Fokus pada progres, bukan kesempurnaan\n• Kelilingi diri dengan orang yang mendukungmu\n• Ingat: kegagalan adalah bagian dari proses belajar, bukan bukti kamu tidak berharga\n\nKamu lebih berharga dari yang kamu kira 💜",
        'topic'    => 'percaya_diri'],

        // Kecanduan gadget
        ['pattern' => '/kecanduan|adiksi|susah berhenti|tidak bisa lepas|terus pegang/i',
        'response' => "🔄 **Mengatasi Kecanduan Gadget:**\n\n1. **Kenali pemicunya** – Kapan kamu cenderung mengambil HP?\n2. **Buat jadwal screen-free** – misalnya saat makan dan belajar\n3. **Cari aktivitas pengganti** – olahraga, hobi, atau ngobrol langsung\n4. **Aktifkan mode grayscale** – layar abu-abu mengurangi daya tarik\n5. **Bicara dengan orang tua atau guru BK** jika sudah sangat mengganggu\n\nKamu bisa mulai dari langkah kecil dulu ya! 💪",
        'topic'    => 'gadget'],

        // Gadget sehat
        ['pattern' => '/gadget|hp|handphone|smartphone|layar|screen|media sosial|sosmed|tiktok|instagram|youtube/i',
        'response' => "📱 **Tips Penggunaan Gadget Sehat:**\n\n• Batasi screen time maksimal **2-3 jam/hari** di luar keperluan belajar\n• Terapkan aturan **no gadget 1 jam sebelum tidur**\n• Gunakan fitur **Digital Wellbeing** untuk pantau penggunaan\n• Istirahatkan mata setiap 20 menit (aturan 20-20-20)\n• Matikan notifikasi media sosial saat belajar\n\nApa yang ingin kamu tahu lebih lanjut?",
        'topic'    => 'gadget'],

        // FOMO / bandingan sosial media
        ['pattern' => '/iri|bandingin diri|fomo|ketinggalan|standar sosmed|hidup orang lain lebih bagus/i',
        'response' => "📵 **Mengatasi FOMO & Perbandingan Sosial Media:**\n\n• Ingat, orang biasanya hanya memposting momen terbaiknya, bukan seluruh hidupnya\n• Coba **unfollow/mute** akun yang bikin kamu insecure\n• Batasi waktu scrolling, gantikan dengan aktivitas nyata\n• Fokus pada hal yang bisa kamu syukuri hari ini\n• Sadari bahwa validasi diri tidak perlu datang dari likes/komentar\n\nHidupmu nggak harus terlihat sempurna di sosmed untuk berharga 🌸",
        'topic'    => 'fomo'],

        // Tidur
        ['pattern' => '/tidur|insomnia|begadang|susah tidur|ngantuk/i',
        'response' => "😴 **Tips Tidur yang Berkualitas:**\n\n• Tidur **7-9 jam** setiap malam untuk remaja\n• Hindari gadget minimal **1 jam sebelum tidur** (cahaya biru ganggu melatonin)\n• Buat rutinitas tidur yang konsisten\n• Kamar tidur = zona bebas gadget\n• Hindari kafein sore/malam hari\n\nTidur cukup sangat penting untuk kesehatan mental dan belajar! 🌙",
        'topic'    => 'kesehatan'],

        // Stres & kesehatan mental
        ['pattern' => '/stres|stress|cemas|anxiety|takut|khawatir|sedih|depresi|mental|psikolog/i',
        'response' => "🧠 **Menjaga Kesehatan Psikososial:**\n\n• **Bicara** dengan orang yang kamu percaya\n• **Tulis jurnal** untuk ekspresikan perasaan\n• **Olahraga** minimal 30 menit/hari – terbukti kurangi stres\n• **Pernapasan dalam** – tarik napas 4 detik, tahan 4, hembuskan 6\n• **Batasi berita negatif** di media sosial\n\n> Jika kamu merasa sangat tertekan, jangan ragu konsultasi dengan guru BK atau orang tua ya! 💙",
        'topic'    => 'kesehatan'],

        // Manajemen waktu
        ['pattern' => '/waktu|jadwal|produktif|belajar|pr|tugas|deadline|manajemen/i',
        'response' => "⏰ **Manajemen Waktu untuk Pelajar:**\n\n**Teknik Pomodoro:**\n• Belajar 25 menit → istirahat 5 menit\n• Setelah 4 sesi, istirahat 15-30 menit\n\n**Tips lainnya:**\n• Buat to-do list malam hari untuk esok\n• Prioritaskan tugas dengan metode **Eisenhower Matrix**\n• Tetapkan jam belajar yang sama setiap hari\n• Matikan notifikasi HP saat belajar\n• Beri reward kecil setelah menyelesaikan tugas\n\nMau tahu lebih detail tentang teknik belajar tertentu? 📚",
        'topic'    => 'waktu'],

        // Aktivitas positif
        ['pattern' => '/aktivitas|olahraga|hobi|kegiatan|positif|bosan|jenuh/i',
        'response' => "🌟 **Aktivitas Positif yang Bisa Dicoba:**\n\n🏃 **Fisik:** jogging, bersepeda, renang, yoga\n🎨 **Kreatif:** menggambar, musik, memasak, kerajinan\n📖 **Intelektual:** baca buku, belajar bahasa baru, koding\n👥 **Sosial:** bergabung komunitas, volunteer, bantu teman\n🌿 **Relaksasi:** meditasi, berkebun, jalan-jalan di alam\n\nAktivitas positif bisa mengurangi ketergantungan pada gadget secara alami! Coba pilih satu yang menarik bagimu 😊",
        'topic'    => 'aktivitas'],

        // SDQ
        ['pattern' => '/sdq|strength|difficulties|kesulitan|kekuatan/i',
        'response' => "📋 **SDQ (Strengths and Difficulties Questionnaire):**\n\nSDQ adalah alat skrining perilaku yang mengukur:\n• **Emosi** – kecemasan, depresi\n• **Perilaku** – kenakalan, agresivitas\n• **Hiperaktivitas** – konsentrasi, impulsivitas\n• **Teman sebaya** – kemampuan bersosialisasi\n• **Perilaku prososial** – empati, kepedulian\n\nSkor total 0-15 = Normal, 16-19 = Borderline, 20-40 = Perlu perhatian\n\nSudah mengisi kuesioner SDQ? Cek hasilnya di menu Riwayat! 📊",
        'topic'    => 'kuesioner'],

        // PSC-17
        ['pattern' => '/psc|pediatric|symptom|gejala/i',
        'response' => "📋 **PSC-17 (Pediatric Symptom Checklist):**\n\nPSC-17 mengukur masalah psikososial pada anak/remaja dalam 3 subskala:\n• **Internalizing** – masalah emosi (sedih, cemas)\n• **Attention** – masalah perhatian dan konsentrasi\n• **Externalizing** – masalah perilaku (agresif)\n\nSkor total ≥15 atau salah satu subskala tinggi = perlu perhatian lebih\n\nKuesioner ini membantu mendeteksi masalah sejak dini! 🔍",
        'topic'    => 'kuesioner'],

        // SAS-SV
        ['pattern' => '/sas|sassv|smartphone addiction|kecanduan hp/i',
        'response' => "📱 **SAS-SV (Smartphone Addiction Scale - Short Version):**\n\nAlat ukur kecanduan smartphone dengan 10 pertanyaan. Mengukur seberapa besar smartphone mengganggu kehidupan sehari-hari.\n\n**Cutoff skor:**\n• Laki-laki: ≥31 = indikasi kecanduan\n• Perempuan: ≥33 = indikasi kecanduan\n\nPenting diketahui lebih awal agar bisa ditangani! 💪",
        'topic'    => 'kuesioner'],

        // Hasil
        ['pattern' => '/hasil|skor|nilai|kuesioner|tes/i',
        'response' => "📊 **Tentang Hasil Kuesionermu:**\n\nHasil kuesioner Psycora menggabungkan 3 instrumen:\n• **SDQ** – kesehatan mental\n• **PSC-17** – gejala psikososial\n• **SAS-SV** – adiksi smartphone\n\n**Level Risiko:**\n🟢 **Rendah** – kondisi baik, pertahankan!\n🟡 **Sedang** – perlu perhatian, konsultasi guru BK\n\nKamu bisa lihat hasil lengkap di menu **Riwayat** ya! 📋",
        'topic'    => 'kuesioner'],

        // Privasi & keamanan data
        ['pattern' => '/siapa yang lihat|privasi|rahasia|aman (tidak|nggak|gak)|data saya|bocor/i',
        'response' => "🔒 **Privasi & Keamanan Data Kamu:**\n\n• Jawaban kuesionermu **hanya bisa dilihat oleh Guru BK** yang berwenang\n• Teman sekelas atau pihak luar **tidak punya akses** ke hasil kuesionermu\n• Data disimpan aman dan digunakan murni untuk membantu proses konseling\n• Isi kuesioner dengan jujur ya, karena kerahasiaanmu terjamin\n\nSemakin jujur kamu menjawab, semakin tepat bantuan yang bisa diberikan 💙",
        'topic'    => 'privasi'],

        // Cara pakai aplikasi
        ['pattern' => '/cara isi kuesioner|kapan isi lagi|cara pakai|gimana caranya|cara menggunakan/i',
        'response' => "📱 **Cara Menggunakan Psycora:**\n\n1. Buka menu **Kuesioner** di dashboard kamu\n2. Isi 3 instrumen (SDQ, PSC-17, SAS-SV) secara jujur\n3. Setelah selesai, hasil otomatis tersimpan di menu **Riwayat**\n4. Guru BK akan meninjau hasilmu dan memberi rekomendasi jika diperlukan\n5. Kamu bisa mengisi ulang kuesioner secara berkala sesuai jadwal dari sekolah\n\nAda bagian tertentu yang masih membingungkan? 😊",
        'topic'    => 'penggunaan'],

        // Guru BK
        ['pattern' => '/guru bk|konselor|konseling|curhat/i',
        'response' => "👩‍🏫 **Kapan Harus ke Guru BK?**\n\nJangan ragu konsultasi dengan guru BK jika:\n• Merasa sangat stres atau tertekan\n• Punya masalah dengan teman atau keluarga\n• Nilai akademis turun drastis\n• Merasa tidak termotivasi berkepanjangan\n• Hasil kuesioner menunjukkan risiko sedang/tinggi\n\nGuru BK adalah sahabatmu, bukan hanya untuk yang \"bermasalah\"! Semua orang butuh dukungan 💙",
        'topic'    => 'konseling'],

        // Terima kasih
        ['pattern' => '/terima kasih|makasih|thanks|tq|oke|ok|mantap|bagus|keren/i',
        'response' => "Sama-sama! 😊 Senang bisa membantu. Jika ada pertanyaan lain seputar kesehatan psikososial atau penggunaan gadget, jangan sungkan tanya ya!\n\nIngat: **kesehatan mentalmu sama pentingnya dengan kesehatan fisik!** 💙",
        'topic'    => 'general'],

        // Gadget sehat
        ['pattern' => '/gadget|hp|handphone|smartphone|layar|screen|media sosial|sosmed|tiktok|instagram|youtube/i',
         'response' => "📱 **Tips Penggunaan Gadget Sehat:**\n\n• Batasi screen time maksimal **2-3 jam/hari** di luar keperluan belajar\n• Terapkan aturan **no gadget 1 jam sebelum tidur**\n• Gunakan fitur **Digital Wellbeing** untuk pantau penggunaan\n• Istirahatkan mata setiap 20 menit (aturan 20-20-20)\n• Matikan notifikasi media sosial saat belajar\n\nApa yang ingin kamu tahu lebih lanjut?",
         'topic'    => 'gadget'],

        // Kecanduan gadget
        ['pattern' => '/kecanduan|adiksi|susah berhenti|tidak bisa lepas|terus pegang/i',
         'response' => "🔄 **Mengatasi Kecanduan Gadget:**\n\n1. **Kenali pemicunya** – Kapan kamu cenderung mengambil HP?\n2. **Buat jadwal screen-free** – misalnya saat makan dan belajar\n3. **Cari aktivitas pengganti** – olahraga, hobi, atau ngobrol langsung\n4. **Aktifkan mode grayscale** – layar abu-abu mengurangi daya tarik\n5. **Bicara dengan orang tua atau guru BK** jika sudah sangat mengganggu\n\nKamu bisa mulai dari langkah kecil dulu ya! 💪",
         'topic'    => 'gadget'],

        // Tidur
        ['pattern' => '/tidur|insomnia|begadang|susah tidur|ngantuk/i',
         'response' => "😴 **Tips Tidur yang Berkualitas:**\n\n• Tidur **7-9 jam** setiap malam untuk remaja\n• Hindari gadget minimal **1 jam sebelum tidur** (cahaya biru ganggu melatonin)\n• Buat rutinitas tidur yang konsisten\n• Kamar tidur = zona bebas gadget\n• Hindari kafein sore/malam hari\n\nTidur cukup sangat penting untuk kesehatan mental dan belajar! 🌙",
         'topic'    => 'kesehatan'],

        // Stres & kesehatan mental
        ['pattern' => '/stres|stress|cemas|anxiety|takut|khawatir|sedih|depresi|mental|psikososial|psikolog/i',
         'response' => "🧠 **Menjaga Kesehatan Psikososial:**\n\n• **Bicara** dengan orang yang kamu percaya\n• **Tulis jurnal** untuk ekspresikan perasaan\n• **Olahraga** minimal 30 menit/hari – terbukti kurangi stres\n• **Pernapasan dalam** – tarik napas 4 detik, tahan 4, hembuskan 6\n• **Batasi berita negatif** di media sosial\n\n> Jika kamu merasa sangat tertekan, jangan ragu konsultasi dengan guru BK atau orang tua ya! 💙",
         'topic'    => 'kesehatan'],

        // Manajemen waktu
        ['pattern' => '/waktu|jadwal|produktif|belajar|pr|tugas|deadline|manajemen/i',
         'response' => "⏰ **Manajemen Waktu untuk Pelajar:**\n\n**Teknik Pomodoro:**\n• Belajar 25 menit → istirahat 5 menit\n• Setelah 4 sesi, istirahat 15-30 menit\n\n**Tips lainnya:**\n• Buat to-do list malam hari untuk esok\n• Prioritaskan tugas dengan metode **Eisenhower Matrix**\n• Tetapkan jam belajar yang sama setiap hari\n• Matikan notifikasi HP saat belajar\n• Beri reward kecil setelah menyelesaikan tugas\n\nMau tahu lebih detail tentang teknik belajar tertentu? 📚",
         'topic'    => 'waktu'],

        // Aktivitas positif
        ['pattern' => '/aktivitas|olahraga|hobi|kegiatan|positif|bosan|jenuh/i',
         'response' => "🌟 **Aktivitas Positif yang Bisa Dicoba:**\n\n🏃 **Fisik:** jogging, bersepeda, renang, yoga\n🎨 **Kreatif:** menggambar, musik, memasak, kerajinan\n📖 **Intelektual:** baca buku, belajar bahasa baru, koding\n👥 **Sosial:** bergabung komunitas, volunteer, bantu teman\n🌿 **Relaksasi:** meditasi, berkebun, jalan-jalan di alam\n\nAktivitas positif bisa mengurangi ketergantungan pada gadget secara alami! Coba pilih satu yang menarik bagimu 😊",
         'topic'    => 'aktivitas'],

        // SDQ
        ['pattern' => '/sdq|strength|difficulties|kesulitan|kekuatan/i',
         'response' => "📋 **SDQ (Strengths and Difficulties Questionnaire):**\n\nSDQ adalah alat skrining perilaku yang mengukur:\n• **Emosi** – kecemasan, depresi\n• **Perilaku** – kenakalan, agresivitas\n• **Hiperaktivitas** – konsentrasi, impulsivitas\n• **Teman sebaya** – kemampuan bersosialisasi\n• **Perilaku prososial** – empati, kepedulian\n\nSkor total 0-15 = Normal, 16-19 = Borderline, 20-40 = Perlu perhatian\n\nSudah mengisi kuesioner SDQ? Cek hasilnya di menu Riwayat! 📊",
         'topic'    => 'kuesioner'],

        // PSC-17
        ['pattern' => '/psc|pediatric|symptom|gejala/i',
         'response' => "📋 **PSC-17 (Pediatric Symptom Checklist):**\n\nPSC-17 mengukur masalah psikososial pada anak/remaja dalam 3 subskala:\n• **Internalizing** – masalah emosi (sedih, cemas)\n• **Attention** – masalah perhatian dan konsentrasi\n• **Externalizing** – masalah perilaku (agresif)\n\nSkor total ≥15 atau salah satu subskala tinggi = perlu perhatian lebih\n\nKuesioner ini membantu mendeteksi masalah sejak dini! 🔍",
         'topic'    => 'kuesioner'],

        // SAS-SV
        ['pattern' => '/sas|sassv|smartphone addiction|kecanduan hp/i',
         'response' => "📱 **SAS-SV (Smartphone Addiction Scale - Short Version):**\n\nAlat ukur kecanduan smartphone dengan 10 pertanyaan. Mengukur seberapa besar smartphone mengganggu kehidupan sehari-hari.\n\n**Cutoff skor:**\n• Laki-laki: ≥31 = indikasi kecanduan\n• Perempuan: ≥33 = indikasi kecanduan\n\nPenting diketahui lebih awal agar bisa ditangani! 💪",
         'topic'    => 'kuesioner'],

        // Hasil
        ['pattern' => '/hasil|skor|nilai|kuesioner|kuesioner|tes/i',
         'response' => "📊 **Tentang Hasil Kuesionermu:**\n\nHasil kuesioner Psycora menggabungkan 3 instrumen:\n• **SDQ** – kesehatan mental\n• **PSC-17** – gejala psikososial\n• **SAS-SV** – adiksi smartphone\n\n**Level Risiko:**\n🟢 **Rendah** – kondisi baik, pertahankan!\n🟡 **Sedang** – perlu perhatian, edukasi aktif\n🔴 **Tinggi** – konsultasi guru BK\n🚨 **Sangat Tinggi** – rujuk profesional\n\nKamu bisa lihat hasil lengkap di menu **Riwayat** ya! 📋\",",
         'topic'    => 'kuesioner'],

        // Guru BK
        ['pattern' => '/guru bk|konselor|konseling|curhat/i',
         'response' => "👩‍🏫 **Kapan Harus ke Guru BK?**\n\nJangan ragu konsultasi dengan guru BK jika:\n• Merasa sangat stres atau tertekan\n• Punya masalah dengan teman atau keluarga\n• Nilai akademis turun drastis\n• Merasa tidak termotivasi berkepanjangan\n• Hasil kuesioner menunjukkan risiko sedang/tinggi\n\nGuru BK adalah sahabatmu, bukan hanya untuk yang \"bermasalah\"! Semua orang butuh dukungan 💙",
         'topic'    => 'konseling'],

        // Terima kasih
        ['pattern' => '/terima kasih|makasih|thanks|tq|oke|ok|mantap|bagus|keren/i',
         'response' => "Sama-sama! 😊 Senang bisa membantu. Jika ada pertanyaan lain seputar kesehatan psikososial atau penggunaan gadget, jangan sungkan tanya ya!\n\nIngat: **kesehatan mentalmu sama pentingnya dengan kesehatan fisik!** 💙",
         'topic'    => 'general'],
    ];

    public function respond(string $message): array
    {
        $message = trim($message);

        foreach ($this->patterns as $item) {
            if (preg_match($item['pattern'], $message)) {
                return [
                    'response' => $item['response'],
                    'topic'    => $item['topic'],
                ];
            }
        }

        // Default fallback
        return [
            'response' => "Hmm, saya belum familiar dengan pertanyaan itu 🤔\n\nCoba tanyakan tentang:\n• **Penggunaan gadget sehat**\n• **Manajemen waktu belajar**\n• **Kesehatan psikososial**\n• **Aktivitas positif**\n• **Kuesioner SDQ / PSC-17 / SAS-SV**\n\nAtau ketik **halo** untuk melihat menu lengkap! 😊",
            'topic'    => 'unknown',
        ];
    }

    public function quickReplies(): array
    {
        return [
            '📱 Tips gadget sehat',
            '⏰ Manajemen waktu',
            '🧠 Kesehatan psikososial',
            '🌟 Aktivitas positif',
            '📋 Tentang kuesioner',
            '👩‍🏫 Kapan ke guru BK?',
        ];
    }
}
