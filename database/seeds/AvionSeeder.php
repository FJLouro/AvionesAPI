<?php

use Illuminate\Database\Seeder;

use App\Avion;
use App\Fabricante;

use Faker\Factory as Faker;

class AvionSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$faker=Faker::create();

		$cuantos=Fabricante::all()->count();

		//Bucle para 20 aviones
		for($i=0;$i<19;$i++){
			//Ver indo de Active Record
			Avion::create(
				['modelo'=>$faker->word(),
				'longitud'=>$faker->randomFloat(),
				'capacidad'=>$faker->randomNumber(),
				'velocidad'=>$faker->randomNumber(),
				'alcance'=>$faker->randomNumber(),
				'fabricante_id'=>$faker->numberBetween(1,$cuantos)
				]
				);
		}
	}

}