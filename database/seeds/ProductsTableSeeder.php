<?php

use App\Http\Middleware\JasminConnect;
use App\Products;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $result = JasminConnect::callJasmin('/materialsCore/materialsItems');
        } catch (Exception $e) {
            return;
        }

        $products = json_decode($result->getBody(), true);

        $sections = ['A' => 1, 'B' => 1, 'C' => 1, 'D' => 1, 'E' => 1, 'F' => 1, 'G' => 1, 'H' => 1];
        $prodToSection = ['Ammo' => 'A', 'MAGs' => 'B', 'Pistol' => 'C',
                          'SMG' => 'D', 'Shotgun' => 'E', 'AssaultRifle' => 'F',
                          'Sniper' => 'G', 'LMG' => 'H'];

        for($i = 0; $i < count($products); $i++) {
            $section = $prodToSection[substr($products[$i]['barcode'], 4)];

            $data = ['product_id' => $products[$i]['itemKey'],
                     'description' => $products[$i]['description'],
                     'min_stock' => $products[$i]['minStock'],
                     'max_stock' => $products[$i]['maxStock'],
                     'stock' => $products[$i]['materialsItemWarehouses'][0]['stockBalance'],
                     'warehouse_section' => $section . $sections[$section]
                    ];

            $sections[$prodToSection[substr($products[$i]['barcode'], 4)]]++;

            if($sections[$prodToSection[substr($products[$i]['barcode'], 4)]] == 9)
                $sections[$prodToSection[substr($products[$i]['barcode'], 4)]] = 1;

            Products::insertProduct($data);
        }

        $this->command->info('Database products table seeded!');
    }
}
