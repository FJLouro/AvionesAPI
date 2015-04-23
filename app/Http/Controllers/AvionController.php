<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Avion;

//Activamos el uso de la caché
use Illuminate\Support\Facades\Cache;

class AvionController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$listaAviones=Cache::remember('cacheTodosAviones',15/60,function()
		{
			return Aviones::all();
		});


		//Para devolver un JSON con codigo de respuesta HTTP. sin caché
		//return response()->json(['status'=>'ok','data'=>Avion::all()],200);

		//Devolvemos el JSON usando caché
		//Devuelve la lista de todos los aviones
		return response()->json(['status'=>'ok','data'=>$listaAviones],200);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//Buscamos ese avion y si lo encuentra muestra la info
		$avion=Avion::find($id);

		//Chequeamos si encontro o no el avion
		if(! $avion){
			//Se devuelve un array errors con los errores detectados y codigo 404
			return response()->json(['errors'=>Array(['code'=>404,'message'=>'No se encuentra un avion con ese codigo.'])],404);
		}

		//Devolvemos la informacion encontrada.
		return response()->json(['status'=>'ok','data'=>$avion],200);
	}

}
