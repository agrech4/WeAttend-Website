<?php
define('DATABASE_NAME', 'WEATTEND_WeAttend');
define('DATABASE_READER', 'weattend_reader');
define('DATABASE_READER_PWD', 'r');
define('DATABASE_WRITER_PWD', 'w');
define('DATABASE_WRITER', 'weattend_writer');

define('ADMIN_EMAIL', get_current_user() . '@uvm.edu');

define('LINE_BREAK', '\n');

//This breaks things
// sanitize the server global variable
//$_SERVER = filter_input_array(INPUT_SERVER, FILTER_SANITIZE_STRING);

// sanatize GET global variables
if (!empty($_GET)) {
    $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
}

define('DEBUG', false);

define ('SERVER', htmlentities($_SERVER['SERVER_NAME'], ENT_QUOTES, 'UTF-8'));

define('DOMAIN', '//' . SERVER);

define ('PHP_SELF', htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'));

$PATH_PARTS = pathinfo(PHP_SELF);

define ('FILE_NAME', $PATH_PARTS['filename']);

define ('BASE_PATH', DOMAIN . $PATH_PARTS['dirname'] . '/');

// sometimes you want to know where www-root is located in relation to where you
// are. Just count the / and then create the path
$www_rootPath='';
for($i=1; $i<substr_count(PHP_SELF, '/'); $i++){
    $www_rootPath .= '../';
}

define('WEB_ROOT_PATH', $www_rootPath);

// generally I put my passwords outside of the www-root folder so it is not
// in a public folder at all. The web server can access it so still don't
// print your passwords with php code
define('BIN_PATH', $www_rootPath . 'bin');

// here my lib folder is just in the same folder but you may have set up your
// lib folder in the www-root so its common to all your projects. If that is the
// case you would just define it like the bin path without going up a level more:
// define('LIB_PATH', $www_rootPath . 'lib');
define('LIB_PATH', 'lib');

if (DEBUG) {
    print '<p>Domain: ' . DOMAIN;
    print '<p>php Self: ' . PHP_SELF;
    print '<p>Path Parts<pre>';
    print_r($PATH_PARTS);
    print '</pre></p>';
    print '<p>BASE_PATH: ' . BASE_PATH;
    print '<p>WEB_ROOT_PATH: ' . WEB_ROOT_PATH;
    print '<p>BIN_PATH: ' . BIN_PATH;
    print '<p>LIB_PATH: ' . LIB_PATH;
}
?>
