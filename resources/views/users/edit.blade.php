<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User / Operator') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <form action="{{ route('users.update', $user->id) }}" method="POST" x-data="{ role: '{{ old('role', $user->role) }}' }">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Nama Lengkap')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="mb-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Role Selection -->
                        <div class="mb-4">
                            <x-input-label for="role" :value="__('Role Akses')" />
                            <select id="role" name="role" x-model="role" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 border focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="operator">Operator Bidang</option>
                                <option value="admin">Administrator</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <!-- Bidang Asosiasi (Only show for operator) -->
                        <div class="mb-4" x-show="role === 'operator'" x-transition>
                            <x-input-label for="bidang_id" :value="__('Bidang Asosiasi')" />
                            <select id="bidang_id" name="bidang_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 border focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Pilih Bidang...</option>
                                @foreach($bidangs as $b)
                                    <option value="{{ $b->id }}" {{ old('bidang_id', $user->bidang_id) == $b->id ? 'selected' : '' }}>
                                        {{ $b->nama_bidang }} ({{ $b->kode_bidang }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('bidang_id')" class="mt-2" />
                        </div>

                        <hr class="my-6 border-gray-100" />
                        <p class="text-sm font-semibold text-gray-800 mb-2">Ubah Password (Kosongkan jika tidak ingin diubah)</p>

                        <!-- Password -->
                        <div class="mb-4">
                            <x-input-label for="password" :value="__('Password Baru')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-6">
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition ease-in-out duration-150">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Perbarui') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
