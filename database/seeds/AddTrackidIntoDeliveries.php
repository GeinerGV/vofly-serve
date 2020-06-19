<?php

use App\Delivery;
use Illuminate\Database\Seeder;

class AddTrackidIntoDeliveries extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
		/**
		 * @var Delivery[]
		 */
		$deliveries = Delivery::whereNull("trackid")->get();
		foreach ($deliveries as $value) {
			$value->trackid = hash('adler32', $value->id) . hash('adler32', time());
			$value->save();
		}
    }
}
