<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Bidang Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <form action="{{ route('bidang.store') }}" method="POST">
                        @csrf

                        <!-- Nama Bidang -->
                        <div class="mb-4">
                            <x-input-label for="nama_bidang" :value="__('Nama Bidang')" />
                            <x-text-input id="nama_bidang" class="block mt-1 w-full" type="text" name="nama_bidang" :value="old('nama_bidang')" required autofocus placeholder="Contoh: Infrastruktur dan Kewilayahan" />
                            <x-input-error :messages="$errors->get('nama_bidang')" class="mt-2" />
                        </div>

                        <!-- Kode Bidang -->
                        <div class="mb-6">
                            <x-input-label for="kode_bidang" :value="__('Kode Bidang (Singkatan)')" />
                            <x-text-input id="kode_bidang" class="block mt-1 w-full" type="text" name="kode_bidang" :value="old('kode_bidang')" required placeholder="Contoh: IPW" />
                            <p class="text-xs text-gray-500 mt-1">Digunakan untuk penanda arsip dan penomoran internal.</p>
                            <x-input-error :messages="$errors->get('kode_bidang')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('bidang.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition ease-in-out duration-150">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
