<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Versionado de la API
//Las rutas quedaran algo como /api/v1.0/rutas existentes....
// /api/v1.0/fabricante/....

Route::group(array('prefix'=>'api/v1.0'),function()
{



//Creamos las rutas nuevas que tendran en cuenta
//los controllers programados en Controllers.
// Ruta /fabricantes/...
Route::resource('fabricantes','FabricanteController',['except'=>['edit','create']]);

//Recurso anidado /fabricantes/xx/aviones
Route::resource('fabricantes.aviones','FabricanteAvionController',['except'=>['edit','create','show']]);

// Ruta /aviones/... El resto de metolos los gestiona FabricanteAvion
Route::resource('aviones','AvionController',['only'=>['index','show']]);

// Ruta por defecto http://www.dominio.local/api/v1.0
Route::get('/', function(){
	return "Bienvenido API RESTful de Aviones";
});

}); // Fin del versionado


//Ruta por defecto al entrar en http://www.dominio.local
Route::get('/', function(){
	return "<a href='http://www.dominio.local/api/v1.0/'>Por favor acceda a la version v1.0 de la API.</a>";
});

/*

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);


*/