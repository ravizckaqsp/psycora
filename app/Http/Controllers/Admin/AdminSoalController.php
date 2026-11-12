<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class AdminSoalController extends Controller
{
    public function index()
    {
        $sdqQuestions = Question::where('type', 'sdq')->orderBy('number')->get();
        $pscQuestions = Question::where('type', 'psc17')->orderBy('number')->get();
        $sassvQuestions = Question::where('type', 'sassv')->orderBy('number')->get();

        return view('admin.soal.index', compact('sdqQuestions', 'pscQuestions', 'sassvQuestions'));
    }

    public function edit($id)
    {
        $question = Question::findOrFail($id);
        return view('admin.soal.edit', compact('question'));
    }

    public function update(Request $request, $id)
    {
        $question = Question::findOrFail($id);

        $request->validate([
            'text' => 'required|string|max:1000',
            'subscale' => 'nullable|string|max:50',
            'reverse_scored' => 'required|boolean',
        ]);

        $question->update([
            'text' => $request->text,
            'subscale' => $request->subscale,
            'reverse_scored' => $request->reverse_scored,
        ]);

        return redirect()->route('admin.soal.index')->with('success', 'Butir soal kuesioner berhasil diperbarui.');
    }
}
