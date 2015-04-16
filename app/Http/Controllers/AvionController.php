<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Avion;

class AvionController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//Devuelve la lista de todos los aviones
		return response()->json(['status'=>'ok','data'=>Avion::all()],200);
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
