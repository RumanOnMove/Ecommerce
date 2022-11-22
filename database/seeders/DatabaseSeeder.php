<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Attribute;
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
//         \App\Models\User::factory(1000)->create();
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

        $attributes = [
            'Size' => [
                'Small',
                'Medium',
                'Big',
            ],
            'Color' => [
                'Green',
                'Red',
                'Yellow',
            ]
        ];

        foreach ($attributes as $key=>$values){
            $attribute = Attribute::create([
                'name' => $key,
                'slug' => Str::slug($key)
            ]);
            foreach ($values as $value){
                $attribute->values()->create([
                    'name' => $value,
                    'slug' => Str::slug($value)
                ]);
            }
        }
    }
}
