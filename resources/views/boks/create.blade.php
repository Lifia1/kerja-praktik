<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Boks Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <form action="{{ route('boks.store') }}" method="POST">
                        @csrf

                        <!-- Nomor Boks -->
                        <div class="mb-4">
                            <x-input-label for="nomor_boks" :value="__('Nomor Boks')" />
                            <x-text-input id="nomor_boks" class="block mt-1 w-full" type="text" name="nomor_boks" :value="old('nomor_boks')" required autofocus placeholder="Contoh: Boks-01" />
                            <x-input-error :messages="$errors->get('nomor_boks')" class="mt-2" />
                        </div>

                        <!-- Lokasi Rak -->
                        <div class="mb-4">
                            <x-input-label for="lokasi_rak" :value="__('Lokasi Rak')" />
                            <x-text-input id="lokasi_rak" class="block mt-1 w-full" type="text" name="lokasi_rak" :value="old('lokasi_rak')" required placeholder="Contoh: Rak A-1" />
                            <x-input-error :messages="$errors->get('lokasi_rak')" class="mt-2" />
                        </div>

                        <!-- Keterangan -->
                        <div class="mb-6">
                            <x-input-label for="keterangan" :value="__('Keterangan (Opsional)')" />
                            <textarea id="keterangan" name="keterangan" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 border focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Keterangan isi boks atau catatan lokasi khusus">{{ old('keterangan') }}</textarea>
                            <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('boks.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition ease-in-out duration-150">
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
