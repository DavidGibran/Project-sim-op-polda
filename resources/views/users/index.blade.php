@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Users" />

    <div class="space-y-6">
        @session('success')
            <x-ui.alert variant="success">
                {{ $value }}
            </x-ui.alert>
        @endsession

        <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Admin Accounts</h3>
            </div>
            <div class="max-w-full overflow-x-auto custom-scrollbar">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Nama</p>
                            </th>
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Email</p>
                            </th>
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Created At</p>
                            </th>
                            <th class="px-5 py-3 text-center sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Aksi</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <td class="px-5 py-4 sm:px-6">
                                    <p class="text-gray-900 font-medium text-theme-sm dark:text-white">{{ $user->name }}</p>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $user->email }}</p>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $user->created_at->format('d M Y') }}</p>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('users.edit', $user) }}"
                                            class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-gray-500">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($users->hasPages())
                <div class="p-5 border-t border-gray-100 dark:border-gray-800">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        {{-- Kendaraan Table Section --}}
        <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Kendaraan Accounts</h3>
            </div>
            <div class="max-w-full overflow-x-auto custom-scrollbar">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Nama Pemegang</p>
                            </th>
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">No. Polisi</p>
                            </th>
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Merk / Tipe</p>
                            </th>
                            <th class="px-5 py-3 text-center sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Aksi</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kendaraans as $kend)
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <td class="px-5 py-4 sm:px-6">
                                    <p class="text-gray-900 font-medium text-theme-sm dark:text-white">{{ $kend->nama_pemegang ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $kend->no_polisi }}</p>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $kend->merk }} {{ $kend->tipe }}</p>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('kendaraan.show', $kend->id_kend) }}"
                                            class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]"
                                            title="View Detail">
                                            Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-gray-500">No vehicles found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($kendaraans->hasPages())
                <div class="p-5 border-t border-gray-100 dark:border-gray-800">
                    {{ $kendaraans->appends(['page' => $users->currentPage()])->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

