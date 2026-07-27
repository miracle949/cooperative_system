@extends('layouts.admin')

@section('title', 'Officers & Committees - CoopAdmin')

@section('content')
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
                    <span class="text-gray-900 font-medium">Officers & Committees</span>
                </li>
            </ol>
        </nav>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Officers & Committees</h1>
        <p class="text-sm text-gray-500">View cooperative officers and committee members</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
            <span class="text-green-800">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center gap-3">
            <i data-lucide="x-circle" class="w-5 h-5 text-red-600"></i>
            <span class="text-red-800">{{ session('error') }}</span>
        </div>
    @endif

    <div class="flex items-center gap-3 mb-6">
        <button onclick="openModal('addOfficerModal')" class="btn btn-primary">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            Add Officer
        </button>
        <button onclick="openModal('addCommitteeModal')" class="btn btn-outline">
            <i data-lucide="folder-plus" class="w-4 h-4"></i>
            Add Committee
        </button>
    </div>

    <div class="card p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Board of Officers</h3>
                <p class="text-sm text-gray-500">Current cooperative officers and their positions</p>
            </div>
            <span class="badge badge-primary">7 Officers</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="relative p-5 bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl border border-primary-200">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center shadow-md">
                            <span class="text-white text-xl font-bold">JD</span>
                        </div>
                        <div>
                            <h4 class="text-base font-semibold text-gray-900">Juan Dela Cruz</h4>
                            <span class="inline-block px-3 py-1 bg-primary-600 text-white text-xs font-semibold rounded-full mt-1">Chairperson</span>
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <button class="p-1.5 hover:bg-white rounded-lg transition-colors" title="Edit">
                            <i data-lucide="pencil" class="w-4 h-4 text-gray-500"></i>
                        </button>
                        <button class="p-1.5 hover:bg-white rounded-lg transition-colors" title="Remove">
                            <i data-lucide="trash-2" class="w-4 h-4 text-red-500"></i>
                        </button>
                    </div>
                </div>
                <div class="mt-4 space-y-1.5 text-sm text-gray-600">
                    <div class="flex items-center gap-2">
                        <i data-lucide="phone" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span>0912-345-6789</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="mail" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span>juan.delacruz@kmPcats.com</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span>Term: 2025 - 2027</span>
                    </div>
                </div>
            </div>

            <div class="relative p-5 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-md">
                            <span class="text-white text-xl font-bold">MS</span>
                        </div>
                        <div>
                            <h4 class="text-base font-semibold text-gray-900">Maria Santos</h4>
                            <span class="inline-block px-3 py-1 bg-blue-600 text-white text-xs font-semibold rounded-full mt-1">Vice Chairperson</span>
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <button class="p-1.5 hover:bg-white rounded-lg transition-colors" title="Edit">
                            <i data-lucide="pencil" class="w-4 h-4 text-gray-500"></i>
                        </button>
                        <button class="p-1.5 hover:bg-white rounded-lg transition-colors" title="Remove">
                            <i data-lucide="trash-2" class="w-4 h-4 text-red-500"></i>
                        </button>
                    </div>
                </div>
                <div class="mt-4 space-y-1.5 text-sm text-gray-600">
                    <div class="flex items-center gap-2">
                        <i data-lucide="phone" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span>0917-123-4567</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="mail" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span>maria.santos@kmPcats.com</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span>Term: 2025 - 2027</span>
                    </div>
                </div>
            </div>

            <div class="relative p-5 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl border border-emerald-200">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-md">
                            <span class="text-white text-xl font-bold">PR</span>
                        </div>
                        <div>
                            <h4 class="text-base font-semibold text-gray-900">Pedro Reyes</h4>
                            <span class="inline-block px-3 py-1 bg-emerald-600 text-white text-xs font-semibold rounded-full mt-1">Secretary</span>
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <button class="p-1.5 hover:bg-white rounded-lg transition-colors" title="Edit">
                            <i data-lucide="pencil" class="w-4 h-4 text-gray-500"></i>
                        </button>
                        <button class="p-1.5 hover:bg-white rounded-lg transition-colors" title="Remove">
                            <i data-lucide="trash-2" class="w-4 h-4 text-red-500"></i>
                        </button>
                    </div>
                </div>
                <div class="mt-4 space-y-1.5 text-sm text-gray-600">
                    <div class="flex items-center gap-2">
                        <i data-lucide="phone" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span>0923-456-7890</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="mail" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span>pedro.reyes@kmPcats.com</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span>Term: 2025 - 2027</span>
                    </div>
                </div>
            </div>

            <div class="relative p-5 bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl border border-amber-200">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-md">
                            <span class="text-white text-xl font-bold">AG</span>
                        </div>
                        <div>
                            <h4 class="text-base font-semibold text-gray-900">Ana Gonzales</h4>
                            <span class="inline-block px-3 py-1 bg-amber-600 text-white text-xs font-semibold rounded-full mt-1">Treasurer</span>
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <button class="p-1.5 hover:bg-white rounded-lg transition-colors" title="Edit">
                            <i data-lucide="pencil" class="w-4 h-4 text-gray-500"></i>
                        </button>
                        <button class="p-1.5 hover:bg-white rounded-lg transition-colors" title="Remove">
                            <i data-lucide="trash-2" class="w-4 h-4 text-red-500"></i>
                        </button>
                    </div>
                </div>
                <div class="mt-4 space-y-1.5 text-sm text-gray-600">
                    <div class="flex items-center gap-2">
                        <i data-lucide="phone" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span>0915-678-9012</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="mail" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span>ana.gonzales@kmPcats.com</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span>Term: 2025 - 2027</span>
                    </div>
                </div>
            </div>

            <div class="relative p-5 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border border-purple-200">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center shadow-md">
                            <span class="text-white text-xl font-bold">CF</span>
                        </div>
                        <div>
                            <h4 class="text-base font-semibold text-gray-900">Carlos Flores</h4>
                            <span class="inline-block px-3 py-1 bg-purple-600 text-white text-xs font-semibold rounded-full mt-1">Auditor</span>
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <button class="p-1.5 hover:bg-white rounded-lg transition-colors" title="Edit">
                            <i data-lucide="pencil" class="w-4 h-4 text-gray-500"></i>
                        </button>
                        <button class="p-1.5 hover:bg-white rounded-lg transition-colors" title="Remove">
                            <i data-lucide="trash-2" class="w-4 h-4 text-red-500"></i>
                        </button>
                    </div>
                </div>
                <div class="mt-4 space-y-1.5 text-sm text-gray-600">
                    <div class="flex items-center gap-2">
                        <i data-lucide="phone" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span>0927-890-1234</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="mail" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span>carlos.flores@kmPcats.com</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span>Term: 2025 - 2027</span>
                    </div>
                </div>
            </div>

            <div class="relative p-5 bg-gradient-to-br from-rose-50 to-rose-100 rounded-xl border border-rose-200">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-rose-400 to-rose-600 flex items-center justify-center shadow-md">
                            <span class="text-white text-xl font-bold">LT</span>
                        </div>
                        <div>
                            <h4 class="text-base font-semibold text-gray-900">Luz Tan</h4>
                            <span class="inline-block px-3 py-1 bg-rose-600 text-white text-xs font-semibold rounded-full mt-1">P.R.O.</span>
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <button class="p-1.5 hover:bg-white rounded-lg transition-colors" title="Edit">
                            <i data-lucide="pencil" class="w-4 h-4 text-gray-500"></i>
                        </button>
                        <button class="p-1.5 hover:bg-white rounded-lg transition-colors" title="Remove">
                            <i data-lucide="trash-2" class="w-4 h-4 text-red-500"></i>
                        </button>
                    </div>
                </div>
                <div class="mt-4 space-y-1.5 text-sm text-gray-600">
                    <div class="flex items-center gap-2">
                        <i data-lucide="phone" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span>0930-123-4567</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="mail" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span>luz.tan@kmPcats.com</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span>Term: 2025 - 2027</span>
                    </div>
                </div>
            </div>

            <div class="relative p-5 bg-gradient-to-br from-cyan-50 to-cyan-100 rounded-xl border border-cyan-200">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-cyan-400 to-cyan-600 flex items-center justify-center shadow-md">
                            <span class="text-white text-xl font-bold">RV</span>
                        </div>
                        <div>
                            <h4 class="text-base font-semibold text-gray-900">Ramon Villanueva</h4>
                            <span class="inline-block px-3 py-1 bg-cyan-600 text-white text-xs font-semibold rounded-full mt-1">Sergeant-at-Arms</span>
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <button class="p-1.5 hover:bg-white rounded-lg transition-colors" title="Edit">
                            <i data-lucide="pencil" class="w-4 h-4 text-gray-500"></i>
                        </button>
                        <button class="p-1.5 hover:bg-white rounded-lg transition-colors" title="Remove">
                            <i data-lucide="trash-2" class="w-4 h-4 text-red-500"></i>
                        </button>
                    </div>
                </div>
                <div class="mt-4 space-y-1.5 text-sm text-gray-600">
                    <div class="flex items-center gap-2">
                        <i data-lucide="phone" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span>0932-567-8901</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="mail" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span>ramon.villanueva@kmPcats.com</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span>Term: 2025 - 2027</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Committees</h3>
                <p class="text-sm text-gray-500">Special committees and their members</p>
            </div>
            <span class="badge badge-primary">4 Committees</span>
        </div>

        <div class="space-y-4">
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 bg-gradient-to-r from-primary-50 to-primary-100 border-b border-primary-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-primary-200 flex items-center justify-center">
                            <i data-lucide="landmark" class="w-5 h-5 text-primary-700"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Finance Committee</h4>
                            <p class="text-xs text-gray-500">Oversees financial planning and budgeting</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="badge badge-primary">3 Members</span>
                        <button class="p-1.5 hover:bg-white rounded-lg transition-colors" title="Edit">
                            <i data-lucide="pencil" class="w-4 h-4 text-gray-500"></i>
                        </button>
                    </div>
                </div>
                <div class="px-5 py-3">
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary-50 text-primary-700 text-sm font-medium rounded-full border border-primary-200">
                            <span class="w-5 h-5 rounded-full bg-primary-400 flex items-center justify-center text-white text-[10px] font-bold">AG</span>
                            Ana Gonzales <span class="text-primary-400 text-xs">(Chair)</span>
                        </span>
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-50 text-gray-700 text-sm font-medium rounded-full border border-gray-200">
                            <span class="w-5 h-5 rounded-full bg-gray-400 flex items-center justify-center text-white text-[10px] font-bold">PR</span>
                            Pedro Reyes
                        </span>
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-50 text-gray-700 text-sm font-medium rounded-full border border-gray-200">
                            <span class="w-5 h-5 rounded-full bg-gray-400 flex items-center justify-center text-white text-[10px] font-bold">CF</span>
                            Carlos Flores
                        </span>
                    </div>
                </div>
            </div>

            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 bg-gradient-to-r from-blue-50 to-blue-100 border-b border-blue-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-200 flex items-center justify-center">
                            <i data-lucide="users" class="w-5 h-5 text-blue-700"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Membership Committee</h4>
                            <p class="text-xs text-gray-500">Handles member registration and relations</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="badge badge-primary">4 Members</span>
                        <button class="p-1.5 hover:bg-white rounded-lg transition-colors" title="Edit">
                            <i data-lucide="pencil" class="w-4 h-4 text-gray-500"></i>
                        </button>
                    </div>
                </div>
                <div class="px-5 py-3">
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-700 text-sm font-medium rounded-full border border-blue-200">
                            <span class="w-5 h-5 rounded-full bg-blue-400 flex items-center justify-center text-white text-[10px] font-bold">MS</span>
                            Maria Santos <span class="text-blue-400 text-xs">(Chair)</span>
                        </span>
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-50 text-gray-700 text-sm font-medium rounded-full border border-gray-200">
                            <span class="w-5 h-5 rounded-full bg-gray-400 flex items-center justify-center text-white text-[10px] font-bold">JD</span>
                            Juan Dela Cruz
                        </span>
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-50 text-gray-700 text-sm font-medium rounded-full border border-gray-200">
                            <span class="w-5 h-5 rounded-full bg-gray-400 flex items-center justify-center text-white text-[10px] font-bold">LT</span>
                            Luz Tan
                        </span>
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-50 text-gray-700 text-sm font-medium rounded-full border border-gray-200">
                            <span class="w-5 h-5 rounded-full bg-gray-400 flex items-center justify-center text-white text-[10px] font-bold">RV</span>
                            Ramon Villanueva
                        </span>
                    </div>
                </div>
            </div>

            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 bg-gradient-to-r from-emerald-50 to-emerald-100 border-b border-emerald-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-emerald-200 flex items-center justify-center">
                            <i data-lucide="shopping-bag" class="w-5 h-5 text-emerald-700"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Livelihood Committee</h4>
                            <p class="text-xs text-gray-500">Manages livelihood programs and projects</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="badge badge-primary">3 Members</span>
                        <button class="p-1.5 hover:bg-white rounded-lg transition-colors" title="Edit">
                            <i data-lucide="pencil" class="w-4 h-4 text-gray-500"></i>
                        </button>
                    </div>
                </div>
                <div class="px-5 py-3">
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-50 text-emerald-700 text-sm font-medium rounded-full border border-emerald-200">
                            <span class="w-5 h-5 rounded-full bg-emerald-400 flex items-center justify-center text-white text-[10px] font-bold">RV</span>
                            Ramon Villanueva <span class="text-emerald-400 text-xs">(Chair)</span>
                        </span>
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-50 text-gray-700 text-sm font-medium rounded-full border border-gray-200">
                            <span class="w-5 h-5 rounded-full bg-gray-400 flex items-center justify-center text-white text-[10px] font-bold">AG</span>
                            Ana Gonzales
                        </span>
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-50 text-gray-700 text-sm font-medium rounded-full border border-gray-200">
                            <span class="w-5 h-5 rounded-full bg-gray-400 flex items-center justify-center text-white text-[10px] font-bold">PR</span>
                            Pedro Reyes
                        </span>
                    </div>
                </div>
            </div>

            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 bg-gradient-to-r from-amber-50 to-amber-100 border-b border-amber-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-200 flex items-center justify-center">
                            <i data-lucide="graduation-cap" class="w-5 h-5 text-amber-700"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Education & Training Committee</h4>
                            <p class="text-xs text-gray-500">Organizes training and educational programs</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="badge badge-primary">3 Members</span>
                        <button class="p-1.5 hover:bg-white rounded-lg transition-colors" title="Edit">
                            <i data-lucide="pencil" class="w-4 h-4 text-gray-500"></i>
                        </button>
                    </div>
                </div>
                <div class="px-5 py-3">
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-amber-50 text-amber-700 text-sm font-medium rounded-full border border-amber-200">
                            <span class="w-5 h-5 rounded-full bg-amber-400 flex items-center justify-center text-white text-[10px] font-bold">LT</span>
                            Luz Tan <span class="text-amber-400 text-xs">(Chair)</span>
                        </span>
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-50 text-gray-700 text-sm font-medium rounded-full border border-gray-200">
                            <span class="w-5 h-5 rounded-full bg-gray-400 flex items-center justify-center text-white text-[10px] font-bold">MS</span>
                            Maria Santos
                        </span>
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-50 text-gray-700 text-sm font-medium rounded-full border border-gray-200">
                            <span class="w-5 h-5 rounded-full bg-gray-400 flex items-center justify-center text-white text-[10px] font-bold">CF</span>
                            Carlos Flores
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Officer Modal -->
    <div id="addOfficerModal" class="modal-overlay hidden">
        <div class="modal max-w-lg">
            <div style="background: linear-gradient(135deg, #1E2A4A 0%, #25335A 100%); padding: 1.25rem 1.5rem;">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i data-lucide="user-plus" class="w-5 h-5" style="color: #fff;"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold" style="color: #fff; margin: 0;">Add New Officer</h2>
                            <p style="margin: 4px 0 0 0; color: rgba(255,255,255,0.7); font-size: 12px;">Assign a new officer position</p>
                        </div>
                    </div>
                    <button onclick="closeModal('addOfficerModal')" style="background: rgba(255,255,255,0.1); border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                        <i data-lucide="x" class="w-5 h-5" style="color: #fff;"></i>
                    </button>
                </div>
            </div>
            <form class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Member</label>
                    <select class="input" required>
                        <option value="">Select a member...</option>
                        <option>Juan Dela Cruz</option>
                        <option>Maria Santos</option>
                        <option>Pedro Reyes</option>
                        <option>Ana Gonzales</option>
                        <option>Carlos Flores</option>
                        <option>Luz Tan</option>
                        <option>Ramon Villanueva</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                    <select class="input" required>
                        <option value="">Select position...</option>
                        <option>Chairperson</option>
                        <option>Vice Chairperson</option>
                        <option>Secretary</option>
                        <option>Treasurer</option>
                        <option>Auditor</option>
                        <option>P.R.O.</option>
                        <option>Sergeant-at-Arms</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Term Start</label>
                    <input type="date" class="input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Term End</label>
                    <input type="date" class="input">
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal('addOfficerModal')" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Add Officer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Committee Modal -->
    <div id="addCommitteeModal" class="modal-overlay hidden">
        <div class="modal max-w-lg">
            <div style="background: linear-gradient(135deg, #1E2A4A 0%, #25335A 100%); padding: 1.25rem 1.5rem;">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i data-lucide="folder-plus" class="w-5 h-5" style="color: #fff;"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold" style="color: #fff; margin: 0;">Add New Committee</h2>
                            <p style="margin: 4px 0 0 0; color: rgba(255,255,255,0.7); font-size: 12px;">Create a new committee</p>
                        </div>
                    </div>
                    <button onclick="closeModal('addCommitteeModal')" style="background: rgba(255,255,255,0.1); border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                        <i data-lucide="x" class="w-5 h-5" style="color: #fff;"></i>
                    </button>
                </div>
            </div>
            <form class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Committee Name</label>
                    <input type="text" class="input" placeholder="Enter committee name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea class="input" rows="2" placeholder="Committee purpose and responsibilities"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Committee Chair</label>
                    <select class="input">
                        <option value="">Select chairperson...</option>
                        <option>Juan Dela Cruz</option>
                        <option>Maria Santos</option>
                        <option>Pedro Reyes</option>
                        <option>Ana Gonzales</option>
                        <option>Carlos Flores</option>
                        <option>Luz Tan</option>
                        <option>Ramon Villanueva</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal('addCommitteeModal')" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Create Committee
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection