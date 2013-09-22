<?php

require 'vendor/autoload.php';

// include redbean and use MariaDB / mysql
require_once($SLIM_ROOT/vendor/RedBean/'rb.php');
R::setup('mysql:host=localhost;dbname=slimsearch','root','');
// R::setup('mysql:host=localhost; dbname=mydatabase','user','password');
