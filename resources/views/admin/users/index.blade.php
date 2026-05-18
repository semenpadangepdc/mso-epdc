@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">

                {{-- Header --}}
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Manajemen User</h2>
                        <p class="text-sm text-gray-500 mt-0.5">Kelola akun dan role pengguna sistem.</p>
                    </div>
                    <a href="{{ route('admin.users.create') }}"
                       class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition">
                        <i class="fas fa-plus"></i>
                        Tambah User
                    </a>
                </div>

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="flex items-center gap-3 bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-4 text-sm">
                        <i class="fas fa-check-circle text-green-500"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="flex items-center gap-3 bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-lg mb-4 text-sm">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Table --}}
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 uppercase tracking-wide text-xs">#</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 uppercase tracking-wide text-xs">Nama</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 uppercase tracking-wide text-xs">Email</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 uppercase tracking-wide text-xs">Role</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-600 uppercase tracking-wide text-xs">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($users as $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 text-gray-500">{{ $user->id }}</td>
                                <td class="px-4 py-3 font-medium text-gray-800">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-red-100 text-red-700 flex items-center justify-center font-bold text-xs">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        {{ $user->name }}
                                        @if(auth()->id() === $user->id)
                                            <span class="text-xs bg-blue-100 text-blue-700 rounded-full px-2 py-0.5 font-medium">Anda</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                                <td class="px-4 py-3">
                                    @foreach($user->roles as $role)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            {{ strtolower($role->name) === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700' }}">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @endforeach
                                    @if($user->roles->isEmpty())
                                        <span class="text-gray-400 text-xs italic">Tidak ada role</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                           class="text-blue-600 hover:text-blue-800 font-medium transition"
                                           title="Edit user">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        @if(auth()->id() !== $user->id)
                                        <form action="{{ route('admin.users.destroy', $user) }}"
                                              method="POST"
                                              class="inline-block"
                                              onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-800 font-medium transition"
                                                    title="Hapus user">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                        @else
                                        <span class="text-gray-400 text-xs italic">—</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-gray-400">
                                    <i class="fas fa-users text-3xl mb-2 block"></i>
                                    Belum ada data user.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($users->hasPages())
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection