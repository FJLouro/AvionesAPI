<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Fabricante;
use App\Avion;
use Response;

//Activamos el uso de la caché
use Illuminate\Support\Facades\Cache;

class FabricanteAvionController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($idFabricante)
	{
		//Mostramos todos los aviones de un fabricante
		$fabricante=Fabricante::find($idFabricante);

		if (! $fabricante)
		{
			//En code podriamos indicar un codigo de error personalizado
			//de nuestra aplicacion si lo deseamos
			return response()->json(['errors'=>Array(['code'=>404,'message'=>'No se encuentra un fabricante con ese codigo.'])],404);
		}

		$listaAviones=Cache::remember('cacheaviones',1,function()
		{
			return $fabricante->aviones()->get();
		});

		return response()->json(['status'=>'ok','data'=>$listaAviones],200);

		//Respuesta sin cache
		//return response()->json(['status'=>'ok','data'=>$fabricante->aviones()->get()],200);
		//Otra forma
		//return response()->json(['status'=>'ok','data'=>$fabricante->aviones],200);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($idFabricante, Request $request)
	{
		//Damos de alta un avion de un fabricante
		//Comprobamos que recibimos todos los datos de avion
		if(! $request->input('modelo') || ! $request->input('longitud')
		 || ! $request->input('capacidad') || ! $request->input('velocidad') || ! $request->input('alcance') )
		{
			// NO estamos recibiendo los campos necesarios. Devolvemos error. 422 Unprocessable Entity
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan datos necesarios para procesar el alta.'])],422);
		}	

		//Compruebo si existe el fabricante
		$fabricante=Fabricante::find($idFabricante);
		
		if (! $fabricante)
		{
			//En code podriamos indicar un codigo de error personalizado
			//de nuestra aplicacion si lo deseamos
			return response()->json(['errors'=>Array(['code'=>404,'message'=>'No se encuentra un fabricante con ese codigo.'])],404);
		}

		//Damos de alta el avion de ese fabricante
		
		$nuevoAvion=$fabricante->aviones()->create($request->all());

		//Devolvemos un JSON con los datos codigo 201 Created y Location del nuevo recurso creado
		$respuesta = Response::make(json_encode(['data'=>$nuevoAvion]),201)->header('Location','http://www.dominio.local/aviones/' + $nuevoAvion->serie)->header('Content-Type','application/json');

		return $respuesta;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($idFabricante, $idAvion, Request $request)
	{
		//Comprobamos si el fabricante existe
		$fabricante=Fabricante::find($idFabricante);

		if(! $fabricante){
			//Se devuelve un array errors con los errores detectados y codigo 404
			return response()->json(['errors'=>Array(['code'=>404,'message'=>'No se encuentra un fabricante con ese codigo.'])],404);
		}

		//Comprobamos si el avion que buscamos es de ese fabricante
		$avion = $fabricantes->aviones()->find($idAvion);

		if(! $avion){
			//Se devuelve un array errors con los errores detectados y codigo 404
			return response()->json(['errors'=>Array(['code'=>404,'message'=>'No se encuentra un avion con ese codigo asociado al fabricante.'])],404);
		}

		//Almacenamos en variables para facilitar el uso, los campos recibidos
		$modelo=$request->input('modelo');
		$longitud=$request->input('longitud');
		$capacidad=$request->input('capacidad');
		$velocidad=$request->input('velocidad');
		$alcance=$request->input('alcance');

		//Comprobamos el metodo si es PATCH o PUT
		if($request->method()=='PATCH') //Actualizacion parcial
		{
			$bandera=false;

			//Actualizacion parcial de los datos
			if($modelo)
			{
				$avion->modelo=$modelo;
				$bandera=true;
			}

			if($longitud)
			{
				$avion->longitud=$longitud;
				$bandera=true;
			}

			if($capacidad)
			{
				$avion->capacidad=$capacidad;
				$bandera=true;
			}

			if($velocidad)
			{
				$avion->velocidad=$velocidad;
				$bandera=true;
			}

			if($alcance)
			{
				$avion->alcance=$alcance;
				$bandera=true;
			}

			//Comprobamos la bandera
			if($bandera)
			{
				//Almacenamos los cambios en la tabla
				$avion->save();

				//Devolvemos un codigo 200. OK
				return response()->json(['status'=>'ok','data'=>$avion],200);
			}
			else
			{
				//Devolvemos un codigo 304 Not Modified. En caso contrario
				return response()->json(['errors'=>array(['code'=>304,'message'=>'No se ha modificado ningun dato del avion'])],304);
			}

		}

		//Metodo PUT (actualizacion total)
		//Comprobamos que recibimos todos los campos
		if(!$modelo || !$longitud || !$capacidad || !$velocidad || !$alcance)
		{
			//Se devuelve codigo 422 Unprocessable Entity
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan valores para completar el procesamiento'])],422);
		}



		//Actualizamos los 5 campos;
		$avion->modelo=$modelo;
		$avion->longitud=$longitud;
		$avion->capacidad=$capacidad;
		$avion->velocidad=$velocidad;
		$avion->alcance=$alcance;

		//Grabamos los datos de avion en la tabla
		$avion->save();
		return response()->json(['status'=>'ok','data'=>$fabricante],200);

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($idFabricante, $idAvion)
	{
		//Corresponde con la ruta /fabricantes/{fabricante} por DELETE
		//Borrado de un fabricante
		$fabricante=Fabricante::find($idFabricante);

		//Chequeamos si existe o no el fabricante
		if(! $fabricante){
			//Se devuelve un array errors con los errores detectados y codigo 404
			return response()->json(['errors'=>Array(['code'=>404,'message'=>'No se encuentra un fabricante con ese codigo.'])],404);
		}

		$avion=$fabricante->aviones()->find($idAvion);

		//Chequeamos si existe o no el avion asociado a ese fabricante
		if(! $avion){
			//Se devuelve un array errors con los errores detectados y codigo 404
			return response()->json(['errors'=>Array(['code'=>404,'message'=>'No se encuentra un avion con ese codigo.'])],404);
		}

		// Borramos los datos del avion en la tabla.
		//Devolvemos codigo 204, 204 significa "No Content"
		//Este codigo no muestra texto en el body, si quisieramos ver el mensaje devolveriamos un codigo 200
		$avion->delete();

		return response()->json(['code'=>204,'message'=>'Se ha eliminado correctamente el avion.'],204);
	}

}
