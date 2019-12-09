<?php

use App\Dispatching;
use App\Http\Controllers\Data\DataSalesOrders;
use App\Http\Middleware\JasminConnect;
use App\PickingWaves;
use App\Products;
use App\SalesOrders;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DispatchingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        $salesOrders = DataSalesOrders::openOrders();

        foreach ($salesOrders as $saleOrder) {
            SalesOrders::create([
                'id' => $saleOrder['id'],
                'client' => $saleOrder['owner'],
                'date' => $saleOrder['date']
            ]);

            Dispatching::create([
                'sales_order_id' => $saleOrder['id']
            ]);
        }

        $this->command->info('Database dispatching table seeded!');
    }
}
