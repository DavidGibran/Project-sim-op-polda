<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $this->get('/admin/dashboard')->assertRedirect('/login');
});

test('authenticated admins can visit the dashboard', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']));

    $this->get('/admin/dashboard')->assertOk();
});
