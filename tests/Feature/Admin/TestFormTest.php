<?php

use App\Models\User;
use Database\Seeders\PermissionRoleSeeder;

test('the test form validates every field', function () {
    $this->seed(PermissionRoleSeeder::class);

    $this->actingAs(User::factory()->admin()->create())
        ->post(route('admin.test-form.store'), [])
        ->assertSessionHasErrors([
            'text', 'textarea', 'taglistbox', 'tags', 'switch', 'select', 'radio', 'phone',
            'password', 'number', 'markdown', 'image', 'file', 'combobox', 'checkbox', 'date', 'datetime',
        ]);
});
