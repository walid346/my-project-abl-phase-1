@extends('layouts.public')

@section('title', __('public.home_meta_title'))
@section('description', __('public.home_meta_description'))

@section('content')
<!-- Hero Section Moderne -->
<div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-cyan-500 dark:from-indigo-900 dark:via-purple-900 dark:to-cyan-700">
    <!-- Particules animées en arrière-plan -->
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-10 left-10 w-4 h-4 bg-white rounded-full animate-pulse"></div>
        <div class="absolute top-32 right-20 w-2 h-2 bg-cyan-300 rounded-full animate-bounce"></div>
        <div class="absolute bottom-20 left-1/4 w-3 h-3 bg-purple-300 rounded-full animate-ping"></div>
        <div class="absolute top-1/2 right-1/3 w-2 h-2 bg-indigo-300 rounded-full animate-pulse"></div>
        <div class="absolute bottom-32 right-10 w-4 h-4 bg-white rounded-full animate-bounce"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
        <div class="text-center">
            <!-- Badge moderne -->
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-white text-sm font-medium mb-8">
                <span class="text-lg mr-2">🐐</span>
                {{ __('public.new_content_weekly') }}
            </div>

            <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight">
                {{ __('public.welcome_to') }}
                <span class="bg-gradient-to-r from-cyan-400 to-purple-400 bg-clip-text text-transparent">
                    {{ __('public.site_title') }}
                </span>
            </h1>

            <p class="text-xl md:text-2xl text-indigo-100 mb-12 max-w-4xl mx-auto leading-relaxed">
                {{ __('public.hero_subtitle') }}
                <span class="block mt-2 text-lg text-cyan-200">{{ __('public.discover_articles') }}</span>
            </p>

            <div class="flex justify-center">
                <a href="#articles" class="group inline-flex items-center px-8 py-4 bg-white text-indigo-600 font-semibold rounded-xl hover:bg-gray-50 transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                    <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    {{ __('public.explore_articles') }}
                    <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>

            <!-- Statistiques -->
            <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-8 max-w-3xl mx-auto">
                <div class="text-center">
                    <div class="text-3xl font-bold text-white">{{ $articles->total() ?? 0 }}</div>
                    <div class="text-indigo-200 text-sm">{{ __('public.articles') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-white">12K+</div>
                    <div class="text-indigo-200 text-sm">{{ __('public.readers') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-white">7</div>
                    <div class="text-indigo-200 text-sm">{{ __('public.categories') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-white">2025</div>
                    <div class="text-indigo-200 text-sm">{{ __('public.since') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vague décorative -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="currentColor" class="text-white dark:text-gray-900"/>
        </svg>
    </div>
</div>

<!-- Articles Section -->
<div id="articles" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col lg:flex-row gap-12">
        <!-- Main Content -->
        <div class="lg:w-2/3">
            <div class="flex items-center justify-between mb-12">
                <div>
                    <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ __('public.latest_articles') }}</h2>
                    <p class="text-gray-600 dark:text-gray-400">{{ __('public.latest_thoughts') }}</p>
                </div>


                
                <!-- Filter by category -->
                @if(request('category'))
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('public.filtered_by') }}</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                            {{ request('category') }}
                            <a href="{{ route('public.home') }}" class="ml-2 text-indigo-600 hover:text-indigo-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        </span>
                    </div>
                @endif
            </div>

            <!-- Articles Grid -->
            @if($articles->count() > 0)
                <div class="grid gap-8 lg:gap-12">
                    @foreach($articles as $article)
                        <article class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden transform hover:-translate-y-2 border border-gray-100 dark:border-gray-700">
                            <div class="lg:flex">
                                <!-- Article Image avec overlay -->
                                @if($article->hasImage())
                                    <div class="lg:w-2/5 relative overflow-hidden">
                                        <img src="{{ $article->getImageUrl() }}"
                                             alt="{{ $article->title }}"
                                             class="w-full h-64 lg:h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>

                                        <!-- Badge catégorie sur l'image -->
                                        @if($article->category)
                                            <div class="absolute top-4 left-4">
                                                <a href="{{ route('public.category.filter', $article->category->slug) }}"
                                                   class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition-colors backdrop-blur-sm">
                                                    {{ $article->category->name }}
                                                </a>
                                            </div>
                                        @endif

                                        <!-- Temps de lecture -->
                                        <div class="absolute bottom-4 right-4">
                                            <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-white/90 text-gray-800 backdrop-blur-sm">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ ceil(str_word_count(strip_tags($article->content)) / 200) }} min
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <!-- Placeholder si pas d'image -->
                                    <div class="lg:w-2/5 relative bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                        <div class="text-center text-white p-8">
                                            <svg class="w-16 h-16 mx-auto mb-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                            <p class="text-sm font-medium">Article</p>
                                        </div>

                                        @if($article->category)
                                            <div class="absolute top-4 left-4">
                                                <a href="{{ route('public.category.filter', $article->category->slug) }}"
                                                   class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white hover:bg-white/30 transition-colors backdrop-blur-sm">
                                                    {{ $article->category->name }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Article Content -->
                                <div class="p-8 {{ $article->image ? 'lg:w-3/5' : 'w-full' }} flex flex-col justify-between">
                                    <div>
                                        <!-- Meta info -->
                                        <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400 mb-4">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <time datetime="{{ $article->created_at->format('Y-m-d') }}">
                                                    {{ $article->created_at->format('d M Y') }}
                                                </time>
                                            </div>


                                        </div>

                                        <!-- Title -->
                                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 leading-tight group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                            <a href="{{ route('public.article.show', $article->slug) }}">
                                                {{ $article->title }}
                                            </a>
                                        </h3>

                                        <!-- Excerpt -->
                                        <p class="text-gray-600 dark:text-gray-300 mb-6 leading-relaxed line-clamp-3">
                                            {{ $article->excerpt ?? Str::limit(strip_tags($article->content), 180) }}
                                        </p>

                                        <!-- Tags -->
                                        @if($article->tags->count() > 0)
                                            <div class="flex flex-wrap gap-2 mb-6">
                                                @foreach($article->tags->take(3) as $tag)
                                                    <a href="{{ route('public.tag.filter', $tag->slug) }}"
                                                       class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors">
                                                        #{{ $tag->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Read More Button -->
                                    <div class="flex items-center justify-between">
                                        <a href="{{ route('public.article.show', $article->slug) }}"
                                           class="group/btn inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                                            read article
                                            <svg class="ml-2 w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                            </svg>
                                        </a>


                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $articles->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No articles published</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400">The first articles are coming soon!</p>
                </div>
            @endif
        </div>

        <!-- Sidebar Moderne -->
        <div class="lg:w-1/3">
            <div class="space-y-8 lg:sticky lg:top-8">


                <!-- Categories Widget -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center mb-6">
                        <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Catégories</h3>
                    </div>
                    <div class="space-y-3">
                        @php
                            $categories = \App\Models\Category::withCount('articles')->get();
                        @endphp
                        @foreach($categories as $category)
                            <a href="{{ route('public.category.filter', $category->slug) }}"
                               class="group flex items-center justify-between p-3 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-indigo-500 rounded-full mr-3 group-hover:scale-125 transition-transform"></div>
                                    <span class="text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 font-medium">
                                        {{ $category->name }}
                                    </span>
                                </div>
                                <span class="text-sm text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-lg">
                                    {{ $category->articles_count }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Popular Tags -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center mb-6">
                        <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Popular tags</h3>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @php
                            $tags = \App\Models\Tag::withCount('articles')->orderBy('articles_count', 'desc')->take(10)->get();
                        @endphp
                        @foreach($tags as $tag)
                            <a href="{{ route('public.tag.filter', $tag->slug) }}"
                               class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gradient-to-r from-purple-100 to-indigo-100 dark:from-purple-900/30 dark:to-indigo-900/30 text-purple-700 dark:text-purple-300 hover:from-purple-200 hover:to-indigo-200 dark:hover:from-purple-900/50 dark:hover:to-indigo-900/50 transition-all duration-300 transform hover:scale-105">
                                #{{ $tag->name }}
                                @if($tag->articles_count > 0)
                                    <span class="ml-1 text-xs bg-purple-200 dark:bg-purple-800 text-purple-800 dark:text-purple-200 px-1 rounded">
                                        {{ $tag->articles_count }}
                                    </span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center mb-6">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Statistics</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                            <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $articles->total() ?? 0 }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Articles</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $categories->count() ?? 0 }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Catégories</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ \App\Models\Tag::count() }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Tags</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                            <div class="text-2xl font-bold text-cyan-600 dark:text-cyan-400">2025</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">From</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection