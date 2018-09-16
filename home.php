<?php

require 'vendor/autoload.php';

echo "Hi! This is a bot for Telegram!";



$botToken=getenv('BOT_TOKEN');
$website="https://api.telegram.org/bot".$botToken;

$update=file_get_contents("php://input");
$update=json_decode($update, TRUE);
$chatId=$update["message"]["chat"]["id"];
$name=$update["message"]["chat"]["first_name"];
$text=$update["message"]["text"];


$paragraph=array();
$paragraph=json_decode(file_get_contents('paragraphfile.txt'), true);
$body="";

/*$body="Lorem ipsum dolor sit amet consectetur adipiscing elit.  ";*/
/*$body=wordwrap($body,70);*/

$order=array();
$order=json_decode(file_get_contents('orderfile.txt'), true);

$time="13:15";
$time=file_get_contents('timefile.txt');

$subject="order";
$subject=file_get_contents('subjectfile.txt');

$company="acme";
$company=file_get_contents('companyfile.txt');

$conn = pg_connect(getenv("DATABASE_URL"));
if (!$conn) {
  echo "An error occurred.\n";
  exit;
}
   
//TIMEORDER chaid name
//COMPANYNAMES chaid name
//CHATORDER chaid name
//EMAILSUBJECT chatid subject


/*---creating table---*/
/*$sql =<<<EOF
      CREATE TABLE CHATORDER
      (CHATID     TEXT      NOT NULL,
       NAME       TEXT      NOT NULL);
EOF;
$ret = pg_query($conn, $sql);
if(!$ret) {
	echo pg_last_error($conn);
} else {
	echo "Table created successfully\n";
}
pg_close($conn);*/

/*---reading table---*/
/*$query = "SELECT ID, NAME FROM CHATORDER";
$result = pg_query($conn, $query);
if (!$result) {
  echo "An error occurred.\n";
  exit;
}
echo $result;
while ($row = pg_fetch_row($result)) {
  echo "CHATID:".$row[0];
  echo "NAME:".$row[1];
  echo "<br />\n";
}*/
/*-------------------*/
$query = "SELECT * FROM CHATORDER";
$result = pg_query($conn, $query);
if (!$result) {
  echo "An error occurred.\n";
  exit;
}
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    echo " ";
    foreach ($line as $r_value) {
        echo "$r_value";
    }
    echo " ";
}
/*-------------------*/
/*-------------------*/
$query = "SELECT * FROM COMPANYNAMES";
$result = pg_query($conn, $query);
if (!$result) {
  echo "An error occurred.\n";
  exit;
}
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    echo " ";
    foreach ($line as $r_value) {
        echo "$r_value";
    }
    echo " ";
}
/*-------------------*/
/*-------------------*/
$query = "SELECT * FROM TIMEORDER";
$result = pg_query($conn, $query);
if (!$result) {
  echo "An error occurred.\n";
  exit;
}
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    echo " ";
    foreach ($line as $r_value) {
        echo "$r_value";
    }
    echo " ";
}
/*-------------------*/
/*-------------------*/
$query = "SELECT * FROM EMAILSUBJECT";
$result = pg_query($conn, $query);
if (!$result) {
  echo "An error occurred.\n";
  exit;
}
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    echo " ";
    foreach ($line as $r_value) {
        echo "$r_value";
    }
    echo " ";
}
/*-------------------*/

