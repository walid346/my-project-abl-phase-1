@extends('layouts.admin')

@section('title', 'Mon profil')
@section('page-title', 'Mon profil')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Profile information</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update your personal information and password</p>
    </div>

    <!-- Profile Information -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Personal information</h3>
        
        <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                   User name
                </label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       value="{{ old('username', Auth::guard('admin')->user()->username) }}"
                       required
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                @error('username')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                     email adresse
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email', Auth::guard('admin')->user()->email) }}"
                       required
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                @error('email')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save
                </button>
            </div>
        </form>
    </div>

    <!-- Change Password -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Change password</h3>
        
        <form method="POST" action="{{ route('admin.profile.password') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Current Password -->
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Current password
                </label>
                <input type="password" 
                       id="current_password" 
                       name="current_password" 
                       required
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                @error('current_password')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                   New password
                </label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                @error('password')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Confirm new password
                </label>
                <input type="password" 
                       id="password_confirmation" 
                       name="password_confirmation" 
                       required
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Change password
                </button>
            </div>
        </form>
    </div>

    <!-- Account Statistics -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Account statistics</h3>
        
        <div class="grid grid-cols-2 gap-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                    {{ Auth::guard('admin')->user()->articles()->count() }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Created items</div>
            </div>
            
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                    {{ Auth::guard('admin')->user()->articles()->where('status', 'published')->count() }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Published articles</div>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                <p>Membre depuis : {{ Auth::guard('admin')->user()->created_at->format('d M Y') }}</p>
                <p>Dernière connexion : {{ Auth::guard('admin')->user()->updated_at->format('d M Y à H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
