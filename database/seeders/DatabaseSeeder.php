<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

         $user =User::factory()->create([
             'name' => 'Albert Sans',
         'email' => 'asanscliment@gmail.com',
         ]);
         $role = Role::create(['name' => 'Administrador']);
         $user->assignRole($role);
    }
}
