<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        Question::truncate();

        // SDQ - 25 pertanyaan
        $sdq = [
            [1,  'Saya berusaha bersikap baik kepada orang lain. Saya peduli dengan perasaan mereka', 'prosocial', false],
            [2,  'Saya gelisah, saya tidak bisa diam untuk waktu yang lama', 'hyperactivity', false],
            [3,  'Saya sering sakit kepala, sakit perut atau macam-macam sakit lainnya', 'emotional', false],
            [4,  'Kalau saya memiliki mainan, CD, atau makanan, saya biasanya berbagi dengan orang lain', 'prosocial', false],
            [5,  'Saya menjadi sangat marah dan sering tidak dapat mengendalikan kemarahan saya', 'conduct', false],
            [6,  'Saya lebih suka sendiri daripada bersama dengan orang-orang seusia saya', 'peer', false],
            [7,  'Saya biasanya melakukan apa yang diperintahkan oleh orang lain', 'conduct', true],
            [8,  'Saya banyak merasa cemas atau khawatir tentang apapun', 'emotional', false],
            [9,  'Saya selalu siap menolong jika ada orang yang terluka, kecewa, atau merasa sakit', 'prosocial', false],
            [10, 'Bila sedang gelisah atau cemas, badan saya sering bergerak-gerak tanpa dapat saya kendalikan', 'hyperactivity', false],
            [11, 'Saya mempunyai satu orang teman baik atau lebih', 'peer', true],
            [12, 'Saya sering bertengkar dengan orang lain. Saya dapat membuat orang lain melakukan apa yang saya inginkan', 'conduct', false],
            [13, 'Saya sering merasa tidak bahagia, sedih, atau menangis', 'emotional', false],
            [14, 'Orang-orang seusia saya pada umumnya menyukai saya', 'peer', true],
            [15, 'Perhatian saya mudah teralihkan, saya sulit untuk berkonsentrasi', 'hyperactivity', false],
            [16, 'Saya merasa gugup dalam situasi baru, saya mudah kehilangan rasa percaya diri', 'emotional', false],
            [17, 'Saya bersikap baik terhadap anak-anak yang lebih muda dari saya', 'prosocial', false],
            [18, 'Saya sering dituduh berbohong atau berbuat curang', 'conduct', false],
            [19, 'Anak-anak atau remaja lain sering mengejek atau mengganggu saya', 'peer', false],
            [20, 'Saya sering menawarkan diri untuk membantu orang lain (orang tua, guru, anak-anak lain)', 'prosocial', false],
            [21, 'Saya berpikir dahulu sebelum melakukan sesuatu', 'hyperactivity', true],
            [22, 'Saya mengambil barang yang bukan milik saya dari rumah, sekolah, atau tempat lainnya', 'conduct', false],
            [23, 'Saya lebih mudah berteman dengan orang dewasa daripada dengan orang-orang seusia saya', 'peer', false],
            [24, 'Banyak yang saya takuti, saya mudah menjadi takut', 'emotional', false],
            [25, 'Saya menyelesaikan pekerjaan yang sedang saya lakukan. Saya mempunyai perhatian yang baik', 'hyperactivity', true],
        ];

        foreach ($sdq as [$num, $text, $subscale, $reverse]) {
            Question::create([
                'type' => 'sdq',
                'number' => $num,
                'text' => $text,
                'subscale' => $subscale,
                'reverse_scored' => $reverse,
            ]);
        }

        // PSC-17 - 17 pertanyaan
        $psc17 = [
            [1,  'Gelisah, tidak bisa tenang', 'attention'],
            [2,  'Merasa sedih', 'internalizing'],
            [3,  'Banyak melamun', 'attention'],
            [4,  'Menolak (tidak suka) berbagi', 'externalizing'],
            [5,  'Tidak memahami perasaan orang lain', 'externalizing'],
            [6,  'Putus asa', 'internalizing'],
            [7,  'Susah berkonsentrasi', 'attention'],
            [8,  'Bertengkar dengan anak lain', 'externalizing'],
            [9,  'Memandang rendah dirimu sendiri', 'internalizing'],
            [10, 'Menyalahkan orang lain untuk masalah yang terjadi', 'externalizing'],
            [11, 'Tampak murung', 'internalizing'],
            [12, 'Tidak menaati peraturan', 'externalizing'],
            [13, 'Bertindak seolah-olah digerakkan oleh mesin (tanpa berpikir)', 'attention'],
            [14, 'Mengganggu anak-anak lain', 'externalizing'],
            [15, 'Mencemaskan banyak hal', 'internalizing'],
            [16, 'Mengambil barang yang bukan miliknya', 'externalizing'],
            [17, 'Perhatian mudah teralihkan', 'attention'],
        ];

        foreach ($psc17 as [$num, $text, $subscale]) {
            Question::create([
                'type' => 'psc17',
                'number' => $num,
                'text' => $text,
                'subscale' => $subscale,
                'reverse_scored' => false,
            ]);
        }

        // SAS-SV - 10 pertanyaan
        $sassv = [
            [1,  'Penggunaan smartphone menghalangi pekerjaan sekolah saya'],
            [2,  'Saya merasa kesulitan berkonsentrasi di kelas, ketika mengerjakan tugas, atau bekerja karena smartphone'],
            [3,  'Saya merasakan sakit pada pergelangan tangan atau bagian belakang leher saat menggunakan smartphone'],
            [4,  'Saya tidak akan tahan jika tidak memiliki smartphone'],
            [5,  'Saya merasa tidak sabar dan gelisah ketika saya tidak memegang smartphone'],
            [6,  'Smartphone selalu ada di dalam pikiran saya meskipun saya tidak menggunakannya'],
            [7,  'Saya tidak akan pernah berhenti menggunakan smartphone bahkan ketika aktivitas sehari-hari saya sudah terganggu'],
            [8,  'Saya terus-menerus memeriksa smartphone saya agar tidak melewatkan percakapan di media sosial'],
            [9,  'Saya menggunakan smartphone lebih lama dari yang saya rencanakan'],
            [10, 'Orang-orang di sekitar saya mengatakan bahwa saya terlalu banyak menggunakan smartphone'],
        ];

        foreach ($sassv as [$num, $text]) {
            Question::create([
                'type' => 'sassv',
                'number' => $num,
                'text' => $text,
                'subscale' => null,
                'reverse_scored' => false,
            ]);
        }
    }
}