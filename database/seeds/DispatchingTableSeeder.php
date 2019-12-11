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
        $openOrders = DataSalesOrders::openOrders();

        foreach ($openOrders as $openOrder) {
            $salesOrder = $openOrder['salesOrder'];
            $date = substr($salesOrder->documentDate, 0, 10);
            SalesOrders::create([
                'id' => str_replace('.','-',$salesOrder->naturalKey),
                'client' => $salesOrder->buyerCustomerParty,
                'date' => $date
            ]);

            Dispatching::create([
                'sales_order_id' => str_replace('.','-',$salesOrder->naturalKey)
            ]);
        }

        $this->command->info('Database dispatching table seeded!');
    }
}
