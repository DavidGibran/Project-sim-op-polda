<?php

namespace App\Helpers;

class MenuHelper
{
    public static function getMainNavItems(): array
    {
        return [
            [
                'icon' => 'dashboard',
                'name' => 'Dashboard',
                'path' => route('admin.dashboard', absolute: false),
            ],
            [
                'icon' => 'car',
                'name' => 'Master Kendaraan',
                'path' => route('kendaraan.index', absolute: false),
            ],
            [
                'icon' => 'task',
                'name' => 'Penugasan Kendaraan',
                'path' => route('penugasan.index', absolute: false),
            ],
            [
                'icon' => 'activity',
                'name' => 'Log Pemakaian',
                'path' => route('log.index', absolute: false),
            ],
            [
                'icon' => 'wrench',
                'name' => 'Perbaikan',
                'subItems' => [
                    [
                        'name' => 'Perbaikan Aktif',
                        'path' => route('perbaikan.index', absolute: false) . '?status=aktif',
                    ],
                    [
                        'name' => 'Riwayat Perbaikan',
                        'path' => route('perbaikan.index', absolute: false),
                    ],
                ],
            ],
            [
                'icon' => 'users',
                'name' => 'Manajemen User',
                'path' => route('users.index', absolute: false),
            ],
            [
                'icon' => 'chart',
                'name' => 'Laporan',
                'subItems' => [
                    [
                        'name' => 'Laporan Pemakaian',
                        'path' => '#',
                    ],
                    [
                        'name' => 'Laporan Perbaikan',
                        'path' => '#',
                    ],
                ],
            ],
        ];
    }

    public static function getMenuGroups(): array
    {
        return [
            [
                'title' => 'Menu Utama',
                'items' => self::getMainNavItems()
            ]
        ];
    }

    public static function isActive($path): bool
    {
        return request()->is(ltrim($path, '/'));
    }

    public static function getIconSvg($iconName): string
    {
        $icons = [
            'dashboard' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg>',
            
            'car' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9c-.1.3-.2.6-.2.9V16c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/></svg>',
            
            'task' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/><path d="M9 12h6"/><path d="M9 16h6"/><path d="M9 8h6"/></svg>',
            
            'activity' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>',
            
            'wrench' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
            
            'users' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
            
            'chart' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/></svg>',
            
            'email' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',
        ];

        return $icons[$iconName] ?? '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
    }
}
