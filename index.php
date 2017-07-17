<?php
/**
 * Created by PhpStorm.
 * User: elad
 * Date: 16 יולי 2017
 * Time: 13:30
 */


use Cardcom\Iframe;
use Cardcom\Invoice;
use Cardcom\InvoiceProduct;
use Cardcom\Setting;

require 'vendor/autoload.php';


Setting::setTerminal(1000);
Setting::setUser("barak9611");


$invoice = new Invoice("elad gasner", "elad@closeapp.co.il");
$product = new InvoiceProduct("מעיל", 90, 2);
$product2 = new InvoiceProduct("מעיל רוח", 90, 2);
$invoice->setProducts([$product, $product2]);

$iframe = new Iframe();
$iframe->setPrice(360);
$iframe->setInvoice($invoice);

//$iframe->setGoodUrl("http://secure.Cardcom.co.il/DealWasSuccessful.aspx");
$iframe->setGoodUrl("http://http://cardcom.try/response.php");

var_dump($iframe->getSetting());
$result = $iframe->getIframe();
var_dump($result);

if ($result->isSuccess()) {
	echo '<html><body>
    <iframe runat="server"  ID="TestIfame" width="700px" height="700px" src="' . $result->getUrl() . '"></iframe>
    </body></html>';
}
