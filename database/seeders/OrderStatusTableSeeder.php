<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (OrderStatus::count() === 0) {
            $now = Carbon::now();
            OrderStatus::insert([
                ['name' => 'Requested', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Request sent', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Request pending', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }
    }
}
