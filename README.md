# Cardcom Iframe
cardcom iframe php package


## Installation

> **Note:** Cardcom Iframe is currently in beta.

    composer require elad/cardcom


### Basic Usage



```php
use Cardcom\Iframe;
use Cardcom\Invoice;
use Cardcom\InvoiceProduct;
use Cardcom\Setting;

require 'vendor/autoload.php';


Setting::setTerminal(<yourTrminal>);
Setting::setUser(<yourUser>);
```

#### Create iframe
```php
$iframe = new Iframe();
$iframe->setPrice(15);
$iframe->docTypeToCreate = 400;
//$iframe->setGoodUrl("http://secure.Cardcom.co.il/DealWasSuccessful.aspx");
$iframe->setGoodUrl("http://cardcom.try/response2.php");
$iframe->setErrorUrl("http://cardcom.try/error.php");
```

#### Create invoice
```php
$invoice = new Invoice("elad gasner", "elad@closeapp.co.il");
$product = new InvoiceProduct("מעיל", 5, 1);
$product2 = new InvoiceProduct("מעיל רוח", 5, 2);
$invoice->setProducts([$product, $product2]);
$iframe->setInvoice($invoice);
```

#### Get iframe link
```php
$iframe = new Iframe();
$iframe->setPrice(15);
$iframe->setInvoice($invoice);
//you can any property from the cardcom api
$iframe->docTypeToCreate = 400;

$iframe->setGoodUrl("http://cardcom.try/response2.php");
$iframe->setErrorUrl("http://cardcom.try/error.php");

// get iframe link
$result = $iframe->getIframe();



if ($result->isSuccess()) {
  $link =  $result->getUrl();
}else{
	$result->getError();
}
```

Good Luck!
