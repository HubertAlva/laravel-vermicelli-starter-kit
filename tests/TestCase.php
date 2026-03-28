<?php

namespace Tests;

use Database\Seeders\PermissionRoleSeeder;
use Illuminate\Database\Events\DatabaseRefreshed;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Event;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Event::listen(DatabaseRefreshed::class, function () {
            $this->artisan('db:seed', ['--class' => PermissionRoleSeeder::class]);
            $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        });

        $this->seed();

        $this->withoutVite();
    }
}
