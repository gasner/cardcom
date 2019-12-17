<?php
/**
 * Created by PhpStorm.
 * User: elad
 * Date: 18 יולי 2017
 * Time: 16:55
 */

namespace Cardcom;

use Exception;
use function json_decode;
use function ucfirst;

/**
 *
 * http://kb.cardcom.co.il/article/AA-00241/0
 *
 * @property string responseCode
 * @property string description
 * @property string terminalnumber
 * @property string lowprofilecode
 * @property string operation
 * @property string prossesEndOK
 * @property string dealRespone
 * @property string dealResponse
 * @property string internalDealNumber
 * @property string cardValidityYear
 * @property string cardValidityMonth
 * @property string cardOwnerID
 * @property string numOfPayments
 * @property string callIndicatorResponse
 * @property string extShvaParams_CardNumber5
 * @property string extShvaParams_Status1
 * @property string extShvaParams_Sulac25
 * @property string extShvaParams_JParameter29
 * @property string extShvaParams_Tokef30
 * @property string extShvaParams_Sum36
 * @property string extShvaParams_SumStars52
 * @property string extShvaParams_FirstPaymentSum78
 * @property string extShvaParams_ConstPayment86
 * @property string extShvaParams_NumberOfPayments94
 * @property string extShvaParams_AbroadCard119
 * @property string extShvaParams_CardTypeCode60
 * @property string extShvaParams_Mutag24
 * @property string extShvaParams_CardOwnerName
 * @property string extShvaParams_CardHolderIdentityNumber
 * @property string extShvaParams_CreditType63
 * @property string extShvaParams_DealType61
 * @property string extShvaParams_ChargType66
 * @property string extShvaParams_TerminalNumber
 * @property string extShvaParams_BinId
 * @property string cardOwnerEmail
 * @property string cardOwnerName
 * @property string cardOwnerPhone
 * @property string returnValue
 * @property string coinId
 * @property string operationResponse
 * @property string operationResponseText
 * @property string invoiceNumber
 * @property string invoiceType
 *
 * Class DealResponse
 * @package Cardcom
 */
class DealResponse
{


	private $response;
	private $strResponse;

	private function __construct($response)
	{
		$this->strResponse = $response;
		parse_str($response, $this->response);
	}

	/**
	 * @param $dealCode
	 * @return DealResponse
	 * @throws Exception
	 */
	static public function getDealResponse($dealCode)
	{
		$urlencoded = http_build_query([
			'username' => Setting::getUser(),
			'terminalnumber' => Setting::getTerminal(),
			"lowprofilecode" => $dealCode
		]);

		$CR = curl_init();
		curl_setopt($CR, CURLOPT_URL, 'https://secure.cardcom.co.il/Interface/BillGoldGetLowProfileIndicator.aspx');
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
			throw new Exception($error);
		}
		curl_close($CR);
//		parse_str($r, $result); # parse result.
		return new DealResponse($r);
	}

	/**
	 * @return mixed
	 */
	public function getResponse()
	{
		return $this->response;
	}

	/**
	 * @return mixed
	 */
	public function getStrResponse()
	{
		return $this->strResponse;
	}


	/**
	 * @return \stdClass
	 * @throws Exception
	 */
	public function sendInvoiceToCustomer()
	{
		if (!$this->cardOwnerEmail) {
			throw new Exception("cardOwnerEmail is NULL");
		}
		return $this->sendInvoiceToMail($this->cardOwnerEmail);
	}

	/**
	 * @param $email
	 * @return \stdClass
	 * @throws Exception
	 */
	public function sendInvoiceToMail($email)
	{

		if (!$this->invoiceNumber) {
			throw new Exception("invoiceNumber is NULL");
		}

		$curl = curl_init();
		$urlencoded = http_build_query([
			"codepage" => 65001,
			'username' => Setting::getUser(),
			"UserPassword" => Setting::getPassword(),
			"InvoiceNumber" => $this->invoiceNumber,
			"InvoiceType" => $this->invoiceType,
			"EmailAddress" => $email
		]);
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://secure.cardcom.solutions/Interface/SendInvoiceCopy.aspx" . $urlencoded,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"Cache-Control: no-cache",
			),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			throw new Exception($err);
		} else {
			return json_decode($response);
		}
	}

	/**
	 * @param $name
	 * @return mixed
	 * @throws Exception
	 */
	function __get($name)
	{
		if (in_array($name, [
			'responseCode',
			'description',
			'terminalnumber',
			'lowprofilecode',
			'operation',
			'prossesEndOK',
			"dealResponse",
			"internalDealNumber",
			"cardValidityYear",
			"cardValidityMonth",
			"cardOwnerID",
			"numOfPayments",
			"invoiceResponseCode",
			"invoiceNumber",
			"invoiceType",
			"extShvaParams_CardNumber5",
			"extShvaParams_Status1",
			"extShvaParams_Sulac25",
			"extShvaParams_JParameter29",
			"extShvaParams_Tokef30",
			"extShvaParams_Sum36",
			"extShvaParams_SumStars52",
			"extShvaParams_ApprovalNumber71",
			"extShvaParams_FirstPaymentSum78",
			"extShvaParams_ConstPayment86",
			"extShvaParams_NumberOfPayments94",
			"extShvaParams_AbroadCard119",
			"extShvaParams_CardTypeCode60",
			"extShvaParams_Mutag24",
			"extShvaParams_CardOwnerName",
			"extShvaParams_CardToken",
			"extShvaParams_CardHolderIdentityNumber",
			"extShvaParams_CreditType63",
			"extShvaParams_DealType61",
			"extShvaParams_ChargType66",
			"extShvaParams_TerminalNumber",
			"extShvaParams_BinId",
			"extShvaParams_InternalDealNumber",
			"extShvaParams_CouponNumber",
			"cardOwnerEmail",
			"cardOwnerName",
			"cardOwnerPhone",
			"ReturnValue",
			"coinId",
			"operationResponse",
			"operationResponseText"
		])) {
			if (isset($this->response[ucfirst($name)])) {
				return $this->response[ucfirst($name)];
			}
			return null;
		} else {
			throw new Exception("not valid attribute");
		}

	}

}
