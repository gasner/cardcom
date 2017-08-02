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
$product = new InvoiceProduct("מעיל", 5, 1);
$product2 = new InvoiceProduct("מעיל רוח", 5, 2);
$invoice->setProducts([$product, $product2]);

$iframe = new Iframe();
$iframe->setPrice(15);
$iframe->setInvoice($invoice);
$iframe->docTypeToCreate = 400;
//$iframe->setGoodUrl("http://secure.Cardcom.co.il/DealWasSuccessful.aspx");
$iframe->setGoodUrl("http://cardcom.try/response2.php");
$iframe->setErrorUrl("http://cardcom.try/error.php");

var_dump($iframe->getSetting());
$result = $iframe->getIframe();
var_dump($result);

if ($result->isSuccess()) {
	echo '<html><body>
    <iframe runat="server"  ID="TestIfame" width="700px" height="700px" src="' . $result->getUrl() . '"></iframe>
    </body></html>';
}
