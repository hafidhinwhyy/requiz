<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pelamar') }}
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Baris untuk Form Pencarian dan Tombol Export -->
                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div>
                            <a href="{{ route('admin.applicant.export', ['search' => request('search')]) }}"
                                class="btn btn-success">
                                Export Excel
                            </a>
                        </div>

                        <div class="d-flex align-items-center">
                            <form action="{{ route('applicant.index') }}" method="GET" class="d-flex">
                                <input type="text" class="form-control me-2" name="search" placeholder="Search.."
                                    value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">Cari</button>
                            </form>

                            <button type="button" class="btn btn-light ms-3" data-bs-toggle="modal"
                                data-bs-target="#filterModal">
                                <i class="bi bi-filter fs-4"></i>
                            </button>
                        </div>

                        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('applicant.index') }}" method="GET">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="filterModalLabel">Filter Options</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="statusFilter" class="form-label">Status</label>
                                                <select class="form-select" name="status" id="statusFilter">
                                                    <option value="">Semua Status</option>
                                                    <option value="Seleksi Administrasi"
                                                        {{ request('status') == 'Seleksi Administrasi' ? 'selected' : '' }}>
                                                        Seleksi Administrasi
                                                    </option>
                                                    <option value="Tidak Lolos Seleksi Administrasi"
                                                        {{ request('status') == 'Tidak Lolos Seleksi Administrasi' ? 'selected' : '' }}>
                                                        Tidak Lolos Seleksi Administrasi
                                                    </option>
                                                    <option value="Seleksi Tes Tulis"
                                                        {{ request('status') == 'Seleksi Tes Tulis' ? 'selected' : '' }}>
                                                        Seleksi Tes Tulis
                                                    </option>
                                                    <option value="Lolos Seleksi Tes Tulis"
                                                        {{ request('status') == 'Lolos Seleksi Tes Tulis' ? 'selected' : '' }}>
                                                        Lolos Seleksi Tes Tulis
                                                    </option>
                                                    <option value="Tidak Lolos Seleksi Tes Tulis"
                                                        {{ request('status') == 'Tidak Lolos Seleksi Tes Tulis' ? 'selected' : '' }}>
                                                        Tidak Lolos Seleksi Tes Tulis
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="positionFilter" class="form-label">Posisi</label>
                                                <select class="form-select" name="position" id="positionFilter">
                                                    <option value="">Semua Posisi</option>
                                                    {{-- Loop ini mengambil data $positions dari controller --}}
                                                    @foreach ($positions as $position)
                                                        <option value="{{ $position->id }}"
                                                            {{ request('position') == $position->id ? 'selected' : '' }}>
                                                            {{ $position->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <a href="{{ route('applicant.index') }}"
                                                class="btn btn-secondary">Reset</a>
                                            <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Selesai -->

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama</th>
                                    <th>Posisi</th>
                                    <th>Umur</th>
                                    <th>Pendidikan</th>
                                    <th>Jurusan</th>
                                    <th>CV</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($applicants as $applicant)
                                    <tr>
                                        <td>{{ ($applicants->currentPage() - 1) * $applicants->perPage() + $loop->iteration }}
                                        </td>
                                        <td>{{ $applicant->name }}</td>
                                        <td>{{ $applicant->position->name }}</td>
                                        <td>{{ $applicant->age }} tahun</td>
                                        <td>{{ $applicant->pendidikan }} -
                                            {{ $applicant->universitas }}</td>
                                        <td>{{ $applicant->jurusan }}</td>
                                        <td>
                                            @if ($applicant->cv_document)
                                                <a href="{{ asset('storage/' . $applicant->cv_document) }}"
                                                    target="_blank" class="btn btn-sm btn-outline-primary">Lihat
                                                    CV</a>
                                            @else
                                                <span class="text-muted">Tidak ada
                                                    CV</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                data-bs-target="#modalEdit{{ $applicant->id }}">Edit</a>
                                            <form action="/admin/applicant/{{ $applicant->id }}" method="post"
                                                class="d-inline">
                                                @method('delete')
                                                @csrf
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Data tidak ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{-- withQueryString() penting agar pencarian tidak hilang saat ganti halaman --}}
                            {{ $applicants->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
