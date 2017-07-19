<?php
/**
 * Created by PhpStorm.
 * User: elad
 * Date: 17 יולי 2017
 * Time: 12:19
 */
require 'vendor/autoload.php';

use Cardcom\DealResponse;
use Cardcom\Setting;

var_dump($_REQUEST);
file_put_contents("response/" . uniqid() . rand(0, 100), json_encode($_REQUEST));


Setting::setTerminal("<trminal-number>");
Setting::setUser("<user-name>");

$response = DealResponse::getDealResponse($_REQUEST['lowprofilecode']);

file_put_contents("response/" . uniqid() . rand(0, 100), json_encode($response->getResponse()));