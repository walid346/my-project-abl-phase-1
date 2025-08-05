@extends('layouts.public')

@section('title', $article->title . ' - Mon Blog Personnel')
@section('description', $article->excerpt)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('public.home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400">{{ $article->title }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Article Header -->
    <header class="mb-8">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6">
            {{ $article->title }}
        </h1>

        <!-- Article Meta -->
        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400 mb-6">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 0h6m-6 0a1 1 0 00-1 1v10a1 1 0 001 1h6a1 1 0 001-1V8a1 1 0 00-1-1"></path>
                </svg>
                <time datetime="{{ $article->created_at->format('Y-m-d') }}">
                    {{ $article->created_at->format('d M Y') }}
                </time>
            </div>

            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Par {{ $article->admin->username }}
            </div>

            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ ceil(str_word_count(strip_tags($article->content)) / 200) }} min de lecture
            </div>
        </div>

        <!-- Category -->
        @if($article->category)
            <div class="flex flex-wrap gap-2 mb-6">
                <a href="{{ route('public.category.filter', $article->category->slug) }}"
                   class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 hover:bg-indigo-200 dark:hover:bg-indigo-800 transition-colors">
                    {{ $article->category->name }}
                </a>
            </div>
        @endif
    </header>

    <!-- Featured Image -->
    @if($article->image)
        <div class="mb-8">
            <img src="{{ Storage::url($article->image) }}" 
                 alt="{{ $article->title }}" 
                 class="w-full h-64 md:h-96 object-cover rounded-lg shadow-lg">
        </div>
    @endif

    <!-- Article Content -->
    <div class="prose prose-lg dark:prose-invert max-w-none mb-8">
        {!! nl2br(e($article->content)) !!}
    </div>

    <!-- Tags -->
    @if($article->tags->count() > 0)
        <div class="border-t border-gray-200 dark:border-gray-700 pt-8 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tags</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($article->tags as $tag)
                    <a href="{{ route('public.tag.filter', $tag->slug) }}" 
                       class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-indigo-100 dark:hover:bg-indigo-900 transition-colors">
                        #{{ $tag->name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif



    <!-- Navigation -->
    <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
        <div class="flex justify-between">
            @if($previousArticle)
                <a href="{{ route('public.article.show', $previousArticle->slug) }}" 
                   class="flex items-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <div class="text-left">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Previous article</div>
                        <div class="font-medium">{{ Str::limit($previousArticle->title, 40) }}</div>
                    </div>
                </a>
            @else
                <div></div>
            @endif

            @if($nextArticle)
                <a href="{{ route('public.article.show', $nextArticle->slug) }}" 
                   class="flex items-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                    <div class="text-right">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Next article</div>
                        <div class="font-medium">{{ Str::limit($nextArticle->title, 40) }}</div>
                    </div>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            @else
                <div></div>
            @endif
        </div>
    </div>
</div>
@endsection
