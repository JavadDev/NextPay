<?php

class NextPay {
	const URL = "https://nextpay.org/nx/gateway/";
	public $api_key;
	public $trans_id;
	public $statusCode;
	
	private $data;
	private $custom_data;
	
	public function __construct(string $api_key)
	{
		$this->api_key = $api_key;
	}
	
	public function setData(array $data)
	{
		$this->data = $data;
		return $this;
	}
	
	public function setApiKey(string $api_key)
	{
		$this->api_key = $api_key;
		return $this;
	}
	
	public function setCustomData(array $custom_data)
	{
		$this->custom_data = $custom_data;
		return $this;
	}
	
	public function getData()
	{
		return $this->data;
	}
	
	public function getApiKey()
	{
		return $this->api_key;
	}
	
	public function getCustomData()
	{
		return $this->custom_data;
	}
	
	private function send(string $method)
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => self::URL . ltrim($method, '/'),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => http_build_query(array_merge(
				['api_key' => $this->api_key],
				$this->data,
				['custom_json_fields' => ($this->data['custom_json_fields'] ?? json_encode($this->getCustomData(), 256))]
			))
		)); 
		
		$response = curl_exec($curl);
		$result = json_decode($response, true);
		curl_close($curl);
		
		return $result;
	}
	
	public function createToken(&$response = null)
	{
		$create = $this->send('token');
		$this->statusCode = ($create['code'] === -1) ? 200: $create['code'];
		$response = $create;
		
		if ($this->statusCode == 200) {
			$this->trans_id = $create['trans_id'];
		}
		
		return $this;
	}
	
	public function paymentUrl()
	{
		$url = self::URL.'payment/'.$this->trans_id;
		return $url;
	}
	
	public function redirect()
	{
		$url = $this->paymentUrl();
		header("location: $url");
	}
	
	public function verifyToken(&$response = null)
	{
		$verify = $this->send('verify');
		$this->statusCode = ($verify['code'] === 0) ? 200: $verify['code'];
		$response = $verify;
		
		return $this;
	}
	
	public function cancelPayment(&$response = null)
	{
		$this->data['refund_request'] = 'yes_money_back';
		$send = $this->send('verify');
		unset($this->data['refund_request']);
		
		$this->statusCode = ($send['code'] === -90) ? 200: $send['code'];
		$response = $send;
		
		return $this;
	}
	
	public function checkOut(&$response = null)
	{
		$send = $this->send('checkout');
		
		$this->statusCode = ($send['code'] === 200) ? 200: $send['code'];
		$response = $send;
		
		return $this;
	}
	
	public function checkOutWithoutFee(&$response = null)
	{
		$send = $this->send('checkout_withoutfee');
		
		$this->statusCode = ($send['code'] === 200) ? 200: $send['code'];
		$response = $send;
		
		return $this;
	}
	
	public function getStatus(&$status)
	{
		$status = $this->statusCode;
		return $this;
	}
	
	public function __toString()
	{
		return (string) $this->statusCode;
	}
}
