<?php

class Model
{

	public function load_model($model,$object_name=null)
	{
		 // transform the model string to capitalized. e.g. user => User, news_list => News_List
		$model = implode( "/", array_map( "ucfirst", array_map( "strtolower", explode( "/", $model ) ) ) );
		$model = implode( "_", array_map( "ucfirst",  explode( "_", $model )  ) );


		// include the file
		if( file_exists($file = MODELS_DIR . $model . ".php") ) {
			require_once $file;
		}
		else{

			trigger_error( "MODEL: FILE <b>{$file}</b> NOT FOUND ", E_USER_WARNING );
			return false;
		}

		if(!$object_name)
			$object_name = $model;

		$tModel = explode("/",$model);
		$class=$tModel[count($tModel)-1];
		$class.="_Model";
		
		if( class_exists($class) ){
			$this->$object_name = new $class;
		}
		else{

			trigger_error( "MODEL: CLASS <b>{$model}</b> NOT FOUND", E_USER_WARNING );
			return false;
		}
		return true;
	}


	/**
	 * Load the library
	 *
	 */
	public function load_library( $library, $object_name = null, $params = NULL){

		if( !$object_name )
			$object_name = $library;

		if( file_exists($file = LIBRARY_DIR . $library . ".php") )
			require_once $file;
		else{
			trigger_error( "LIBRARY: FILE <b>{$file}</b> NOT FOUND ", E_USER_WARNING );
			return false;
		}


		$class = $library;
		if( class_exists($class) )
		{
			if(!is_null($params))
			{
				$this->$object_name = new $class($params);
			}
			else
			{
				$this->$object_name = new $class;
			}
		}
		else{
			trigger_error( "LIBRARY: CLASS <b>{$library}</b> NOT FOUND", E_USER_WARNING );
			return false;
		}
		return true;
	}
}
?>