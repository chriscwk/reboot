<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
        	[
	        	'email' => 'chris@gmail.com',
	        	'password' => bcrypt('Chris@123'),
	        	'first_name' => 'Christopher',
	        	'last_name' =. 'Chan',
	        	'phone_number' => '(+6) 016-4139884',
	        	'birth_date' => '21/10/1997',
	            'created_at' => Carbon::now(),
	            'updated_at' => Carbon::now()
        	],
        	[
	        	'email' => 'andrew@gmail.com',
	        	'password' => bcrypt('Andrew@123'),
	        	'first_name' => 'Andrew',
	        	'last_name' =. 'Khor',
	        	'phone_number' => '(+6) 016-8769857',
	        	'birth_date' => '04/12/1997',
	            'created_at' => Carbon::now(),
	            'updated_at' => Carbon::now()
        	],
        	[
	        	'email' => 'kit@gmail.com',
	        	'password' => bcrypt('Kit@123'),
	        	'first_name' => 'Kit',
	        	'last_name' =. 'Hoong',
	        	'phone_number' => '(+6) 016-5462986',
	        	'birth_date' => '12/05/1997',
	            'created_at' => Carbon::now(),
	            'updated_at' => Carbon::now()
        	]
    	);
    }
}
