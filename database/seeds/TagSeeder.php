<?php

use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tags')->delete();

        $tags = [
            [ 'tag_name' => 'device_type', 'configuration_id' => 1, 'tag_id' => 1 ],
            [ 'tag_name' => 'device_type', 'configuration_id' => 2, 'tag_id' => 1 ],
            [ 'tag_name' => 'device_type', 'configuration_id' => 3, 'tag_id' => 1 ],
            [ 'tag_name' => 'device_type', 'configuration_id' => 4, 'tag_id' => 1 ],
            [ 'tag_name' => 'device_type', 'configuration_id' => 5, 'tag_id' => 1 ],
            [ 'tag_name' => 'device_type', 'configuration_id' => 6, 'tag_id' => 1 ],
            [ 'tag_name' => 'device_type', 'configuration_id' => 7, 'tag_id' => 1 ],
            [ 'tag_name' => 'device_type', 'configuration_id' => 8, 'tag_id' => 1 ],
            [ 'tag_name' => 'device_type', 'configuration_id' => 9, 'tag_id' => 120 ],
            [ 'tag_name' => 'device_type', 'configuration_id' => 10, 'tag_id' => 229 ],
            
            [ 'tag_name' => 'software_version', 'configuration_id' => 1, 'tag_id' => 4 ],
            [ 'tag_name' => 'software_version', 'configuration_id' => 2, 'tag_id' => 4 ],
            [ 'tag_name' => 'software_version', 'configuration_id' => 3, 'tag_id' => 4 ],
            [ 'tag_name' => 'software_version', 'configuration_id' => 4, 'tag_id' => 4 ],
            [ 'tag_name' => 'software_version', 'configuration_id' => 5, 'tag_id' => 4 ],
            [ 'tag_name' => 'software_version', 'configuration_id' => 6, 'tag_id' => 4 ],
            [ 'tag_name' => 'software_version', 'configuration_id' => 7, 'tag_id' => 4 ],
            [ 'tag_name' => 'software_version', 'configuration_id' => 8, 'tag_id' => 4 ],
            [ 'tag_name' => 'software_version', 'configuration_id' => 9, 'tag_id' => 99 ],
            [ 'tag_name' => 'software_version', 'configuration_id' => 10, 'tag_id' => 4 ],

            [ 'tag_name' => 'software_build', 'configuration_id' => 1, 'tag_id' => 5 ],
            [ 'tag_name' => 'software_build', 'configuration_id' => 2, 'tag_id' => 5 ],
            [ 'tag_name' => 'software_build', 'configuration_id' => 3, 'tag_id' => 5 ],
            [ 'tag_name' => 'software_build', 'configuration_id' => 4, 'tag_id' => 5 ],
            [ 'tag_name' => 'software_build', 'configuration_id' => 5, 'tag_id' => 5 ],
            [ 'tag_name' => 'software_build', 'configuration_id' => 6, 'tag_id' => 5 ],
            [ 'tag_name' => 'software_build', 'configuration_id' => 7, 'tag_id' => 5 ],
            [ 'tag_name' => 'software_build', 'configuration_id' => 8, 'tag_id' => 5 ],

            [ 'tag_name' => 'serial_number_month', 'configuration_id' => 1, 'tag_id' => 6 ],
            [ 'tag_name' => 'serial_number_month', 'configuration_id' => 2, 'tag_id' => 6 ],
            [ 'tag_name' => 'serial_number_month', 'configuration_id' => 3, 'tag_id' => 6 ],
            [ 'tag_name' => 'serial_number_month', 'configuration_id' => 4, 'tag_id' => 6 ],
            [ 'tag_name' => 'serial_number_month', 'configuration_id' => 5, 'tag_id' => 6 ],
            [ 'tag_name' => 'serial_number_month', 'configuration_id' => 6, 'tag_id' => 6 ],
            [ 'tag_name' => 'serial_number_month', 'configuration_id' => 7, 'tag_id' => 6 ],
            [ 'tag_name' => 'serial_number_month', 'configuration_id' => 8, 'tag_id' => 6 ],

            [ 'tag_name' => 'serial_number_year', 'configuration_id' => 1, 'tag_id' => 7 ],
            [ 'tag_name' => 'serial_number_year', 'configuration_id' => 2, 'tag_id' => 7 ],
            [ 'tag_name' => 'serial_number_year', 'configuration_id' => 3, 'tag_id' => 7 ],
            [ 'tag_name' => 'serial_number_year', 'configuration_id' => 4, 'tag_id' => 7 ],
            [ 'tag_name' => 'serial_number_year', 'configuration_id' => 5, 'tag_id' => 7 ],
            [ 'tag_name' => 'serial_number_year', 'configuration_id' => 6, 'tag_id' => 7 ],
            [ 'tag_name' => 'serial_number_year', 'configuration_id' => 7, 'tag_id' => 7 ],
            [ 'tag_name' => 'serial_number_year', 'configuration_id' => 8, 'tag_id' => 7 ],

            [ 'tag_name' => 'serial_number_unit', 'configuration_id' => 1, 'tag_id' => 8 ],
            [ 'tag_name' => 'serial_number_unit', 'configuration_id' => 2, 'tag_id' => 8 ],
            [ 'tag_name' => 'serial_number_unit', 'configuration_id' => 3, 'tag_id' => 8 ],
            [ 'tag_name' => 'serial_number_unit', 'configuration_id' => 4, 'tag_id' => 8 ],
            [ 'tag_name' => 'serial_number_unit', 'configuration_id' => 5, 'tag_id' => 8 ],
            [ 'tag_name' => 'serial_number_unit', 'configuration_id' => 6, 'tag_id' => 8 ],
            [ 'tag_name' => 'serial_number_unit', 'configuration_id' => 7, 'tag_id' => 8 ],
            [ 'tag_name' => 'serial_number_unit', 'configuration_id' => 8, 'tag_id' => 8 ],

            [ 'tag_name' => 'capacity_utilization', 'configuration_id' => 1, 'tag_id' => 2 ],
            [ 'tag_name' => 'capacity_utilization', 'configuration_id' => 2, 'tag_id' => 2 ],
            [ 'tag_name' => 'capacity_utilization', 'configuration_id' => 3, 'tag_id' => 2 ],
            [ 'tag_name' => 'capacity_utilization', 'configuration_id' => 4, 'tag_id' => 2 ],
            [ 'tag_name' => 'capacity_utilization', 'configuration_id' => 5, 'tag_id' => 2 ],
            [ 'tag_name' => 'capacity_utilization', 'configuration_id' => 6, 'tag_id' => 2 ],
            [ 'tag_name' => 'capacity_utilization', 'configuration_id' => 7, 'tag_id' => 2 ],
            [ 'tag_name' => 'capacity_utilization', 'configuration_id' => 8, 'tag_id' => 2 ],
            [ 'tag_name' => 'capacity_utilization', 'configuration_id' => 9, 'tag_id' => 105 ],
            [ 'tag_name' => 'capacity_utilization', 'configuration_id' => 10, 'tag_id' => 230 ],

            [ 'tag_name' => 'energy_consumption', 'configuration_id' => 1, 'tag_id' => 3 ],
            [ 'tag_name' => 'energy_consumption', 'configuration_id' => 2, 'tag_id' => 3 ],
            [ 'tag_name' => 'energy_consumption', 'configuration_id' => 3, 'tag_id' => 3 ],
            [ 'tag_name' => 'energy_consumption', 'configuration_id' => 4, 'tag_id' => 3 ],
            [ 'tag_name' => 'energy_consumption', 'configuration_id' => 5, 'tag_id' => 3 ],
            [ 'tag_name' => 'energy_consumption', 'configuration_id' => 6, 'tag_id' => 3 ],
            [ 'tag_name' => 'energy_consumption', 'configuration_id' => 7, 'tag_id' => 3 ],
            [ 'tag_name' => 'energy_consumption', 'configuration_id' => 8, 'tag_id' => 3 ],
            [ 'tag_name' => 'energy_consumption', 'configuration_id' => 9, 'tag_id' => 106 ],
            [ 'tag_name' => 'energy_consumption', 'configuration_id' => 10, 'tag_id' => 231 ]
            
        ];
        DB::table('tags')->insert($tags);
    }
}
