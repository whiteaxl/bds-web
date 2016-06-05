<?php
/* =============================================================== *\
|		Module name:	AJAX Index									|
|		Module version:	1.0											|
\* =============================================================== */

define("IN_SITE", true);
define("IN_INDEX", true);
define("PHP_EX", ".php");
include("constant". PHP_EX);
include("config". PHP_EX);

//error_reporting(E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables
set_magic_quotes_runtime(0); // Disable magic_quotes_runtime

include("./includes/mysql". PHP_EX);
include("./includes/info_home". PHP_EX);

//Database driver
$DB		= new DBSql;

//Info of site
$Info	= new Info;
$Info->get_common();

//Run the module
$mod_file	= "./modules/ajax/". $Info->get_mod() . PHP_EX;
if ( file_exists($mod_file) ){
	include($mod_file);
}
//-----------------------------------------------------

//Close mysql
$DB->close();
?>