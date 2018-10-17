<?php

// Base application directory
$base_dir = getcwd() . "/";
chdir( $base_dir );
set_include_path($base_dir);

// base folder
define( "BASE_DIR",					$base_dir );
define( "BASE_NAME",				basename( $base_dir ) );

// base folders
define( "SYSTEM_DIR",				"system/" );
define( "CACHE_DIR",                "cache/" );
define( "APPLICATION_DIR",          "$app/" );
define( "ASSETS_DIR",				"assets" );
define( "SERVER_DIR",               BASE_DIR.'../settings/' );
define( "THEMES_DIR",               BASE_DIR.'../app/views/' );
define( "LANGUAGES_DIR",            BASE_DIR.'../system/language/' );
define( "GAME_ASSETS_DIR",			'../assets/' );
define( "JAVASCRIPT_DIR",           ASSETS_DIR."js/" );
define( "CSS_DIR",                  ASSETS_DIR."css/" );

// Rain folders
define( "LIBRARY_DIR",              "system/library/" );
define( "LANGUAGE_DIR",             "system/language/" );
define( "CONSTANTS_DIR",            "system/const/" );
define( "LOG_DIR",                  "system/log/" );


// admin application folders
define( "MODELS_DIR",				"$app/models/" );
define( "VIEWS_DIR",				"$app/views/" );
define( "CONTROLLERS_DIR",			"$app/controllers/" );



// -- end