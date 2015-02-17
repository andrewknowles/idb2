<?php

define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APP', ROOT . 'idb2/application' . DIRECTORY_SEPARATOR);
define('DB', ROOT . 'idb2/dbconnect' . DIRECTORY_SEPARATOR);

// load application config (error reporting etc.)
require APP . '/config/config.php';

require DB . 'ifx_model.php';
$Ifxmodel = new Ifx_Model();
 
require DB . 'my_model.php';
$Mymodel = new My_Model();

require APP. 'login.php';
