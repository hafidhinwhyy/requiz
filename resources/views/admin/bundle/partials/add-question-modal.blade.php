<div class="modal fade" id="addQuestionModal-{{ $bundle->id }}" tabindex="-1"
    aria-labelledby="addQuestionModalLabel-{{ $bundle->id }}" aria-hidden="true">

    {{-- Kita hapus 'modal-dialog-scrollable' karena scroll akan kita atur manual di dalam body --}}
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="addQuestionModalLabel-{{ $bundle->id }}">
                    <i class="bi bi-plus-square-dotted me-2"></i>Tambah Soal ke Bundle:
                    <strong>{{ $bundle->name }}</strong>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('bundle.questions.add', $bundle) }}" method="POST">
                @csrf
                <div class="modal-body">
                    {{-- Toolbar untuk Filter dan Aksi --}}
                    <div class="p-3 bg-white rounded-3 mb-3 shadow-sm">
                        <div class="row align-items-center">
                            <div class="col-sm-8">
                                <input type="text" class="form-control question-search-input"
                                    placeholder="Cari soal di sini..."
                                    data-target-list="#question-list-{{ $bundle->id }}">
                            </div>
                            <div class="col-sm-4">
                                <div class="form-check form-switch pt-2 pt-sm-0 ps-sm-4">
                                    <input class="form-check-input select-all-questions" type="checkbox" role="switch"
                                        data-target-list="#question-list-{{ $bundle->id }}">
                                    <label class="form-check-label">Pilih Semua</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ============================================= --}}
                    {{-- MULAI: Area Scroll Manual --}}
                    {{-- ============================================= --}}
                    <div class="question-scroll-area" style="max-height: 45vh; overflow-y: auto;">
                        <div class="list-group question-list" id="question-list-{{ $bundle->id }}">
                            {{-- Hapus logika flag manual dan @if yang tidak perlu --}}
                            @forelse ($availableQuestions as $question)
                                <label class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <div>
                                            <input class="form-check-input me-3" type="checkbox" name="question_ids[]"
                                                value="{{ $question->id }}">
                                            <span class="fw-bold">{{ $question->question }}</span>
                                        </div>
                                        <span class="badge bg-info text-dark rounded-pill">{{ $question->type }}</span>
                                    </div>
                                </label>
                            @empty
                                <div class="text-center py-5">
                                    <h6 class="text-muted">Tidak ada soal baru untuk ditambahkan</h6>
                                    <p>Semua soal yang tersedia sudah ada di dalam bundle ini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    {{-- ============================================= --}}
                    {{-- SELESAI: Area Scroll Manual --}}
                    {{-- ============================================= --}}

                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <span class="text-muted selection-counter">0 soal terpilih</span>
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Tambahkan
                            Soal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JAVASCRIPT ANDA TETAP SAMA DAN TIDAK PERLU DIUBAH --}}
<script>
    // ... Kode JavaScript dari jawaban sebelumnya tidak perlu diubah ...
</script>
