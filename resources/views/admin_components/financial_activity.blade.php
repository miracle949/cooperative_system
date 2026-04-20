@extends('layouts.admin')

@section('title', 'Financial Activity - CoopAdmin')

@section('content')
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="text-sm text-gray-500">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="hover:text-primary-600">
                        <i data-lucide="home" class="w-4 h-4 mr-2"></i>
                    </a>
                </li>
                <li class="flex items-center">
                    <i data-lucide="chevron-right" class="w-4 h-4 mx-2 text-gray-400"></i>
                    <span class="text-gray-900 font-medium">Financial Activity</span>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Coming Soon -->
    <div class="flex flex-col items-center justify-center min-h-[60vh]">
        <div class="text-center">
            <div class="w-24 h-24 bg-primary-100 rounded-full flex items-center justify-center mb-6">
                <i data-lucide="wallet" class="w-12 h-12 text-primary-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Coming Soon</h1>
            <p class="text-gray-500 text-lg">Financial Activity module is under development.</p>
            <p class="text-gray-400 text-sm mt-2">Stay tuned for updates!</p>
        </div>
    </div>
@endsection