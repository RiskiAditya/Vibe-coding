<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create users
        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('Admin123'),
            'role' => 'admin',
        ]);

        $staff = User::create([
            'username' => 'staff',
            'email' => 'staff@example.com',
            'password' => Hash::make('Staff123'),
            'role' => 'staff',
        ]);

        $borrower = User::create([
            'username' => 'borrower',
            'email' => 'borrower@example.com',
            'password' => Hash::make('Borrower123'),
            'role' => 'borrower',
        ]);

        // Create categories
        $electronics = Category::create(['name' => 'Electronics']);
        $tools = Category::create(['name' => 'Tools']);
        $sports = Category::create(['name' => 'Sports Equipment']);
        $office = Category::create(['name' => 'Office Equipment']);

        // Create equipment
        Equipment::create([
            'name' => 'Laptop Dell XPS 15',
            'category_id' => $electronics->id,
            'status' => 'available',
            'stock' => 5,
            'available_stock' => 5,
            'description' => 'High-performance laptop for development and design work.',
        ]);

        Equipment::create([
            'name' => 'Projector Epson EB-X41',
            'category_id' => $electronics->id,
            'status' => 'available',
            'stock' => 3,
            'available_stock' => 3,
            'description' => 'Portable projector for presentations and meetings.',
        ]);

        Equipment::create([
            'name' => 'Power Drill Bosch GSB 13 RE',
            'category_id' => $tools->id,
            'status' => 'available',
            'stock' => 4,
            'available_stock' => 4,
            'description' => 'Professional power drill for various applications.',
        ]);

        Equipment::create([
            'name' => 'Digital Camera Canon EOS 90D',
            'category_id' => $electronics->id,
            'status' => 'available',
            'stock' => 2,
            'available_stock' => 2,
            'description' => 'DSLR camera for photography and videography.',
        ]);

        Equipment::create([
            'name' => 'Basketball',
            'category_id' => $sports->id,
            'status' => 'available',
            'stock' => 10,
            'available_stock' => 10,
            'description' => 'Official size basketball for indoor and outdoor use.',
        ]);

        Equipment::create([
            'name' => 'Whiteboard Portable',
            'category_id' => $office->id,
            'status' => 'available',
            'stock' => 6,
            'available_stock' => 6,
            'description' => 'Portable whiteboard for meetings and brainstorming.',
        ]);

        Equipment::create([
            'name' => 'Microphone Shure SM58',
            'category_id' => $electronics->id,
            'status' => 'maintenance',
            'stock' => 3,
            'available_stock' => 0,
            'description' => 'Professional microphone for events and presentations.',
        ]);

        Equipment::create([
            'name' => 'Ladder Aluminum 6ft',
            'category_id' => $tools->id,
            'status' => 'available',
            'stock' => 2,
            'available_stock' => 2,
            'description' => 'Lightweight aluminum ladder for various tasks.',
        ]);

        $this->command->info('Demo data seeded successfully!');
        $this->command->info('Admin credentials: admin / Admin123');
        $this->command->info('Staff credentials: staff / Staff123');
        $this->command->info('Borrower credentials: borrower / Borrower123');
    }
}
