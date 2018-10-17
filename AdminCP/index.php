<?php

#--------------------------------
# Base application directory
#--------------------------------
$app = "app";

#--------------------------------
# Base server directory
#--------------------------------
define( "CONFIG_DIR",  "config/" );


#--------------------------------
# Load the class
#--------------------------------
require_once "config/directory.php";


#--------------------------------
# Load the bootstrap
#--------------------------------
require_once "$app/bootstrap.php";


// -- end