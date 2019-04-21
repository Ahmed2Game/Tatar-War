<?php

// Base application directory
$base_dir = getcwd() . "/";
chdir($base_dir);
set_include_path($base_dir);

// base folder
define("BASE_DIR", $base_dir);
define("BASE_NAME", basename($base_dir));

// base folders
define("SYSTEM_DIR", "system/");
define("CACHE_DIR", "cache/");
define("APPLICATION_DIR", "$app/");
define("ASSETS_DIR", "assets/");


// Rain folders
define("LIBRARY_DIR", "system/library/");
define("LANGUAGE_DIR", "system/language/");
define("CONSTANTS_DIR", "system/const/");
define("LOG_DIR", "system/log/");

// website folders
define("UPLOADS_DIR", ASSETS_DIR . "uploads/");
define("JAVASCRIPT_DIR", ASSETS_DIR . "js/");
define("CSS_DIR", ASSETS_DIR . "css/");

// admin application folders
define("MODELS_DIR", "$app/models/");
define("VIEWS_DIR", "$app/views/default/");
define("CONTROLLERS_DIR", "$app/controllers/");



// -- end