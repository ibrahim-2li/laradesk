<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrdersStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (OrdersStatuses::count() === 0) {
            $now = Carbon::now();
            OrdersStatuses::insert([
                ['name' => 'Requested', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Request sent', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Request pending', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }
    }
}