if(strpos($text,'/resetdev')!==false){
	file_put_contents('subjectfile.txt',  "order");
	file_put_contents('companyfile.txt',  "acme");
	file_put_contents('timefile.txt',  "13:15");
	$order=array("item0", "item1");
	file_put_contents('orderfile.txt',  json_encode($order));
	$paragraph=array("Dear recipient, i'd like to order as ", " for ", " people at "," with the following order: ");
	file_put_contents('paragraphfile.txt',  json_encode($paragraph));
	file_get_contents($website."/sendmessage?chat_id=".$chatId."&text=reset to dev mode");
}
if(strpos($text,'/resetdev@MailOrderBot')!==false){
	file_put_contents('subjectfile.txt',  "order");
	file_put_contents('companyfile.txt',  "acme");
	file_put_contents('timefile.txt',  "13:15");
	$order=array("item0", "item1");
	file_put_contents('orderfile.txt',  json_encode($order));
	$paragraph=array("Dear recipient, i'd like to order as ", " for ", " people at "," with the following order: ");
	file_put_contents('paragraphfile.txt',  json_encode($paragraph));
	file_get_contents($website."/sendmessage?chat_id=".$chatId."&text=reset to dev mode");
}

if(strpos($text,'/resetitems')!==false){
	/*deleting from the table*/
	$query = "DELETE FROM CHATORDER WHERE CHATID = '".$chatId."' ;";
	pg_query($conn, $query);
	/*deleting from the file*/
	$order=array();
	file_put_contents('orderfile.txt',  json_encode($order));
	file_get_contents($website."/sendmessage?chat_id=".$chatId."&text=items erased");
}
if(strpos($text,'/resetitems@MailOrderBot')!==false){
	/*deleting from the table*/
	$query = "DELETE FROM CHATORDER WHERE CHATID = '".$chatId."' ;";
	pg_query($conn, $query);
	/*deleting from the file*/
	$order=array();
	file_put_contents('orderfile.txt',  json_encode($order));
	file_get_contents($website."/sendmessage?chat_id=".$chatId."&text=items erased");
}

if(strpos($text,'/resetparagraph')!==false){
	$paragraph=array();
	file_put_contents('paragraphfile.txt',  json_encode($paragraph));
	file_get_contents($website."/sendmessage?chat_id=".$chatId."&text=paragraph erased");
}
if(strpos($text,'/resetparagraph@MailOrderBot')!==false){
	$paragraph=array();
	file_put_contents('paragraphfile.txt',  json_encode($paragraph));
	file_get_contents($website."/sendmessage?chat_id=".$chatId."&text=paragraph erased");
}

if(strpos($text,'/time')!==false){
	$text = str_replace('/time', '', $text);
	/*deleting from the table*/
	$query = "DELETE FROM TIMEORDER WHERE CHATID = '".$chatId."' ;";
	pg_query($conn, $query);
	/*inserting into the table*/
	$query = "INSERT INTO TIMEORDER VALUES ('".$chatId."','".$text."');";
	pg_query($conn, $query);
	/*update files*/
	file_put_contents('timefile.txt',  $text);
	file_get_contents($website."/sendmessage?chat_id=".$chatId."&text=time set");
}
if(strpos($text,'/time@MailOrderBot')!==false){
	$text = str_replace('/time@MailOrderBot', '', $text);
	/*deleting from the table*/
	$query = "DELETE FROM TIMEORDER WHERE CHATID = '".$chatId."' ;";
	pg_query($conn, $query);
	/*inserting into the table*/
	$query = "INSERT INTO TIMEORDER VALUES ('".$chatId."','".$text."');";
	pg_query($conn, $query);
	/*update files*/
	file_put_contents('timefile.txt',  $text);
	file_get_contents($website."/sendmessage?chat_id=".$chatId."&text=time set");
}

if(strpos($text,'/company')!==false){
	$text = str_replace('/company', '', $text);
	/*deleting from the table*/
	$query = "DELETE FROM COMPANYNAMES WHERE CHATID = '".$chatId."' ;";
	pg_query($conn, $query);
	/*inserting into the table*/
	$query = "INSERT INTO COMPANYNAMES VALUES ('".$chatId."','".$text."');";
	pg_query($conn, $query);
	/*update files*/
	file_put_contents('companyfile.txt',  $text );
	file_get_contents($website."/sendmessage?chat_id=".$chatId."&text=company set");
}
if(strpos($text,'/company@MailOrderBot')!==false){
	$text = str_replace('/company@MailOrderBot', '', $text);
	/*deleting from the table*/
	$query = "DELETE FROM COMPANYNAMES WHERE CHATID = '".$chatId."' ;";
	pg_query($conn, $query);
	/*inserting into the table*/
	$query = "INSERT INTO COMPANYNAMES VALUES ('".$chatId."','".$text."');";
	pg_query($conn, $query);
	/*update files*/
	file_put_contents('companyfile.txt',  $text );
	file_get_contents($website."/sendmessage?chat_id=".$chatId."&text=company set");
}

