<?php

use Illuminate\Database\Seeder;

//Hace uso del modelo Fabricante
use App\Fabricante;

//Usamos el Faker que instalamos antes.
use Faker\Factory as Faker;

class FabricanteSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		//Creamos una isntancia de Faker
		$faker=Faker::create();

		//Vamos a cubrir 5 fabricantes.
		for($i=0;$i<5;$i++){
			//Ver indo de Active Record
			Fabricante::create(
				['nombre'=>$faker->word(),
				'direccion'=>$faker->word(),
				'telefono'=>$faker->randomNumber()
				]
				);
		}
	}

}