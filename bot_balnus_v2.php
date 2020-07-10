<?php
$jeda_defined = 0;
set_time_limit(0);
ini_set('memory_limit', '256M');

//masukan nomor token Anda di sini
define('TOKEN','922754146:AAHbKHGGDeV0NQdStA-x2R1rKXY4AtmO1tY');
 
//Fungsi untuk Penyederhanaan kirim perintah dari URI API Telegram
function BotKirim($perintah){
  return 'https://api.telegram.org/bot'.TOKEN.'/'.$perintah;
}
 

function KirimPerintahStream($perintah,$data){
  //INPUT1
  /*$aContext = array(
    "ssl"=>array(
      "verify_peer"=>false,
      "verify_peer_name"=>false,
    ),
      'http' => array(
          'proxy'           => 'tcp://10.59.82.1:80',
          'request_fulluri' => true,
          'action' => 'typing',
      ),
  );
  $cxContext = stream_context_create($aContext);*/
  $result = file_get_contents(BotKirim($perintah)."?parse_mode=markdown");
  return $result;
}
 
function KirimPerintahCurl($perintah,$data){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,BotKirim($perintah));
  //curl_setopt($ch, CURLOPT_PROXY, "10.59.82.1:80");
  curl_setopt($ch, CURLOPT_POST, count($data));
  curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Your application name');
 
  $kembali = curl_exec ($ch);
  curl_close ($ch);
 
  return $kembali;
}
 
 
/*  Perintah untuk mendapatkan Update dari Api Telegram.
*  Fungsi ini menjadi penting karena kita menggunakan metode "Long-Polling".
*  Jika Anda menggunakan webhooks, fungsi ini tidaklah diperlukan lagi.
*/
 
function DapatkanUpdate($offset){
  //kirim ke Bot
  $url = BotKirim("getUpdates")."?offset=".$offset;
  //dapatkan hasilnya berupa JSON

  //INPUT1
  /*$aContext = array(
    "ssl"=>array(
      "verify_peer"=>false,
      "verify_peer_name"=>false,
    ),
      'http' => array(
          'proxy'           => 'tcp://10.59.82.1:80',
          'request_fulluri' => true,
          'action' => "typing"
      ),
  );
  $cxContext = stream_context_create($aContext);*/

  $kirim = file_get_contents($url);
  //kemudian decode JSON tersebut
  $hasil = json_decode($kirim, true);
  if($hasil["ok"] == 1){
          /* Jika hasil["ok"] bernilai satu maka berikan isi JSONnya.
           * Untuk dipergunakan mengirim perintah balik ke Telegram
           */
          return $hasil["result"];
  }else{  /* Jika tidak maka kosongkan hasilnya.
           * Hasil harus berupa Array karena kita menggunakan JSON.
           */
          return array();
       }
}
 
function KirimPerintah($perintah,$data){
    // Detek otomatis metode curl atau stream (by Radya)
     if(is_callable('curl_init')) {
       $hasil = KirimPerintahCurl($perintah,$data);
        //cek kembali, terkadang di XAMPP Curl sudah aktif
        //namun pesan tetap tidak terikirm, maka kita tetap gunakan Stream
        if (empty($hasil)){
            $hasil = KirimPerintahStream($perintah,$data);
         }   
      }
      else {
         $hasil = KirimPerintahStream($perintah,$data);
      }
    return $hasil;         
 }
 
 
#MCD--------------------------------------------------------------------------
//include("mcd/mcd_function.php");
#-----------------------------------------------------------------------------
 
function JalankanBot(){
global $jeda_defined;
//mula-mula tepatkan nilai offset pada nol
$update_id  = 0; 

//cek file apakah terdapat file "last_update_id"
if (file_exists("last_update_id")) {
    //jika ada, maka baca offset tersebut dari file "last_update_id"
    $update_id = (int)file_get_contents("last_update_id");
}
//baca JSON dari bot, cek dan dapatkan pembaharuan JSON nya
$updates = DapatkanUpdate($update_id);
//Fungsi Dewa 
foreach ($updates as $message){
  $update_id = $message["update_id"];;
  $message_data = $message["message"];
    
  //jika terdapat text dari Pengirim
  if (isset($message_data["text"])) {
    $chatid = $message_data["chat"]["id"];
    $message_id = $message_data["message_id"];
    $username = $message_data["from"]["username"];
    $first_name = $message_data["from"]["first_name"];
    $last_name = $message_data["from"]["last_name"];
    $text = $message_data["text"];
    $pecah = explode(' ',$text);
    $perintah = $pecah[0];

    //loging-----------------------------------------------------
    // $ymd = date('Y-m-d H:i:s');
    //$conn = mysqli_connect("localhost","root","","telegram");

    //tuncate table
   	/*
    $qry2 = "INSERT INTO telegram.log (tgl, username, firstname, lastname, text)
    VALUES ('$ymd','$username','$first_name','$last_name','$text')";
    $result = mysql_query($qry2,$conn);    */			
    //loging-----------------------------------------------------    
    //to branch query
    //Perintah Perintah Bergambar
    //echo ">>>>>>>".$perintahimg;
      if($perintah == "/write") { 
        echo "
        <script type=\"text/javascript\">
        window.open('bba_report.php?TEXT=$pecah[1]&&chat_id=$chatid')
        </script>";
      }      
    }//akhir dari sebuah fungsi dewa
    file_put_contents("last_update_id", $update_id + 1);
  }
}
//revisi (by Radya)
//JalankanBot(); 
//revisi (by Radya) "airbus","41Rbu5#2016"
while (true) {
  JalankanBot();
 
  $jeda = 3; // jeda 2 detik
  // Detek otomatis, cli atau browser (by Radya)
  if(php_sapi_name()==="cli") {
    sleep($jeda); //beri jedah 2 detik
  } else {
    echo '<meta http-equiv="refresh" content="'.$jeda.'">';
    echo 'BOT Telegram sedang jalan. TUTUP BROWSER DENDA 100JUTA!!!';
    break;
  }
}
