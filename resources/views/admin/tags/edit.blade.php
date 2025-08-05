@extends('layouts.admin')

@section('title', 'Modifier le tag')
@section('page-title', 'Modifier le tag')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">edit tag</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">#{{ $tag->name }}</p>
        </div>
        <div class="flex space-x-2">
            @if($tag->articles_count > 0)
                <a href="{{ route('public.tag.filter', $tag->slug) }}" 
                   target="_blank"
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Voir sur le site
                </a>
            @endif
            <a href="{{ route('admin.tags.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('admin.tags.update', $tag) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nom du tag <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 dark:text-gray-400">#</span>
                    </div>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $tag->name) }}"
                           required
                           class="w-full pl-8 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                           placeholder="Ex: Laravel, JavaScript, Tutorial, etc.">
                </div>
                @error('name')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Slug -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Current URL (slug)
                </label>
                <div class="flex items-center">
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ url('/tag/') }}/</span>
                    <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400 ml-1">{{ $tag->slug }}</span>
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    The URL will be updated automatically if you change the name
                </p>
            </div>

            <!-- Tag Preview -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Overview
                </label>
                <div class="flex items-center space-x-2">
                    <span id="tag-preview" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                        #{{ $tag->name }}
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Preview of the tag as it will appear on the site</span>
                </div>
            </div>

            <!-- Stats -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Statistics</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $tag->articles_count }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Related article(s)</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-600 dark:text-gray-400">{{ $tag->created_at->format('d/m/Y') }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">creation date</div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.tags.index') }}" 
                   class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">
                    Annuler
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Update
                </button>
            </div>
        </form>
    </div>

    <!-- Articles with this tag -->
    @if($tag->articles_count > 0)
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Items with this tag</h3>
            <div class="space-y-3">
                @foreach($tag->articles()->latest()->take(10)->get() as $article)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $article->title }}</h4>
                            <div class="flex items-center space-x-4 mt-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $article->status === 'published' ? 'Publié' : 'Brouillon' }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $article->created_at->format('d/m/Y') }}
                                </p>
                                @if($article->category)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200">
                                        {{ $article->category->name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            @if($article->status === 'published')
                                <a href="{{ route('public.article.show', $article->slug) }}" 
                                   target="_blank"
                                   class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            @endif
                            <a href="{{ route('admin.articles.edit', $article) }}" 
                               class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
                
                @if($tag->articles_count > 10)
                    <div class="text-center">
                        <a href="{{ route('admin.articles.index', ['search' => $tag->name]) }}" 
                           class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                            See all articles with this tag ({{ $tag->articles_count }})
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Related Tags -->
    @if(isset($related_tags) && $related_tags->count() > 0)
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Similar tags</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($related_tags as $related_tag)
                    <a href="{{ route('admin.tags.edit', $related_tag) }}" 
                       class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-indigo-100 dark:hover:bg-indigo-900 transition-colors">
                        #{{ $related_tag->name }}
                        <span class="ml-1 text-xs text-gray-500 dark:text-gray-400">({{ $related_tag->articles_count }})</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Merge Tags -->
    @if($tag->articles_count === 0)
        <div class="mt-6 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-yellow-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Unused tag</h3>
                    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                       This tag is not associated with any article. You can delete it without any impact on your content.
                    </div>
                    <div class="mt-3">
                        <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}" 
                              class="inline-block"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce tag ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                               delete this tag
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Update tag preview when name changes
    document.getElementById('name').addEventListener('input', function() {
        const name = this.value;
        document.getElementById('tag-preview').textContent = '#' + (name || 'nom-du-tag');
    });
</script>
@endpush
@endsection
