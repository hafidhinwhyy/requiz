<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuestionBundle;
use App\Models\Question; // <-- Tambahkan ini

class QuestionBundleController extends Controller
{
    /**
     * Menampilkan daftar semua bundle.
     */
    public function index()
    {
        // Gunakan withCount untuk efisiensi, kita tidak perlu memuat semua soal di sini.
        $bundles = QuestionBundle::withCount('questions')->latest()->get();
        $allQuestions = Question::all();

        return view('admin.bundle.index', compact('bundles', 'allQuestions'));
    }

    /**
     * Mengambil daftar soal dalam bentuk partial view untuk AJAX.
     */
    public function fetchQuestions(QuestionBundle $bundle)
    {
        // Lakukan paginasi seperti biasa
        $questionsInBundle = $bundle->questions()->paginate(10, ['*'], 'page', request('page'));

        // Kembalikan hanya view partial, bukan layout lengkap
        return view('admin.bundle.partials.question-table', compact('bundle', 'questionsInBundle'));
    }

    /**
     * Menyimpan bundle baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input dari form
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // 2. Buat data baru menggunakan model
        QuestionBundle::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // 3. Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('bundle.index')
                         ->with('success', 'Bundle baru berhasil dibuat!');
    }

    public function update(Request $request, QuestionBundle $bundle)
    {
        // 1. Validasi input dari form edit
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // 2. Update data pada model yang ditemukan
        $bundle->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // 3. Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('bundle.index')
                         ->with('success', 'Bundle berhasil diperbarui!');
    }

    // Anda juga memerlukan metode destroy jika ingin fungsionalitas hapus bekerja
    public function destroy(QuestionBundle $bundle)
    {
        $bundle->delete();
        return redirect()->route('bundle.index')
                         ->with('success', 'Bundle berhasil dihapus!');
    }

    public function addQuestion(Request $request, QuestionBundle $bundle)
    {
        // 1. Validasi input, pastikan question_ids adalah array dan setiap isinya ada di tabel questions
        $request->validate([
            'question_ids' => 'required|array', // Pastikan ini adalah array
            'question_ids.*' => 'exists:questions,id', // Validasi setiap item di dalam array
        ]);

        // 2. Ambil ID soal yang sudah ada di bundle untuk mencegah duplikasi
        $existingQuestionIds = $bundle->questions()->pluck('questions.id')->toArray();

        // 3. Filter ID soal yang dikirim dari form, hanya ambil yang belum ada di bundle
        $newQuestionIds = array_diff($request->question_ids, $existingQuestionIds);

        // 4. Jika ada soal baru yang valid, tambahkan ke bundle menggunakan attach()
        if (!empty($newQuestionIds)) {
            $bundle->questions()->attach($newQuestionIds);
            return redirect()->back()->with('success', count($newQuestionIds) . ' soal berhasil ditambahkan!');
        }

        // 5. Jika tidak ada soal baru yang ditambahkan (mungkin karena sudah ada semua)
        return redirect()->back()->with('info', 'Tidak ada soal baru yang ditambahkan. Mungkin semua sudah ada di dalam bundle.');
    }

    /**
     * Menghapus soal dari bundle.
     */
    public function removeQuestion(QuestionBundle $bundle, Question $question)
    {
        // Gunakan detach() untuk menghapus relasi dari tabel pivot
        $bundle->questions()->detach($question->id);

        return redirect()->back()->with('success', 'Soal berhasil dihapus dari bundle!');
    }
}
