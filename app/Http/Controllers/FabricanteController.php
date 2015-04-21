<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

//Cargamos Fabricante por que lo usamos mas abajo
use App\Fabricante;

use Response;

class FabricanteController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//return "En el index de Fabricante.";
		//Devolvemos un JSON con todos los fabricantes.
		//return Fabricante::all();

		//Para devolver un JSON con codigo de respuesta HTTP.
		return response()->json(['status'=>'ok','data'=>Fabricante::all()],200);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */

	//No se utiliza este metodo por que se usaria para mostrar un formulario
	//de creacion de Fabricantes. Y una API REST no hace eso
	/*
	public function create()
	{
		//
	}
	*/

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		//Metodo llamado al hacer un POST
		//Comprobamos que recibimos todos los campos.
		if (!$request->input('nombre')|| !$request->input('direccion')|| !$request->input('telefono'))
		{
			// NO estamos recibiendo los campos necesarios. Devolvemos error.
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan datos necesarios para procesar el alta.'])],422);
		}

		// Insertamos los datos recibidos en la tabla.
		$nuevoFabricante=Fabricante::create($request->all());

		//Devolvemos la respuesta Http 201 (Created) + los datos del nuevo fabricante + una cabecera de Location + cabecera JSON
		$respuesta = Response::make(json_encode(['data'=>$nuevoFabricante]),201)->header('Location','http://www.dominio.local/fabricantes/' + $nuevoFabricante->id)->header('Content-Type','application/json');

		return $respuesta;

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//Corresponde con la ruta /fabricantes/{fabricante}
		//Buscamos un fabricante por el ID.
		$fabricante=Fabricante::find($id);

		//Chequeamos si encontro o no el fabricante
		if(! $fabricante){
			//Se devuelve un array errors con los errores detectados y codigo 404
			return response()->json(['errors'=>Array(['code'=>404,'message'=>'No se encuentra un fabricante con ese codigo.'])],404);
		}

		//Devolvemos la informacion encontrada.
		return response()->json(['status'=>'ok','data'=>$fabricante],200);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	/*
	public function edit($id)
	{
		//
	}
	*/

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		//Vamos a actualizar un fabricante
		//Comprobamos si el fabricante existe. En otro caso devolvemos error.
		$fabricante=Fabricante::find($id);

		//Si no existe mostramos error
		if (! $fabricante)
		{
			//Devolvemos error 404
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un fabricante con ese codigo'])],404);
		}

		//Almacenamos en variables para facilitar el uso, los campos recibidos
		$nombre=$request->input('nombre');
		$direccion=$request->input('direccion');
		$telefono=$request->input('telefono');

		//Comprobamso si recibimos peticion PATCH(parcial) o PUT(total)
		if($request->method()=='PATCH')
		{
			$bandera=false;

			//Actualizacion parcial de los datos
			if($nombre != null && $nombre != '')
			{
				$fabricante->nombre=$nombre;
				$bandera=true;
			}

			if($direccion != null && $direccion != '')
			{
				$fabricante->direccion=$direccion;
				$bandera=true;
			}

			if($telefono != null && $telefono != '')
			{
				$fabricante->telefono=$telefono;
				$bandera=true;
			}


			if($bandera)
			{
				//Grabamos el fabricante
				$fabricante->save();

				//Devolvemos un codigo 200
				return response()->json(['status'=>'ok','data'=>$fabricante],200);
			}
			else
			{
				//Devolvemos un codigo 304 Not Modified
				return response()->json(['errors'=>array(['code'=>304,'message'=>'No se ha modificado ningun dato del fabricante'])],304);
			}

		}

		//Metodo PUT actualizamos todos los campos
		//Comprobamos que recibimos todos
		if(!$nombre || !$direccion || !$telefono)
		{
			//Se devuelve codigo 422 Unprocessable Entity
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan valores para completar el procesamiento'])],422);
		}

		//Actualizamos los 3 campos;
		$fabricante->nombre=$nombre;
		$fabricante->direccion=$direccion;
		$fabricante->telefono=$telefono;

		//Grabamos el fabricante
		$fabricante->save();
		return response()->json(['status'=>'ok','data'=>$fabricante],200);


	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//Corresponde con la ruta /fabricantes/{fabricante} por DELETE
		//Borrado de un fabricante
		$fabricante=Fabricante::find($id);

		//Chequeamos si existe o no el fabricante
		if(! $fabricante){
			//Se devuelve un array errors con los errores detectados y codigo 404
			return response()->json(['errors'=>Array(['code'=>404,'message'=>'No se encuentra un fabricante con ese codigo.'])],404);
		}

		// Borramos los datos del fabricante en la tabla.
		//Devolvemos codigo 204, 204 significa "No Content"
		//Este codigo no muestra texto en el body, si quisieramos ver el mensaje devolveriamos un codigo 200
		//Antes de borralo comprobamos si tiene aviones y si es asi 
		//sacamos un mensaje de error
		//$aviones = $fabricante->aviones()->get;
		$aviones = $fabricante->aviones;

		if (sizeof($aviones) >0)
		{
			//Si quisieramos borrar todos los aviones del fabricante seria:
			//$fabricante->aviones->delete();

			//Devolvemos un codigo 409 Conflict.
			return response()->json(['errors'=>Array(['code'=>409,'message'=>'Este fabricante posee aviones y no puede ser eliminado.'])],409);
		}

		//Eliminamos el fabricante si no tiene aviones
		$fabricante->delete();

		//Se devuelve codigo 204 No Content.
		return response()->json(['code'=>204,'message'=>'Se ha eliminado correctamente el fabricante.'],204);
	}

}
