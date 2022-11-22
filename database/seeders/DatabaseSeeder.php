<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::factory(1000)->create();
//        $roles = [
//            'Admin',
//            'Customer'
//        ];
//
//        foreach ($roles as $role){
//            Role::create([
//                'name' => $role,
//                'slug' => Str::slug($role),
//                'status' => Role::Status['Active']
//            ]);
//        }
    }
}
