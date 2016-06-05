<?php
/* =============================================================== *\
|		Module name:      Admin Index								|
\* =============================================================== */
error_reporting(E_ALL); // This will NOT report uninitialized variables
define("IN_SITE", true);
define("IN_ADMIN", true);
define("PHP_EX", ".php");
include("constant". PHP_EX);
include("config". PHP_EX);


set_magic_quotes_runtime(0); // Disable magic_quotes_runtime

//Database
include("./includes/mysql". PHP_EX);
$DB = new DBSql;

//Info of site
include("./includes/info_admin". PHP_EX);
$Info  = new Info;

//Template
include("./includes/template". PHP_EX);
$Info->option['template_path']	= './templates/'. $Info->option['template'];
$Template = new Template($Info->option['template_path'] ."/admin");

//Language
include("./languages/". $Info->option['language'] ."/lang_main". PHP_EX);
include("./languages/". $Info->option['language'] ."/admin/lang_admin". PHP_EX);
$Lang  = new Lang_Admin;
$Lang->Lang_Global();//Construction

//Functions
include("./includes/functions". PHP_EX);
include("./includes/functions_admin". PHP_EX);
$Func  = new Func_Admin;

//Image
include("./includes/image". PHP_EX);
$Image  = new Image;

//File Management
include("./includes/file_web". PHP_EX);
$File  = new FileMan;

//Session
include("./includes/sessions". PHP_EX);
$Session = new Session_Page;

//Set global vars
$Template->set_vars(array(
	'SITE_NAME'				=> $Info->option['site_name'],
	"SCRIPT_NAME"			=> $Info->option['script_name'],
	"TEMPLATE_PATH"			=> $Info->option['template_path'],
));

//Check Login
$Func->check_login();

//Run the global file
include("./modules/admin/ad_global". PHP_EX);

//Cache
include("./includes/cache". PHP_EX);
$Cache  = new Cache;
$Cache->cache_onoff($Info->option['cache_enabled']);

//Run the module
$mod_file	= "./modules/admin/". $Info->get_mod() . PHP_EX;
if ( file_exists($mod_file) ){
	include($mod_file);
}

//-----------------------------------------------------
$DB->close();
if ( isset($FTP) ){
	$FTP->close();
}

//Ready Template to out put
if ( !empty($Info->tpl_header) ){
	$Template->set_files(array(
		"header"	=> $Info->tpl_header .".tpl",
	));
}
if ( !empty($Info->tpl_main) ){
	$Template->set_files(array(
		"main"	=> $Info->tpl_main .".tpl",
	));
}
if ( !empty($Info->tpl_footer) ){
	$Template->set_files(array(
		"footer"	=> $Info->tpl_footer .".tpl",
	));
}
$Template->show();

?>