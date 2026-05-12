<div class="relative" x-data="{
    dropdownOpen: false,
    profileModalOpen: false,
    showCurrentPassword: false,
    showNewPassword: false,
    showConfirmPassword: false,
    toggleDropdown() {
        this.dropdownOpen = !this.dropdownOpen;
    },
    closeDropdown() {
        this.dropdownOpen = false;
    }
}" @click.away="closeDropdown()">
    @php
        /**
         * Deteksi siapa yang sedang login
         *
         * - Admin login menggunakan Auth::user()
         * - Kendaraan login menggunakan session manual
         */
        $isKendaraan = session()->has('kendaraan_id');
        $authUser = Auth::user();

        /**
         * Nama yang ditampilkan di dropdown
         */
        $displayName = $isKendaraan
            ? session('kendaraan_polisi', 'Kendaraan')
            : ($authUser->name ?? 'Admin');

        /**
         * Subtext / email
         *
         * Untuk kendaraan, karena tidak ada email,
         * kita tampilkan label "Pengemudi".
         */
        $displaySubtext = $isKendaraan
            ? 'Pengemudi'
            : ($authUser->email ?? '-');

        /**
         * Initial avatar
         *
         * - kendaraan: ambil 2 karakter pertama no polisi
         * - admin: gunakan method initials() jika user ada
         */
        $displayInitials = $isKendaraan
            ? strtoupper(substr(session('kendaraan_polisi', 'K'), 0, 2))
            : ($authUser ? $authUser->initials() : 'A');
        /**
         * Get Username for Profile
         */
        $username = $isKendaraan 
            ? \App\Models\MasterKend::where('id_kend', session('kendaraan_id'))->value('username')
            : ($authUser->email ?? '-');
    @endphp

    <!-- User Button -->
    <button
        class="flex items-center text-gray-700 dark:text-gray-400"
        @click.prevent="toggleDropdown()"
        type="button"
    >
        <span
            class="flex items-center justify-center text-center mr-3 overflow-hidden rounded-full h-11 w-11 bg-gray-200 text-black dark:bg-gray-700 dark:text-white">
            {{ $displayInitials }}
        </span>

        <span class="block mr-1 font-medium text-theme-sm">
            {{ $displayName }}
        </span>

        <!-- Chevron Icon -->
        <svg
            class="w-5 h-5 transition-transform duration-200"
            :class="{ 'rotate-180': dropdownOpen }"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Dropdown Start -->
    <div
        x-show="dropdownOpen"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-[17px] flex w-[260px] flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark z-50"
        style="display: none;"
    >
        <!-- User Info -->
        <div>
            <span class="block font-medium text-gray-700 text-theme-sm dark:text-gray-400">
                {{ $displayName }}
            </span>
            <span class="mt-0.5 block text-theme-xs text-gray-500 dark:text-gray-400">
                {{ $displaySubtext }}
            </span>
        </div>

        <!-- Menu Items -->
        @if(! $isKendaraan)
            <ul class="flex flex-col gap-1 pt-4 pb-3 border-b border-gray-200 dark:border-gray-800">
                @php
                    $menuItems = [
                        [
                            'text' => 'Edit profile',
                            'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M12 3.5C7.30558 3.5 3.5 7.30558 3.5 12C3.5 14.1526 4.3002 16.1184 5.61936 17.616C6.17279 15.3096 8.24852 13.5955 10.7246 13.5955H13.2746C15.7509 13.5955 17.8268 15.31 18.38 17.6167C19.6996 16.119 20.5 14.153 20.5 12C20.5 7.30558 16.6944 3.5 12 3.5ZM17.0246 18.8566V18.8455C17.0246 16.7744 15.3457 15.0955 13.2746 15.0955H10.7246C8.65354 15.0955 6.97461 16.7744 6.97461 18.8455V18.856C8.38223 19.8895 10.1198 20.5 12 20.5C13.8798 20.5 15.6171 19.8898 17.0246 18.8566ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9991 7.25C10.8847 7.25 9.98126 8.15342 9.98126 9.26784C9.98126 10.3823 10.8847 11.2857 11.9991 11.2857C13.1135 11.2857 14.0169 10.3823 14.0169 9.26784C14.0169 8.15342 13.1135 7.25 11.9991 7.25ZM8.48126 9.26784C8.48126 7.32499 10.0563 5.75 11.9991 5.75C13.9419 5.75 15.5169 7.32499 15.5169 9.26784C15.5169 11.2107 13.9419 12.7857 11.9991 12.7857C10.0563 12.7857 8.48126 11.2107 8.48126 9.26784Z"
                                    fill="currentColor"
                                />
                            </svg>',
                            'path' => route('settings.profile.edit'),
                        ],
                    ];
                @endphp

                @foreach ($menuItems as $item)
                    <li>
                        <a
                            href="{{ $item['path'] }}"
                            class="flex items-center gap-3 px-3 py-2 font-medium text-gray-700 rounded-lg group text-theme-sm hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
                        >
                            <span class="text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300">
                                {!! $item['icon'] !!}
                            </span>
                            {{ $item['text'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <ul class="flex flex-col gap-1 pt-4 pb-3 border-b border-gray-200 dark:border-gray-800">
                <li>
                    <button
                        @click="profileModalOpen = true; closeDropdown()"
                        class="flex w-full items-center gap-3 px-3 py-2 font-medium text-gray-700 rounded-lg group text-theme-sm hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
                    >
                        <span class="text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12 3.5C7.30558 3.5 3.5 7.30558 3.5 12C3.5 14.1526 4.3002 16.1184 5.61936 17.616C6.17279 15.3096 8.24852 13.5955 10.7246 13.5955H13.2746C15.7509 13.5955 17.8268 15.31 18.38 17.6167C19.6996 16.119 20.5 14.153 20.5 12C20.5 7.30558 16.6944 3.5 12 3.5ZM17.0246 18.8566V18.8455C17.0246 16.7744 15.3457 15.0955 13.2746 15.0955H10.7246C8.65354 15.0955 6.97461 16.7744 6.97461 18.8455V18.856C8.38223 19.8895 10.1198 20.5 12 20.5C13.8798 20.5 15.6171 19.8898 17.0246 18.8566ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9991 7.25C10.8847 7.25 9.98126 8.15342 9.98126 9.26784C9.98126 10.3823 10.8847 11.2857 11.9991 11.2857C13.1135 11.2857 14.0169 10.3823 14.0169 9.26784C14.0169 8.15342 13.1135 7.25 11.9991 7.25ZM8.48126 9.26784C8.48126 7.32499 10.0563 5.75 11.9991 5.75C13.9419 5.75 15.5169 7.32499 15.5169 9.26784C15.5169 11.2107 13.9419 12.7857 11.9991 12.7857C10.0563 12.7857 8.48126 11.2107 8.48126 9.26784Z" fill="currentColor" />
                            </svg>
                        </span>
                        Profil
                    </button>
                </li>
            </ul>
        @endif

        <!-- Sign Out -->
        <form method="POST" action="{{ route('logout.universal') }}">
            @csrf
            <a
                href="{{ route('logout.universal') }}"
                class="flex items-center w-full gap-3 px-3 py-2 mt-3 font-medium text-gray-700 rounded-lg group text-theme-sm hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
                onclick="event.preventDefault(); this.closest('form').submit();"
            >
                <span class="text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </span>
                Sign out
            </a>
        </form>
    </div>
    <!-- Dropdown End -->

    {{-- Modal Profil / Ganti Password --}}
    @if($isKendaraan)
    <div
        x-show="profileModalOpen"
        class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/50 px-4"
        style="display: none;"
        x-cloak
    >
        <div
            @click.away="profileModalOpen = false"
            class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-900"
        >
            <div class="mb-5 flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                    Edit Profil
                </h3>
                <button
                    @click="profileModalOpen = false"
                    class="text-gray-500 hover:text-black dark:hover:text-white"
                >
                    ✕
                </button>
            </div>

            <form action="{{ route('kendaraan.update-password') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    {{-- Username (Read-only) --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Username
                        </label>
                        <input
                            type="text"
                            value="{{ $username }}"
                            readonly
                            class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-500 outline-none dark:border-gray-800 dark:bg-gray-800/50 dark:text-gray-400"
                        >
                    </div>

                    {{-- Current Password --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Password Saat Ini <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                :type="showCurrentPassword ? 'text' : 'password'"
                                name="current_password"
                                required
                                placeholder="Masukkan password lama..."
                                class="w-full rounded-lg border border-gray-200 bg-transparent pl-4 pr-11 py-2.5 text-sm outline-none focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                            >
                            <button
                                type="button"
                                @click="showCurrentPassword = !showCurrentPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                            >
                                <svg x-show="!showCurrentPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showCurrentPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                    </div>

                    <hr class="border-gray-100 dark:border-gray-800">

                    {{-- New Password --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Password Baru <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                :type="showNewPassword ? 'text' : 'password'"
                                name="new_password"
                                required
                                placeholder="Masukkan password baru..."
                                class="w-full rounded-lg border border-gray-200 bg-transparent pl-4 pr-11 py-2.5 text-sm outline-none focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                            >
                            <button
                                type="button"
                                @click="showNewPassword = !showNewPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                            >
                                <svg x-show="!showNewPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showNewPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Confirm New Password --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Konfirmasi Password Baru <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                :type="showConfirmPassword ? 'text' : 'password'"
                                name="new_password_confirmation"
                                required
                                placeholder="Ulangi password baru..."
                                class="w-full rounded-lg border border-gray-200 bg-transparent pl-4 pr-11 py-2.5 text-sm outline-none focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                            >
                            <button
                                type="button"
                                @click="showConfirmPassword = !showConfirmPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                            >
                                <svg x-show="!showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showConfirmPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-7 flex flex-col gap-3">
                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center rounded-lg bg-success-400 dark:bg-success-600 px-5 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition-opacity"
                    >
                        Simpan Perubahan
                    </button>
                    <button
                        type="button"
                        @click="profileModalOpen = false"
                        class="inline-flex w-full items-center justify-center rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-transparent dark:text-gray-300 dark:hover:bg-white/5 transition-colors"
                    >
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>