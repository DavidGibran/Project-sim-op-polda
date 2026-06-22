<?php

use App\Models\User;

test('guest is redirected from admin area', function () {
    $this->get('/admin/dashboard')
        ->assertRedirect(route('login'));
});

test('authenticated non admin is redirected from admin area', function () {
    $user = User::factory()->create(['role' => 'user']);

    $this->actingAs($user)
        ->get('/admin/dashboard')
        ->assertRedirect(route('login'));
});

test('authenticated admin can access admin area', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get('/admin/dashboard')
        ->assertOk();
});