if(strpos($text,'/subject')!==false){
	$text = str_replace('/subject', '', $text);
	/*deleting from the table*/
	$query = "DELETE FROM EMAILSUBJECT WHERE CHATID = '".$chatId."' ;";
	pg_query($conn, $query);
	/*inserting into the table*/
	$query = "INSERT INTO EMAILSUBJECT VALUES ('".$chatId."','".$text."');";
	pg_query($conn, $query);
	/*update files*/
	file_put_contents('subjectfile.txt',  $text );
	file_get_contents($website."/sendmessage?chat_id=".$chatId."&text=subject set");
}
if(strpos($text,'/subject@MailOrderBot')!==false){
	$text = str_replace('/subject@MailOrderBot', '', $text);
	/*deleting from the table*/
	$query = "DELETE FROM EMAILSUBJECT WHERE CHATID = '".$chatId."' ;";
	pg_query($conn, $query);
	/*inserting into the table*/
	$query = "INSERT INTO EMAILSUBJECT VALUES ('".$chatId."','".$text."');";
	pg_query($conn, $query);
	/*update files*/
	file_put_contents('subjectfile.txt',  $text );
	file_get_contents($website."/sendmessage?chat_id=".$chatId."&text=subject set");
}
  
if(strpos($text,'sendEmail')!==false){
	/*mail("mailbotsevice@protonmail.com","Lorem ipsum",$body);*/
	$from = new SendGrid\Email(null, "test@example.com");
	$recipientTestMail = getenv('TEST_RECIPIENT');
        $to = new SendGrid\Email(null, $recipientTestMail);
        $content = new SendGrid\Content("text/plain", "Test Email!");
        $mail = new SendGrid\Mail($from, $subject, $to, $content);
        $apiKey = getenv('SENDGRID_API_KEY');
        $sg = new \SendGrid($apiKey);
        $response = $sg->client->mail()->send()->post($mail);
	file_get_contents($website."/sendmessage?chat_id=".$chatId."&text=messageSent");
}

if(strpos($text,'/send')!==false){
	$orderPreview = implode("; ",$order);
	$body = $paragraph[0].$company.$paragraph[1].sizeof($order).$paragraph[2].$time.$paragraph[3].$orderPreview;
	/*$body = $body." ".$company.", ".sizeof($order)." customers, ".$time.", order: ".$orderPreview;*/
	
	$from = new SendGrid\Email(null, "acme@example.com");
	$content = new SendGrid\Content("text/plain", $body);
	$apiKey = getenv('SENDGRID_API_KEY');
        $sg = new \SendGrid($apiKey);
	
	/*to recipient*/
	$recipientTestMail = getenv('TEST_RECIPIENT');
	$to = new SendGrid\Email(null, $recipientTestMail);
	$mail = new SendGrid\Mail($from, $subject, $to, $content);
        $response = $sg->client->mail()->send()->post($mail);	
	
	/*to developer*/
	$recipientDeveloperMail = getenv('DEVELOPER_RECIPIENT');
	$toDeveloper = new SendGrid\Email(null, $recipientDeveloperMail);
	$mailDeveloper = new SendGrid\Mail($from, $subject, $toDeveloper, $content);
        $responseDeveloper = $sg->client->mail()->send()->post($mailDeveloper);
	
	file_get_contents($website."/sendmessage?chat_id=".$chatId."&text=messageSent");
}
if(strpos($text,'/send@MailOrderBot')!==false){
	$orderPreview = implode("; ",$order);
	$body = $paragraph[0].$company.$paragraph[1].sizeof($order).$paragraph[2].$time.$paragraph[3].$orderPreview;
	
	$from = new SendGrid\Email(null, "acme@example.com");
	$content = new SendGrid\Content("text/plain", $body);
	$apiKey = getenv('SENDGRID_API_KEY');
        $sg = new \SendGrid($apiKey);
	
	/*to recipient*/
	$recipientTestMail = getenv('TEST_RECIPIENT');
	$to = new SendGrid\Email(null, $recipientTestMail);
	$mail = new SendGrid\Mail($from, $subject, $to, $content);
        $response = $sg->client->mail()->send()->post($mail);	
	
	/*to developer*/
	$recipientDeveloperMail = getenv('DEVELOPER_RECIPIENT');
	$toDeveloper = new SendGrid\Email(null, $recipientDeveloperMail);
	$mailDeveloper = new SendGrid\Mail($from, $subject, $toDeveloper, $content);
        $responseDeveloper = $sg->client->mail()->send()->post($mailDeveloper);
	
	file_get_contents($website."/sendmessage?chat_id=".$chatId."&text=messageSent");
}

