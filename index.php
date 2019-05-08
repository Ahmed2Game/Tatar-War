<?php
#--------------------------------
# Base application directory
#--------------------------------
$app = "Applic";
 

#--------------------------------
# Base server directory
#--------------------------------
define("CONFIG_DIR", "settings/");

#--------------------------------
# Load the class
#--------------------------------
require_once CONFIG_DIR . "directory.php";


#--------------------------------
# Load the bootstrap
#--------------------------------
require_once "$app/bootstrap.php";


// -- end