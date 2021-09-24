Установка хука
<?php
	$auth_token = '4e01c7364b67e33a-b9f3db0adbf60360-590941c35d4d41a7';
	$webhook = 'https://cartridgestat.herokuapp.com';
	
	$jsonData = 
	'{
		"auth_token": "'.$auth_token.'",
		"url": "'.$webhook.'",
		"event_types": ["subscribed", "unsubscribed", "delivered", "message", "seen"]
	}';
	
	$ch = curl_init('https://chatapi.viber.com/pa/set_webhook');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	$response = curl_exec($ch);
	$err = curl_error($ch);
	curl_close($ch);
	if($err) {echo($err);}
	else {echo($response);}
?>