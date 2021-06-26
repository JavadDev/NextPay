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
