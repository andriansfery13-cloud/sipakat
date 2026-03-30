<x-app-layout>
    <x-slot name="title">Data Pegawai</x-slot>
    <x-slot name="subtitle">Kelola data pegawai perusahaan</x-slot>

    <div class="animate-fade-in-up">
        <!-- Actions Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <form method="GET" action="{{ route('employees.index') }}" class="flex flex-1 gap-2 max-w-lg">
                <div class="relative flex-1">
                    <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NPWP, NIK..."
                        class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <select name="status" class="border border-slate-200 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="tetap" {{ request('status') === 'tetap' ? 'selected' : '' }}>Tetap</option>
                    <option value="tidak_tetap" {{ request('status') === 'tidak_tetap' ? 'selected' : '' }}>Tidak Tetap</option>
                </select>
                <button type="submit" class="btn-primary text-white px-4 py-2.5 rounded-xl text-sm font-medium">Cari</button>
            </form>
            <div class="flex flex-wrap gap-2 justify-end w-full sm:w-auto">
                <a href="{{ route('employees.import') }}" class="btn-primary-outline border border-indigo-600 text-indigo-600 hover:bg-indigo-50 px-4 py-2.5 rounded-xl text-sm font-medium inline-flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    Import Excel
                </a>
                <a href="{{ route('employees.create') }}" class="btn-primary text-white px-5 py-2.5 rounded-xl text-sm font-medium inline-flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Pegawai
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="data-table w-full text-sm">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left">No</th>
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">NPWP</th>
                            <th class="px-4 py-3 text-left">NIK</th>
                            <th class="px-4 py-3 text-left">PTKP</th>
                            <th class="px-4 py-3 text-left">Jabatan</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($employees as $i => $emp)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-slate-500">{{ $employees->firstItem() + $i }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center flex-shrink-0">
                                            <span class="text-white text-xs font-bold">{{ strtoupper(substr($emp->name, 0, 1)) }}</span>
                                        </div>
                                        <span class="font-medium text-slate-800">{{ $emp->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-slate-600 font-mono text-xs">{{ $emp->formatted_npwp }}</td>
                                <td class="px-4 py-3 text-slate-600 font-mono text-xs">{{ $emp->nik ?? '-' }}</td>
                                <td class="px-4 py-3"><span class="badge bg-blue-100 text-blue-700">{{ $emp->ptkp_status }}</span></td>
                                <td class="px-4 py-3 text-slate-600">{{ $emp->position ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @if($emp->employee_status === 'tetap')
                                        <span class="badge bg-emerald-100 text-emerald-700">Tetap</span>
                                    @else
                                        <span class="badge bg-amber-100 text-amber-700">Tidak Tetap</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('employees.edit', $emp) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('employees.destroy', $emp) }}" onsubmit="return confirm('Yakin ingin menghapus pegawai ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center">
                                    <svg class="w-16 h-16 mx-auto mb-4 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <p class="text-slate-400 text-sm">Belum ada data pegawai</p>
                                    <a href="{{ route('employees.create') }}" class="btn-primary text-white px-4 py-2 rounded-lg text-sm font-medium mt-4 inline-block">+ Tambah Pegawai</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($employees->hasPages())
                <div class="px-4 py-3 border-t border-slate-100">
                    {{ $employees->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
