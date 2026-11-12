<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuestionnaireResponse;
use App\Models\SdqAnswer;
use App\Models\Psc17Answer;
use App\Models\SassvAnswer;
use App\Services\ScoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionnaireController extends Controller
{
    public function __construct(private ScoringService $scoring) {}

    // Halaman intro kuesioner
    public function index()
    {
        // Ambil draf kuesioner yang belum selesai agar siswa bisa melanjutkan
        $draft = QuestionnaireResponse::where('user_id', Auth::id())
            ->where('status', 'draft')
            ->first();

        $existing = QuestionnaireResponse::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->latest('completed_at')
            ->first();

        $canStart = true;
        $nextAllowedAt = null;

        if ($existing && $existing->completed_at) {
            $nextAllowedAt = $existing->completed_at->copy()->addDays(7);
            if ($nextAllowedAt->isFuture()) {
                $canStart = false;
            }
        }

        return view('siswa.questionnaire.index', compact('existing', 'draft', 'canStart', 'nextAllowedAt'));
    }

    // ============================================================
    // SDQ – All questions on one page
    // ============================================================
    public function sdq(Request $request)
    {
        // Check if student is in cooldown
        $existing = QuestionnaireResponse::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->latest('completed_at')
            ->first();

        if ($existing && $existing->completed_at) {
            $nextAllowedAt = $existing->completed_at->copy()->addDays(7);
            if ($nextAllowedAt->isFuture()) {
                // Cooldown is active. Only allow resuming an existing draft.
                $draft = QuestionnaireResponse::where('user_id', Auth::id())
                    ->where('status', 'draft')
                    ->first();

                if (!$draft || $request->query('new') === 'true') {
                    return redirect()->route('siswa.questionnaire.index')
                        ->with('error', 'Anda hanya dapat mengisi kuesioner sekali dalam 7 hari. Anda dapat mengisi kembali pada ' . $nextAllowedAt->format('d F Y, H:i') . ' WIB.');
                }
            }
        }

        $questions = Question::where('type', 'sdq')->orderBy('number')->get();

        // Hapus draf lama jika siswa memilih untuk memulai kuesioner baru secara eksplisit
        if ($request->query('new') === 'true') {
            QuestionnaireResponse::where('user_id', Auth::id())
                ->where('status', 'draft')
                ->delete();
        }

        // Selalu buat atau ambil draf response baru jika belum ada
        $draft = QuestionnaireResponse::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 'draft'],
        );

        $saved = SdqAnswer::where('response_id', $draft->id)
            ->pluck('answer_value', 'question_number')
            ->toArray();

        return view('siswa.questionnaire.wizard', [
            'instrument'     => 'SDQ',
            'instrument_key' => 'sdq',
            'questions'      => $questions,
            'step'           => 1,
            'total'          => 3,
            'saved'          => $saved,
            'prev_route'     => route('siswa.questionnaire.index'),
            'options'        => [
                0 => 'Tidak Benar',
                1 => 'Agak Benar',
                2 => 'Benar',
            ],
        ]);
    }

    public function storeSdqStep(Request $request)
    {
        $response = QuestionnaireResponse::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 'draft'],
        );

        $answers = $request->input('answers', []);

        // Simpan jawaban yang diisi saat ini (boleh kosong/parsial)
        SdqAnswer::where('response_id', $response->id)->delete();
        foreach ($answers as $num => $val) {
            if ($val !== null && $val !== '') {
                SdqAnswer::create([
                    'response_id'     => $response->id,
                    'question_number' => $num,
                    'answer_value'    => (int) $val,
                ]);
            }
        }

        // Cek target redirect dari tab atau tombol kembali
        $nextTab = $request->input('next_tab');
        if ($nextTab === 'psc17') {
            return redirect()->route('siswa.questionnaire.psc17');
        } elseif ($nextTab === 'sassv') {
            return redirect()->route('siswa.questionnaire.sassv');
        } elseif ($nextTab === 'sdq') {
            return redirect()->route('siswa.questionnaire.sdq');
        } elseif ($nextTab === 'back') {
            return redirect()->route('siswa.questionnaire.index');
        }

        // Default: lanjut ke instrumen berikutnya (PSC-17)
        return redirect()->route('siswa.questionnaire.psc17');
    }

    // ============================================================
    // PSC-17 – All questions on one page
    // ============================================================
    public function psc17(Request $request)
    {
        $draft = QuestionnaireResponse::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 'draft'],
        );

        $questions = Question::where('type', 'psc17')->orderBy('number')->get();

        $saved = Psc17Answer::where('response_id', $draft->id)
            ->pluck('answer_value', 'question_number')
            ->toArray();

        return view('siswa.questionnaire.wizard', [
            'instrument'     => 'PSC-17',
            'instrument_key' => 'psc17',
            'questions'      => $questions,
            'step'           => 2,
            'total'          => 3,
            'saved'          => $saved,
            'prev_route'     => route('siswa.questionnaire.sdq'),
            'options'        => [
                0 => 'Tidak Pernah',
                1 => 'Kadang-kadang',
                2 => 'Sering',
            ],
        ]);
    }

    public function storePsc17Step(Request $request)
    {
        $response = QuestionnaireResponse::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 'draft'],
        );

        $answers = $request->input('answers', []);

        // Simpan jawaban yang diisi saat ini (boleh kosong/parsial)
        Psc17Answer::where('response_id', $response->id)->delete();
        foreach ($answers as $num => $val) {
            if ($val !== null && $val !== '') {
                Psc17Answer::create([
                    'response_id'     => $response->id,
                    'question_number' => $num,
                    'answer_value'    => (int) $val,
                ]);
            }
        }

        // Cek target redirect dari tab atau tombol kembali
        $nextTab = $request->input('next_tab');
        if ($nextTab === 'sdq') {
            return redirect()->route('siswa.questionnaire.sdq');
        } elseif ($nextTab === 'sassv') {
            return redirect()->route('siswa.questionnaire.sassv');
        } elseif ($nextTab === 'psc17') {
            return redirect()->route('siswa.questionnaire.psc17');
        } elseif ($nextTab === 'back') {
            return redirect()->route('siswa.questionnaire.sdq');
        }

        // Default: lanjut ke instrumen berikutnya (SAS-SV)
        return redirect()->route('siswa.questionnaire.sassv');
    }

    // ============================================================
    // SAS-SV – All questions on one page
    // ============================================================
    public function sassv(Request $request)
    {
        $draft = QuestionnaireResponse::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 'draft'],
        );

        $questions = Question::where('type', 'sassv')->orderBy('number')->get();

        $saved = SassvAnswer::where('response_id', $draft->id)
            ->pluck('answer_value', 'question_number')
            ->toArray();

        return view('siswa.questionnaire.wizard', [
            'instrument'     => 'SAS-SV',
            'instrument_key' => 'sassv',
            'questions'      => $questions,
            'step'           => 3,
            'total'          => 3,
            'saved'          => $saved,
            'prev_route'     => route('siswa.questionnaire.psc17'),
            'options'        => [
                1 => 'Sangat Tidak Setuju',
                2 => 'Tidak Setuju',
                3 => 'Netral',
                4 => 'Setuju',
                5 => 'Sangat Setuju',
                6 => 'Sangat Setuju Sekali',
            ],
        ]);
    }

    public function storeSassvStep(Request $request)
    {
        $response = QuestionnaireResponse::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 'draft'],
        );

        $answers = $request->input('answers', []);

        // Simpan jawaban yang diisi saat ini (boleh kosong/parsial)
        SassvAnswer::where('response_id', $response->id)->delete();
        foreach ($answers as $num => $val) {
            if ($val !== null && $val !== '') {
                SassvAnswer::create([
                    'response_id'     => $response->id,
                    'question_number' => $num,
                    'answer_value'    => (int) $val,
                ]);
            }
        }

        // Cek target redirect dari tab atau tombol kembali
        $nextTab = $request->input('next_tab');
        if ($nextTab === 'sdq') {
            return redirect()->route('siswa.questionnaire.sdq');
        } elseif ($nextTab === 'psc17') {
            return redirect()->route('siswa.questionnaire.psc17');
        } elseif ($nextTab === 'sassv') {
            return redirect()->route('siswa.questionnaire.sassv');
        } elseif ($nextTab === 'back') {
            return redirect()->route('siswa.questionnaire.psc17');
        }

        // Jika menekan tombol selesai kuesioner, validasi seluruh 52 pertanyaan dari ketiga instrumen
        $sdqQuestions = Question::where('type', 'sdq')->orderBy('number')->get();
        $pscQuestions = Question::where('type', 'psc17')->orderBy('number')->get();
        $sasQuestions = Question::where('type', 'sassv')->orderBy('number')->get();

        $sdqAnswers = SdqAnswer::where('response_id', $response->id)->pluck('answer_value', 'question_number')->toArray();
        $pscAnswers = Psc17Answer::where('response_id', $response->id)->pluck('answer_value', 'question_number')->toArray();
        $sasAnswers = SassvAnswer::where('response_id', $response->id)->pluck('answer_value', 'question_number')->toArray();

        // 1. Cek kelengkapan SDQ
        foreach ($sdqQuestions as $q) {
            if (!isset($sdqAnswers[$q->number])) {
                return redirect()->route('siswa.questionnaire.sdq')
                    ->withErrors(['answers' => "Mohon selesaikan pengisian. Pertanyaan nomor {$q->number} pada instrumen SDQ belum dijawab."])
                    ->withInput();
            }
        }

        // 2. Cek kelengkapan PSC-17
        foreach ($pscQuestions as $q) {
            if (!isset($pscAnswers[$q->number])) {
                return redirect()->route('siswa.questionnaire.psc17')
                    ->withErrors(['answers' => "Mohon selesaikan pengisian. Pertanyaan nomor {$q->number} pada instrumen PSC-17 belum dijawab."])
                    ->withInput();
            }
        }

        // 3. Cek kelengkapan SAS-SV
        foreach ($sasQuestions as $q) {
            if (!isset($sasAnswers[$q->number])) {
                return redirect()->route('siswa.questionnaire.sassv')
                    ->withErrors(['answers' => "Mohon selesaikan pengisian. Pertanyaan nomor {$q->number} pada instrumen SAS-SV belum dijawab."])
                    ->withInput();
            }
        }

        // Hitung skor & klasifikasi final jika semuanya lengkap
        $this->scoring->calculate($response);

        return redirect()->route('siswa.questionnaire.result', $response->id);
    }

    // Halaman hasil
    public function result($id)
    {
        $response = QuestionnaireResponse::with('riskClassification')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('siswa.questionnaire.result', compact('response'));
    }
}