<?php

use App\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('warehouse')->truncate();

        $data = array();
        $sectionLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

        for($i = 0; $i < count($sectionLetters); $i++) {
            for($j = 1; $j < 9; $j++)
                array_push($data, array('section' => $sectionLetters[$i] . $j));
        }

        Warehouse::insertWarehouseSections($data);
        $this->command->info('Database warehouse table seeded!');
    }
}
