<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
                {{ __('Bundle') }}
            </h2>
            {{-- Tombol ini sekarang akan membuka modal --}}
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahBundle">
                Create Bundle
            </button>
        </div>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @foreach ($bundles as $bundle)
                        <div class="accordion mb-3" id="accordion-{{ $bundle->id }}">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-{{ $bundle->id }}" aria-expanded="false"
                                        aria-controls="collapse-{{ $bundle->id }}">
                                        <span class="fw-bold me-4">{{ $bundle->name }}</span>
                                    </button>
                                </h2>
                                <div id="collapse-{{ $bundle->id }}" class="accordion-collapse collapse"
                                    data-bs-parent="#parentAccordion"> {{-- Targetkan parent utama --}}
                                    <div class="accordion-body">
                                        <div class="mb-3">
                                            {{-- Tombol ini memicu modal edit --}}
                                            <a href="#" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editBundle{{ $bundle->id }}">Edit Bundle</a>

                                            {{-- Form Hapus --}}
                                            <form action="{{ route('bundle.destroy', $bundle->id) }}" method="post"
                                                class="d-inline">
                                                @method('delete')
                                                @csrf
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Apakah Anda Yakin ?')">Delete
                                                    Bundle</button>
                                            </form>
                                            {{-- Tombol lain --}}
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#addQuestionModal-{{ $bundle->id }}">
                                                + Tambah Soal ke Bundle
                                            </button>
                                        </div>
                                        {{-- Tabel Soal yang Sudah Ada --}}
                                        <table class="table table-sm table-striped mb-4">
                                            <thead>
                                                <tr>
                                                    <th>Soal</th>
                                                    <th>Tipe</th>
                                                    <th style="width: 10%;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($bundle->questions as $question)
                                                    <tr>

                                                        <td>{{ Str::limit($question->question, 80) }}</td>
                                                        <td><span class="badge bg-info">{{ $question->type }}</span>
                                                        </td>
                                                        <td>
                                                            <form
                                                                action="{{ route('bundle.questions.remove', ['bundle' => $bundle->id, 'question' => $question->id]) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm"
                                                                    onclick="return confirm('Yakin hapus soal ini dari bundle?')">Hapus</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted">Belum ada soal
                                                            di bundle ini.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Modal untuk mengedit bundle --}}
                        {{-- Pastikan ID modal unik untuk setiap bundle --}}
                        <div class="modal fade" id="editBundle{{ $bundle->id }}" tabindex="-1"
                            aria-labelledby="modalEditBundle{{ $bundle->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="modalEditBundle{{ $bundle->id }}">Edit
                                            Bundle</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('bundle.update', $bundle->id) }}" method="POST">
                                        @csrf
                                        @method('PUT') {{-- Gunakan method PUT untuk update --}}
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Nama Bundle</label>
                                                {{-- Isi value dengan data yang ada --}}
                                                <input type="text" class="form-control" name="name"
                                                    value="{{ old('name', $bundle->name) }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="description" class="form-label">Deskripsi (Opsional)</label>
                                                {{-- Isi textarea dengan data yang ada --}}
                                                <textarea class="form-control" name="description" rows="3">{{ old('description', $bundle->description) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="addQuestionModal-{{ $bundle->id }}" tabindex="-1"
                            aria-labelledby="addQuestionModalLabel-{{ $bundle->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg"> {{-- Modal lebih besar dan bisa di-scroll --}}
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addQuestionModalLabel-{{ $bundle->id }}">Pilih
                                            Soal untuk Bundle: {{ $bundle->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('bundle.questions.add', $bundle->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p class="text-muted">Pilih satu atau lebih soal di bawah ini. Soal yang
                                                sudah ada di dalam bundle tidak akan ditampilkan.</p>

                                            <div class="list-group">
                                                @forelse ($allQuestions as $question)
                                                    {{-- Tampilkan checkbox hanya jika soal belum ada di bundle --}}
                                                    @if (!$bundle->questions->contains($question))
                                                        <label class="list-group-item">
                                                            <input class="form-check-input me-2" type="checkbox"
                                                                name="question_ids[]" value="{{ $question->id }}">
                                                            {{ $question->question }}
                                                            <span
                                                                class="badge bg-info float-end">{{ $question->type }}</span>
                                                        </label>
                                                    @endif
                                                @empty
                                                    <p class="text-center">Semua soal sudah ada di dalam bundle ini
                                                        atau tidak ada soal yang tersedia.</p>
                                                @endforelse
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Tambahkan Soal
                                                Terpilih</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Modal untuk notifikasi sukses --}}
    @if (session('success'))
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center p-4">
                        <h5 class="text-success mb-3">✅ {{ session('success') }}</h5>
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Create Bundle -->
    <div class="modal fade" id="tambahBundle" tabindex="-1" aria-labelledby="modalTambahBundle"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalTambahBundle">Buat Bundle Baru</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Form mengarah ke route 'bundles.store' --}}
                    <form action="{{ route('bundle.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            {{-- Input Nama Bundle --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Bundle</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Input Deskripsi --}}
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi (Opsional)</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk menampilkan modal sukses secara otomatis --}}
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            });
        </script>
    @endif

</x-app-layout>
