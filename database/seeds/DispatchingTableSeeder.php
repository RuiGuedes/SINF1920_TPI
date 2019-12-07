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
     */
    public function run()
    {
        $so = new SalesOrders();
        $pw = new PickingWaves();
        $salesOrders = DataSalesOrders::allSalesOrders();
        $user_id = User::first()['id'];

        foreach($salesOrders as $saleOrder) {
            $pw->id = 1;
            $pw->num_orders = 1;
            $pw->save();

            $so->create([
                "id" => $saleOrder['id'],
                "client" => $saleOrder['owner'],
                "picking_wave_id" => 1,
                "date" => $saleOrder['date']
            ]);

            $dispatching = new Dispatching;
            $dispatching['sales_order_id'] = $saleOrder['id'];
            $dispatching['user_id'] = $user_id;
            $dispatching->save();
        }

        $this->command->info('Database dispatching table seeded!');
    }
}
