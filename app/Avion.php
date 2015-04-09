<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Avion extends Model {

	//Definir la tabla de MySQL que usara este modelo.
	protected $table="aviones";

	//Clave primaria de la tabla
	//En este caso es el campo serie, por lo tanto hay que indicarlo.
	//Si no se indica, por defecto seria un campo llamado "id".
	protected $primaryKey = 'serie';

	//Atributos de la tabla que se pueden rellenar de forma masiva.
	protected $fillable=array('modelo','longitud','capacidad','velocidad','alcance');

	//Campos que no queremos que se devuelvan en las consultas.
	protected $hidden=['created_at','updated_at'];

	//Definimos la relacion de Avion con Fabricante
	public function fabricante(){
		//1 avion pertenece a un fabricante
		return $this->belongsTo('App\Fabricante');
	}
}
