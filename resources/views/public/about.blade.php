@extends('layouts.public')

@section('title', __('public.about_meta_title'))
@section('description', __('public.about_meta_description'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Hero Section -->
    <div class="text-center mb-16">
        <div class="w-32 h-32 mx-auto mb-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6">
            {{ __('public.about_me') }}
        </h1>
        <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
            {{ __('public.about_description') }}
        </p>
    </div>

    <!-- Contact Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">{{ __('public.contact_me') }}</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Email -->
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Email</div>
                    <div class="text-gray-900 dark:text-white">contact-walid@gmail.com</div>
                </div>
            </div>

            <!-- GitHub -->
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">GitHub</div>
                    <div class="text-gray-900 dark:text-white">@salhi-walid</div>
                </div>
            </div>

            <!-- LinkedIn -->
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">LinkedIn</div>
                    <div class="text-gray-900 dark:text-white">WalidProfile</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="prose prose-lg dark:prose-invert max-w-none">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Mon parcours</h2>
            
            <p class="text-gray-600 dark:text-gray-300 mb-6">
               As a passionate web developer, I've had the opportunity to work on a variety of projects, mixing design, development and optimization. This blog is a reflection of this diversity: I share articles about web development, but also about topics I'm passionate about, such as aviation, digital marketing, technology news, productivity and practical advice. My aim is to document my experiences, pass on my knowledge and offer useful resources to those who wish to learn, progress or simply discover.

Translated with DeepL.com (free version)
            </p>

            <p class="text-gray-600 dark:text-gray-300 mb-6">
              Are you curious, passionate or simply looking for inspiration? You've come to the right place.
              This blog is much more than a technical diary: it's a logbook for people with a passion for the web, knowledge and initiative.
            </p>
        </div>

        <!-- Skills Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Mes compétences</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Frontend -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Frontend</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-300">HTML/CSS</span>
                            <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: 95%"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-300">JavaScript</span>
                            <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: 90%"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-300">React</span>
                            <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: 85%"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-300">Vue.js</span>
                            <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: 80%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Backend -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Backend</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-300">PHP</span>
                            <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: 95%"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-300">Django</span>
                            <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: 90%"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-300">MySQL</span>
                            <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: 85%"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-300">Node.js</span>
                            <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: 75%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Passions & Curiosités Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Mes passions & curiosités</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Développement Web -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg p-6 border border-blue-100 dark:border-blue-800">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Code & Innovation</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">
                        Passionate about modern frameworks, software architecture and the new technologies shaping tomorrow's web.
                    </p>
                </div>

                <!-- Marketing Digital -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg p-6 border border-green-100 dark:border-green-800">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Digital Marketing</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">
                      Digital strategies, SEO, content marketing and data analysis to understand today's digital ecosystem.
                    </p>
                </div>

                <!-- Intelligence Artificielle -->
                <div class="bg-gradient-to-br from-purple-50 to-violet-50 dark:from-purple-900/20 dark:to-violet-900/20 rounded-lg p-6 border border-purple-100 dark:border-purple-800">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">IA & Innovation</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">
                        Exploring the possibilities offered by AI, machine learning and their integration into modern development.
                    </p>
                </div>

                <!-- Aviation -->
                <div class="bg-gradient-to-br from-sky-50 to-cyan-50 dark:from-sky-900/20 dark:to-cyan-900/20 rounded-lg p-6 border border-sky-100 dark:border-sky-800">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-sky-500 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Aviation</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">
                       A fascination with aeronautics, flight technology and the evolution of the modern airline industry.
                    </p>
                </div>

                <!-- Productivité -->
                <div class="bg-gradient-to-br from-orange-50 to-amber-50 dark:from-orange-900/20 dark:to-amber-900/20 rounded-lg p-6 border border-orange-100 dark:border-orange-800">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Productivité</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">
                      Organization methods, efficiency tools and techniques to optimize time and performance.
                    </p>
                </div>

                <!-- Tech & Actualités -->
                <div class="bg-gradient-to-br from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 rounded-lg p-6 border border-red-100 dark:border-red-800">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Actualités Tech</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">
                        Technology watch, emerging trends and analysis of the innovations that are transforming our daily lives.
                    </p>
                </div>
            </div>

            <!-- Citation inspirante -->
            <div class="mt-8 p-6 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-lg border-l-4 border-indigo-500">
                <blockquote class="text-lg italic text-gray-700 dark:text-gray-300 mb-2">
                    "Curiosity is the driving force behind innovation. Every field we explore enriches our vision of the world and feeds our creativity."
                </blockquote>
                <cite class="text-sm text-gray-500 dark:text-gray-400">- My personal philosophy</cite>
            </div>
        </div>

    </div>
</div>
@endsection