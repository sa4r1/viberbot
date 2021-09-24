Запуск скрипта
<?php 

$auth_token = "4e067951a0e7e76b-51824f95c3576b71-27b793d898a081e5";
$send_name = "Bot";
$is_log = true;

function put_log_in($data)
{
	global $is_log;
	if($is_log) {file_put_contents("tmp_in.txt", $data."\n", FILE_APPEND);}
}

function put_log_out($data)
{
	global $is_log;
	if($is_log) {file_put_contents("tmp_out.txt", $data."\n", FILE_APPEND);}
}

function sendReq($data)
{
	$request_data = json_encode($data);
	put_log_out($request_data);
	
	//here goes the curl to send data to user
	$ch = curl_init("https://chatapi.viber.com/pa/send_message");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $request_data);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	$response = curl_exec($ch);
	$err = curl_error($ch);
	curl_close($ch);
	if($err) {return $err;}
	else {return $response;}
}

function getMainMenu($user_id){
	$data['auth_token'] = $auth_token;
	$data['receiver']=$user_id;
  $data['BgColor']="#ffffff";
  $data['type']='text';
  $data['text']="Please select one of the options below:";
  $keyboard_array['Type']='keyboard';
  $keyboard_array['DefaultHeight']=false;
  $keyboard_array['BgColor']="#FFFFFF";
  $keyboard['keyboard']=$keyboard_array;
 
  $news['Columns']=2;
  $news['Rows']=2;
  $news['TextVAlign']="bottom";
  $news['TextHAlign']="center";
  $news['TextOpacity']="100";
  $news['Text']='NEWS';
  $news['TextSize']="regular";
  $news['ActionType']="reply";
  $news['ActionBody']="LCNews";
  $keyboard['keyboard']['Buttons'][]=$news;
 
 
  $articles['Columns']=2;
  $articles['Rows']=2;
  $articles['TextVAlign']="bottom";
  $articles['TextHAlign']="center";
  $articles['TextOpacity']="100";
  $articles['Text']='ARTICLES';
  $articles['TextSize']="regular";
  $articles['ActionType']="reply";
  $articles['ActionBody']="LCArticles";
  $keyboard['keyboard']['Buttons'][]=$articles;
 
 
  $interviews['Columns']=2;
  $interviews['Rows']=2;
  $interviews['TextVAlign']="bottom";
  $interviews['TextHAlign']="center";
  $interviews['TextOpacity']="100";
  $interviews['Text']='INTERVIEWS';
  $interviews['TextSize']="regular";
  $interviews['ActionType']="reply";
  $interviews['ActionBody']="LCInterviews";
  $keyboard['keyboard']['Buttons'][]=$interviews;
 
  $gallery['Columns']=2;
  $gallery['Rows']=2;
  $gallery['TextVAlign']="bottom";
  $gallery['TextHAlign']="center";
  $gallery['TextOpacity']="100";
  $gallery['Text']='GALLERY';
  $gallery['TextSize']="regular";
  $gallery['ActionType']="reply";
  $gallery['ActionBody']="LCGallery";
  $keyboard['keyboard']['Buttons'][]=$gallery; 
 
  $poll['Columns']=2;
  $poll['Rows']=2;
  $poll['TextVAlign']="bottom";
  $poll['TextHAlign']="center";
  $poll['TextOpacity']="100";
  $poll['Text']='POLL';
  $poll['TextSize']="regular";
  $poll['ActionType']="reply";
  $poll['ActionBody']="LCPoll";
  $keyboard['keyboard']['Buttons'][]=$poll;
 
  $potm['Columns']=2;
  $potm['Rows']=2;
  $potm['TextVAlign']="bottom";
  $potm['TextHAlign']="center";
  $potm['TextOpacity']="100";
  $potm['Text']='PLAYER OF THE MONTH';
  $potm['TextSize']="regular";
  $potm['ActionType']="reply";
  $potm['ActionBody']="LCPOTM";
  $keyboard['keyboard']['Buttons'][]=$potm;
 
  $quote['Columns']=2;
  $quote['Rows']=2;
  $quote['TextVAlign']="bottom";
  $quote['TextHAlign']="center";
  $quote['TextOpacity']="100";
  $quote['Text']='QUOTE OF THE DAY';
  $quote['TextSize']="regular";
  $quote['ActionType']="reply";
  $quote['ActionBody']="LCQuote";
  $keyboard['keyboard']['Buttons'][]=$quote;
 
  $website['Columns']=4;
  $website['Rows']=2;
  $website['TextVAlign']="bottom";
  $website['TextHAlign']="center";
  $website['TextOpacity']="100";
  $website['Text']='VISITE OUR WEBSITE';
  $website['TextSize']="regular";
  $website['ActionType']="open-url";
  $website['ActionBody']="http://www.letzcricket.com";
  $keyboard['keyboard']['Buttons'][]=$website;
 
  $data['keyboard']=$keyboard['keyboard'];

  return sendReq($data);
}

function sendMsg($sender_id, $text, $type, $tracking_data = Null, $arr_asoc = Null)
{
	global $auth_token, $send_name;
  
	$data['auth_token'] = $auth_token;
	$data['receiver'] = $sender_id;
	if($text != Null) {$data['text'] = $text;}
	$data['type'] = $type;
	//$data['min_api_version'] = $input['sender']['api_version'];
	$data['sender']['name'] = $send_name;
	//$data['sender']['avatar'] = $input['sender']['avatar'];
	if($tracking_data != Null) {$data['tracking_data'] = $tracking_data;}
	if($arr_asoc != Null)
	{
		foreach($arr_asoc as $key => $val) {$data[$key] = $val;}
	}
	
	return sendReq($data);
}

function sendMsgText($sender_id, $text, $tracking_data = Null)
{
	return sendMsg($sender_id, $text, "text", $tracking_data);
}

$request = file_get_contents("php://input");
$input = json_decode($request, true);
put_log_in($request);

$type = $input['message']['type']; //type of message received (text/picture)
$text = $input['message']['text']; //actual message the user has sent
$sender_id = $input['sender']['id']; //unique viber id of user who sent the message
$sender_name = $input['sender']['name']; //name of the user who sent the message



if($input['event'] == 'webhook') 
{
  $webhook_response['status'] = 0;
  $webhook_response['status_message'] = "ok";
  $webhook_response['event_types'] = 'delivered';
  echo json_encode($webhook_response);
  die;
}
else if($input['event'] == "subscribed") 
{
  sendMsgText($sender_id, "Спасибо, что подписались на нас!");
}
else if($input['event'] == "conversation_started")
{
  sendMsgText($sender_id, "
  	Бот лежит на сервисе heroku: cartridgestat.herokuapp.com \n
  	Mail: helpit@sawmill25.ru \n
  	Код лежит тут: github.com/sa4r1/viberbot \n
  	MySQL: ostroucz.beget.tech ostroucz_base Kukuruza*25
  	");
}
elseif($input['event'] == "message")
{
	getMainMenu($sender_id);
  sendMsg($sender_id, $text, $type);
}

?>