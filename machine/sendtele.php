<?php
//send to telegram
//$chat_id = $_POST["chatid"];
//$chat_id = "439239139";
//$chat_id = $_POST["chatid"];
//$img =  $_POST["img"];
$chat_id = "350633638";
//$img = "BBA report";

$bot_url    = "https://api.telegram.org/bot922754146:AAHbKHGGDeV0NQdStA-x2R1rKXY4AtmO1tY/";
$url        = $bot_url . "sendPhoto?chat_id=" . $chat_id ;

$post_fields = [
	'chat_id'   => $chat_id,
    'photo'     => new CURLFile(realpath($_POST["img"].".jpg"))
];

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type:multipart/form-data"
));
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields); 
$output = curl_exec($ch);
var_dump($output);

return;
?>