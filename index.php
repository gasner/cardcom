<?php
/**
 * Created by PhpStorm.
 * User: elad
 * Date: 16 יולי 2017
 * Time: 13:30
 */


use Cardcom\Iframe;
use Cardcom\Setting;

require 'vendor/autoload.php';

Setting::setTerminal(51973);
Setting::setUser("jCXqq41GFFDJ2qaClIzI");


$iframe = new Iframe();
$iframe->setPrice(333);
$iframe->setGoodUrl("https://secure.Cardcom.co.il/DealWasSuccessful.aspx");
var_dump($iframe->getIframe());