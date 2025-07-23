@extends('layouts.admin')

@section('title', 'Créer un tag')
@section('page-title', 'Nouveau tag')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">create new tag</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Label your items for better organization</p>
        </div>
        <a href="{{ route('test.auth') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
           Back
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('test.store.tag') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                   tag name <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 dark:text-gray-400">#</span>
                    </div>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           required
                           class="w-full pl-8 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                           placeholder="Ex: Laravel, JavaScript, Tutorial, etc.">
                </div>
                @error('name')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                   Use short, descriptive keywords. Avoid spaces and special characters.
                </p>
            </div>

            <!-- Slug Preview -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    URL (slug)
                </label>
                <div class="flex items-center">
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ url('/tag/') }}/</span>
                    <span id="slug-preview" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 ml-1">tag name</span>
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">The URL will be generated automatically from the name</p>
            </div>

            <!-- Tag Preview -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Aperçu
                </label>
                <div class="flex items-center space-x-2">
                    <span id="tag-preview" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                        #nom-du-tag
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Preview of the tag as it will appear on the site</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('test.auth') }}"
                   class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">
                    Annuler
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                   create tag
                </button>
            </div>
        </form>
    </div>

    <!-- Quick Add Multiple Tags -->
    <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Quick creation of multiple tags</h3>
        <form method="POST" action="{{ route('simple.multiple.tags') }}" class="space-y-4">
            @csrf
            <div>
                <label for="tags" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Tags multiples
                </label>
                <textarea id="tags"
                          name="tags"
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                          placeholder="Entrez plusieurs tags séparés par des virgules&#10;Ex: Laravel, PHP, JavaScript, React, Vue.js"></textarea>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Separate each tag with a comma. Duplicates will be automatically ignored.
                </p>
            </div>
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
               Create all tags
            </button>
        </form>
    </div>

    <!-- Tips -->
    <div class="mt-6 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
        <div class="flex">
            <svg class="w-5 h-5 text-blue-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Tips for tags</h3>
                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Use specific, relevant keywords</li>
                        <li>Avoid overly generic tags such as “article” or “blog”.</li>
                        <li>An article can have several tags (unlike categories).</li>
                        <li>Tags help SEO and navigation</li>
                        <li>Reuse existing tags rather than creating new, similar ones.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Tags for Reference -->
    @if(isset($popular_tags) && $popular_tags->count() > 0)
        <div class="mt-6 bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Popular existing tags</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($popular_tags as $tag)
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200">
                        #{{ $tag->name }} ({{ $tag->articles_count }})
                    </span>
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Auto-generate slug and tag preview
    document.getElementById('name').addEventListener('input', function() {
        const name = this.value;
        const slug = name
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
            .replace(/\s+/g, '-') // Replace spaces with hyphens
            .replace(/-+/g, '-') // Replace multiple hyphens with single
            .trim('-'); // Remove leading/trailing hyphens
        
        document.getElementById('slug-preview').textContent = slug || 'nom-du-tag';
        document.getElementById('tag-preview').textContent = '#' + (name || 'nom-du-tag');
    });

    // Auto-format multiple tags input
    document.getElementById('multiple_tags').addEventListener('input', function() {
        // Auto-format as user types
        let value = this.value;
        // Remove extra spaces around commas
        value = value.replace(/\s*,\s*/g, ', ');
        // Remove leading/trailing spaces
        value = value.trim();
        this.value = value;
    });
</script>
@endpush
@endsection
