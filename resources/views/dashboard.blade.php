<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Welcome Header -->
            <div class="bg-indigo-700 text-white rounded-lg p-6 mb-8 shadow-md">
                <h1 class="text-2xl font-bold mb-2">Selamat Datang di SINTARA!</h1>
                <p class="text-indigo-100">
                    Sistem Informasi Tata Arsip Bappeda Provinsi Lampung. 
                    Anda login sebagai <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->role === 'admin' ? 'Administrator' : (auth()->user()->bidang ? auth()->user()->bidang->nama_bidang : 'Operator Bidang') }}).
                </p>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                
                <!-- Card Bidang -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Bidang / Divisi</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalBidang }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-blue-50 text-blue-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    </div>
                    @if(auth()->user()->isAdmin())
                        <div class="text-xs text-blue-500 font-semibold mt-3">
                            <a href="{{ route('bidang.index') }}">Kelola Bidang &rarr;</a>
                        </div>
                    @endif
                </div>

                <!-- Card Boks -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-yellow-500 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Boks Fisik</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalBoks }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-yellow-50 text-yellow-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                    @if(auth()->user()->isAdmin())
                        <div class="text-xs text-yellow-600 font-semibold mt-3">
                            <a href="{{ route('boks.index') }}">Kelola Boks Fisik &rarr;</a>
                        </div>
                    @endif
                </div>

                <!-- Card Surat Masuk -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Surat Masuk</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalSuratMasuk }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-green-50 text-green-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-xs text-green-500 font-semibold mt-3">
                        <a href="{{ route('surat-masuk.index') }}">Lihat Surat Masuk &rarr;</a>
                    </div>
                </div>

                <!-- Card Surat Keluar -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-purple-500 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Surat Keluar</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalSuratKeluar }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-purple-50 text-purple-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4l4 4m0 0l-4 4m4-4H12" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-xs text-purple-500 font-semibold mt-3">
                        <a href="{{ route('surat-keluar.index') }}">Lihat Surat Keluar &rarr;</a>
                    </div>
                </div>

            </div>

            <!-- Recent Activity List -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Recent Surat Masuk -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3 mb-4">
                        <h3 class="font-bold text-gray-800 text-lg">Surat Masuk Terbaru</h3>
                        <a href="{{ route('surat-masuk.index') }}" class="text-xs text-indigo-600 hover:text-indigo-900 font-semibold">Semua &rarr;</a>
                    </div>
                    @if($recentSuratMasuk->isEmpty())
                        <p class="text-sm text-gray-500 py-4">Belum ada surat masuk yang tercatat.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($recentSuratMasuk as $sm)
                                <div class="flex items-start justify-between p-3 hover:bg-gray-50 rounded-md transition duration-150">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $sm->perihal }}</p>
                                        <p class="text-xs text-gray-500 mt-1">No: {{ $sm->nomor_surat }} | Dari: {{ $sm->pengirim }}</p>
                                        <div class="flex items-center space-x-2 mt-1.5">
                                            <span class="text-[10px] bg-blue-50 text-blue-700 px-2 py-0.5 rounded font-medium">
                                                {{ $sm->bidang->nama_bidang }}
                                            </span>
                                            <span class="text-[10px] bg-yellow-50 text-yellow-700 px-2 py-0.5 rounded font-medium">
                                                {{ $sm->boks->nomor_boks }}
                                            </span>
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-400 font-medium whitespace-nowrap ml-4">
                                        {{ $sm->tanggal_surat->format('d M Y') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Recent Surat Keluar -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3 mb-4">
                        <h3 class="font-bold text-gray-800 text-lg">Surat Keluar Terbaru</h3>
                        <a href="{{ route('surat-keluar.index') }}" class="text-xs text-indigo-600 hover:text-indigo-900 font-semibold">Semua &rarr;</a>
                    </div>
                    @if($recentSuratKeluar->isEmpty())
                        <p class="text-sm text-gray-500 py-4">Belum ada surat keluar yang tercatat.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($recentSuratKeluar as $sk)
                                <div class="flex items-start justify-between p-3 hover:bg-gray-50 rounded-md transition duration-150">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $sk->perihal }}</p>
                                        <p class="text-xs text-gray-500 mt-1">No: {{ $sk->nomor_surat }} | Ke: {{ $sk->tujuan }}</p>
                                        <div class="flex items-center space-x-2 mt-1.5">
                                            <span class="text-[10px] bg-blue-50 text-blue-700 px-2 py-0.5 rounded font-medium">
                                                {{ $sk->bidang->nama_bidang }}
                                            </span>
                                            <span class="text-[10px] bg-yellow-50 text-yellow-700 px-2 py-0.5 rounded font-medium">
                                                {{ $sk->boks->nomor_boks }}
                                            </span>
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-400 font-medium whitespace-nowrap ml-4">
                                        {{ $sk->tanggal_surat->format('d M Y') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
