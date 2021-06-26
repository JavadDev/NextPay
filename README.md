# NextPay
NextPay.org Sample Library

#### Example:
```PHP
require 'NextPay.php';
$token = 'MerchantID';
$NextPay = new NextPay($token);
```

```PHP
$data = array( 	'amount' => 1000, 	'order_id' => hash('crc32', uniqid(microtime(true))), 	'callback_uri' => dirname($_SERVER['SCRIPT_URI']).'/back.php', ); $NextPay->setData($data); $create = $NextPay->createToken($response)->getStatus($Status); if ($Status == 200) { 	$trans_id = $create->trans_id; 	$create->redirect(); } else { 	$response = json_encode($response, 448); 	echo "Error!<br><pre language='json'>$response</pre>"; }
```