if(strpos($text,'/preview')!==false){
	$orderPreview = implode("; ",$order);
	$body = $paragraph[0].$company.$paragraph[1].sizeof($order).$paragraph[2].$time.$paragraph[3].$orderPreview;
	file_get_contents($website."/sendmessage?chat_id=".$chatId."&text=".$body);
}
if(strpos($text,'/preview@MailOrderBot')!==false){
	$orderPreview = implode("; ",$order);
	$body = $paragraph[0].$company.$paragraph[1].sizeof($order).$paragraph[2].$time.$paragraph[3].$orderPreview;
	file_get_contents($website."/sendmessage?chat_id=".$chatId."&text=".$body);
}

if(strpos($text,'/add')!==false){
	/*removing command string*/
	$text = str_replace('/add', '', $text);
	/*inserting into the table*/
	$query = "INSERT INTO CHATORDER VALUES ('".$chatId."','".$text."');";
	pg_query($conn, $query);
	/*inserting into the file*/
	array_push($order, $text);
	file_put_contents('orderfile.txt',  json_encode($order));
	/*reply with a preview*/
	$orderPreview = implode("; ",$order);
	file_get_contents($website."/sendmessage?chat_id=".$chatId."&text=".$orderPreview);
}
if(strpos($text,'/add@MailOrderBot')!==false){
	/*removing command string*/
	$text = str_replace('/add@MailOrderBot', '', $text);
	/*inserting into the table*/
	$query = "INSERT INTO CHATORDER VALUES ('".$chatId."','".$text."');";
	pg_query($conn, $query);
	/*inserting into the file*/
	array_push($order, $text);
	file_put_contents('orderfile.txt',  json_encode($order));
	/*reply with a preview*/
	$orderPreview = implode("; ",$order);
	file_get_contents($website."/sendmessage?chat_id=".$chatId."&text=".$orderPreview);
}

if(strpos($text,'/newparagraph')!==false){
	$text = str_replace('/newparagraph', '', $text);
	array_push($paragraph, $text);
	file_put_contents('paragraphfile.txt',  json_encode($paragraph));
	$paragraphPreview = implode("; ",$paragraph);
	file_get_contents($website."/sendmessage?chat_id=".$chatId."&text=".$paragraphPreview);
}
if(strpos($text,'/newparagraph@MailOrderBot')!==false){
	$text = str_replace('/newparagraph@MailOrderBot', '', $text);
	array_push($paragraph, $text);
	file_put_contents('paragraphfile.txt',  json_encode($paragraph));
	$paragraphPreview = implode("; ",$paragraph);
	file_get_contents($website."/sendmessage?chat_id=".$chatId."&text=".$paragraphPreview);
}
