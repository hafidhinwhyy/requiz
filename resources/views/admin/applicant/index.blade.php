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
                        <!-- Tombol Export Excel -->
                        <div>
                            {{-- Tombol ini mengarah ke route export dengan membawa parameter pencarian saat ini --}}
                            <a href="{{ route('admin.applicant.export', ['search' => request('search')]) }}"
                                class="btn btn-success">
                                Export Excel
                            </a>
                        </div>
                        <!-- Form Pencarian -->
                        <form action="{{ route('applicant.index') }}" method="GET" class="d-flex">
                            <input type="text" class="form-control me-2" name="search" placeholder="Search.."
                                value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">Cari</button>
                        </form>
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
