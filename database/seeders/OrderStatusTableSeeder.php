<?php

namespace Database\Seeders;

use Carbon\Carbon;
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
                ['name' => 'تم الطلب', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'الطلب معلق', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'تم إرسال الطلب', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }
    }
}
