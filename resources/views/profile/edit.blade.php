@extends('layouts.app')

@section('title', 'Edit Profil - MedicStore')

@section('content')
<div class="max-w-2xl mx-auto py-12">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Profil</h1>
        <p class="text-gray-600">Kelola informasi akun Anda</p>
    </div>

    <!-- Success Message -->
    @if (session('status'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
            {{ session('status') }}
        </div>
    @endif

    <!-- Profile Information Section -->
    <div class="bg-white rounded-lg shadow-md p-8 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Informasi Profil</h2>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <!-- Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan nama lengkap">
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email *</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan email">
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role Display (Read-only) -->
            <div class="mb-6">
                <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">Peran</label>
                <div class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-700">
                    @if($user->role === 'admin')
                        Administrator
                    @elseif($user->role === 'pharmacist')
                        Apoteker
                    @else
                        Pasien
                    @endif
                </div>
            </div>

            <!-- Member Since (Read-only) -->
            <div class="mb-6">
                <label for="created_at" class="block text-sm font-semibold text-gray-700 mb-2">Anggota Sejak</label>
                <div class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-700">
                    {{ $user->created_at->format('d F Y') }}
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                Simpan Perubahan
            </button>
        </form>
    </div>

    <!-- Danger Zone -->
    <div class="bg-red-50 border border-red-200 rounded-lg p-8">
        <h3 class="text-lg font-bold text-red-900 mb-2">Zona Berbahaya</h3>
        <p class="text-red-800 mb-4">Setelah Anda menghapus akun, tidak ada jalan kembali. Harap pastikan Anda yakin.</p>
        
        <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun Anda? Tindakan ini tidak dapat dibatalkan.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                Hapus Akun
            </button>
        </form>
    </div>
</div>
@endsection
