<?php


# Global Definetions :
$TerminalNumber = 51973; # Company terminal
$UserName = 'jCXqq41GFFDJ2qaClIzI';   # API User
$CreateInvoice = false;  # to Create Invoice an invoice ?
$IsIframe = true;
$Operation = 1;  # = 1 - Bill Only , 2- Bill And Create Token , 3 - Token Only , 4 - Suspended Deal ( Order).


#Create Post Information
// Account vars
$vars = array();
$vars['terminalnumber'] = $TerminalNumber;
$vars['username'] = $UserName;
$vars['codepage'] = '65001'; // unicode
$vars["ChargeInfo.APILevel"] = "9"; // req

// billing info article : http://kb.cardcom.co.il/article/AA-00243/0
$vars['ChargeInfo.SumToBill'] = "20"; // Sum To Bill
$vars['ChargeInfo.CoinID'] = "1"; // billing coin , 1- NIS , 2- USD other , article :  http://kb.cardcom.co.il/article/AA-00247/0
$vars['ChargeInfo.Language'] = "he"; // page languge he- hebrew , en - english , ru , ar
$vars['ChargeInfo.ProductName'] = "Order Number 1234"; // Product Name


// redirect url
$vars['ChargeInfo.SuccessRedirectUrl'] = "https://secure.Cardcom.co.il/DealWasSuccessful.aspx"; // Success Page
$vars['ChargeInfo.ErrorRedirectUrl'] = "https://secure.Cardcom.co.il/DealWasUnSuccessful.aspx?customVar=1234"; // Error Page
$vars['ChargeInfo.IndicatorUrl'] = "http://www.yoursite.com/NotifyURL"; // Indicator Url \ Notify URL

// Other optinal vars :
$vars["ChargeInfo.ReturnValue"] = "1234"; // value that will be return and save in CardCom system
$vars["ChargeInfo.MaxNumOfPayments"] = "5"; // max num of payments to show  to the user

if ($CreateInvoice) {
	// article for invoice vars:  http://kb.cardcom.co.il/article/AA-00244/0

	// customer info :
	$vars["InvoiceHead.CustName"] = "Test customer"; // customer name
	$vars["InvoiceHead.SendByEmail"] = "true"; // will the invoice be send by email to the customer
	$vars["InvoiceHead.Language"] = "he"; // he or en only
	$vars["InvoiceHead.Email"] = "1234"; // value that will be return and save in CardCom system

	// products info

	// Line 1
	$vars["InvoiceLines1.Description"] = "item 1";
	$vars["InvoiceLines1.Price"] = "5";
	$vars["InvoiceLines1.Quantity"] = "2";

	// line 2
	$vars["InvoiceLines2.Description"] = "itme 2";
	$vars["InvoiceLines2.Price"] = "10";
	$vars["InvoiceLines2.Quantity"] = "1";

	// ********   Sum of all Lines Price*Quantity  must be equals to SumToBill ***** //
}

// Send Data To Bill Gold Server
$r = PostVars($vars, 'https://secure.Cardcom.co.il/interface/PerformSimpleCharge.aspx');

parse_str($r, $result); # parse result.

# Is Deal OK
if ($result['ResponseCode'] == '0') {
	var_dump($result);
	die;
	# Iframe or  Redicet User :
	$newurl = "https://secure.Cardcom.co.il/External/lowProfileClearing/" . $TerminalNumber . ".aspx?LowProfileCode=" . $result['LowProfileCode'];
	if ($IsIframe) {
		echo '<html><body>
    <iframe runat="server"  ID="TestIfame" width="700px" height="700px" src="' . $newurl . '"></iframe>
    </body></html>';
	} else  // redirect
	{
		header("Location:" . $newurl);
	}

} # Show Error to developer only
else {
	var_dump($result);
	echo $result['ResponseCode'] . ' ' . $result['Description'];
}


function PostVars($vars, $PostVarsURL)
{
	$urlencoded = http_build_query($vars);
//	print($urlencoded);
//	die;
	#init curl connection
	if (function_exists("curl_init")) {
		$CR = curl_init();
		curl_setopt($CR, CURLOPT_URL, $PostVarsURL);
		curl_setopt($CR, CURLOPT_POST, 1);
		curl_setopt($CR, CURLOPT_FAILONERROR, true);
		curl_setopt($CR, CURLOPT_POSTFIELDS, $urlencoded);
		curl_setopt($CR, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($CR, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($CR, CURLOPT_FAILONERROR, true);
		#actual curl execution perfom
		$r = curl_exec($CR);
		$error = curl_error($CR);
		# some error , send email to developer
		if (!empty($error)) {

			echo $error;

			die();
		}
		curl_close($CR);
		return $r;
	} else {
		echo "No curl_init";
		die();
	}
}

?>