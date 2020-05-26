<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Admin;
use App\User;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		if (!User::where("uid", "RwkwIRDfxySPBL7pU1aMKSrvixC2")->first()) {
			DB::table('users')->insert([
				'name' => 'Admin',
				'phone' => '+00000000000',
				'email' => 'voflyapp@gmail.com',
				'uid' => 'RwkwIRDfxySPBL7pU1aMKSrvixC2',
				"api_token" => 'RwkwIRDfxySPBL7pU1aMKSrvixC2',
				'password' => Hash::make("@dmin123..789"),
			]);
		}
		$user = User::where("uid", "RwkwIRDfxySPBL7pU1aMKSrvixC2")->first();
		if (!$user->admin) {
			$admin = new Admin;
			$admin->user()->associate($user);
			$admin->push();
		}
	}
}
