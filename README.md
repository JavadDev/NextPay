# NextPay
NextPay.org Sample Library

#### Create an instance of class:
```PHP
require 'NextPay.php';
$token = 'MerchantID';
$NextPay = new NextPay($token);
```

#### Create new transaction:
```PHP
$data = array(
	'amount' => 1000,
	'order_id' => 'unique order id',
	'callback_uri' => 'https://site.me/path/callback.php',
);

$NextPay->setData($data);
$create = $NextPay->createToken($response)->getStatus($Status);

if ($Status == 200) {
	$trans_id = $create->trans_id;
	$create->redirect();
} else {
	$response = json_encode($response, 448);
	echo $response;
}
```

#### Verify a transaction:
```PHP
extract($_GET);

if (isset($amount) and isset($trans_id) and isset($order_id)) {
	$data = array(
		'amount' => $amount,
		'trans_id' => $trans_id,
	);
	
	$NextPay->setData($data);
	$verify = $NextPay->verifyToken($response)->getStatus($Status);
	
	if ($Status == 200) {
		echo 'Done';
	} else {
		echo 'Error';
	}
	
	$response = json_encode($response, 448);
	echo "<pre language='json'>$response</pre>";
}
```

#### Checkout Payment:
```PHP
$data = array(
	'wid' => 32,
	'auth' => "",
	'amount' => 1000,
	'sheba' => "100....",
	'name' => "محمد محمدی",
);

$NextPay->setData($data);
$NextPay->checkOut($response)->getStatus($Status);
// $NextPay->checkOutWithoutFee($response)->getStatus($Status);
if ($Status == 200) {
	echo 'Success';
} else {
	$response = json_encode($response, 448);
	echo $response;
}
```

#### Notes:
1. All results will save in __$response__ variable!
2. Library will change __$Status__ code to 200.
3. Read more in NextPay.org documents
