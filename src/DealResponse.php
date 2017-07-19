<?php
/**
 * Created by PhpStorm.
 * User: elad
 * Date: 18 יולי 2017
 * Time: 16:55
 */

namespace Cardcom;

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
			throw new \Exception($error);
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

	function __get($name)
	{
		if (in_array($name, ['productName', 'maxNumOfPayments', 'aPILevel', 'minNumOfPayments', 'hideCreditCardUserId', 'successRedirectUrl', 'errorRedirectUrl', 'indicatorUrl', 'cancelUrl', 'cancelType', 'sumInStars', 'hideCVV', 'creditType', 'returnValue', 'defaultNumOfPayments', 'hideCardOwnerName', 'sapakMutav', 'coinID', 'language'])) {
			return $this->response[ucfirst($name)];
		} else {
			throw new \Exception("not valid attribute");
		}

	}

}