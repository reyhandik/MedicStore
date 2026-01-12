@extends('layouts.app')

@section('title', 'Masuk - MedicStore')

@section('content')
<div class="max-w-md mx-auto">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Masuk</h1>
        <p class="text-gray-600">Masuk ke akun MedicStore Anda</p>
    </div>

    <!-- Login Form -->
    <form action="{{ route('login') }}" method="POST" class="bg-white rounded-lg shadow-md p-8 space-y-6">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="anda@email.com">
            @error('email')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Kata Sandi</label>
            <input type="password" id="password" name="password" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="••••••••">
            @error('password')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input type="checkbox" id="remember" name="remember" class="w-4 h-4 text-blue-600 rounded">
            <label for="remember" class="ml-2 text-sm text-gray-700">Ingat saya</label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition">
            Masuk
        </button>

        <!-- Divider -->
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-600">atau</span>
            </div>
        </div>

        <!-- Register Link -->
        <div class="text-center">
            <p class="text-gray-600">Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-semibold">Daftar di sini</a>
            </p>
        </div>

        <!-- Forgot Password Link -->
        <div class="text-center">
            <a href="{{ route('password.request') }}" class="text-gray-600 hover:text-gray-800 text-sm">Lupa kata sandi?</a>
        </div>
    </form>

    <!-- Demo Credentials -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <p class="text-sm font-semibold text-blue-900 mb-2">Akun Demo:</p>
        <ul class="text-xs text-blue-800 space-y-1">
            <li><strong>Admin:</strong> admin@medicstore.com / password</li>
            <li><strong>Apoteker:</strong> pharmacist@medicstore.com / password</li>
            <li><strong>Pasien:</strong> john@example.com / password</li>
        </ul>
    </div>
</div>
@endsection
