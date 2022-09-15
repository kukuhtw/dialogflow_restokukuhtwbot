<?php
use Google\Cloud\Dialogflow\V2\SessionsClient;
use Google\Cloud\Dialogflow\V2\TextInput;
use Google\Cloud\Dialogflow\V2\QueryInput;

require __DIR__.'/vendor/autoload.php';
include("db.php");

date_default_timezone_set("Asia/Jakarta");
$tanggalhariini = date("Y/m/d");
$jamhariini = date("H:i:sa");
$saatini = $tanggalhariini. " ".$jamhariini;

$usermessages = (isset($_GET["usermessages"]) ? $_GET["usermessages"] : null); 
$usermessages = mysqli_real_escape_string($link, $usermessages);



$cookies_visitor = isset($_COOKIE['cookies_visitor']) ? $_COOKIE['cookies_visitor'] : '';
$cookies_visitor ="DEMO";

$cookiestext = isset($_COOKIE['cookiestext']) ? $_COOKIE['cookiestext'] : '';
$cookiestext = mysqli_real_escape_string($link, $cookiestext);

if ($cookiestext!=$usermessages) {
	exit;
}

$ipaddress = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$browser = ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) ? strtolower( $_SERVER['HTTP_USER_AGENT'] ) : '';


$usermessages_lower=strtolower($usermessages);
if ($usermessages_lower=="clear") {
	$sql = " truncate table `log_conversation` ";
	$conn = new PDO("mysql:host=$mySQLserver;dbname=$mySQLdefaultdb", $mySQLuser, $mySQLpassword);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conn->exec($sql);

}

if ($usermessages!="") {
	
	$sql="insert into `log_conversation` (`actor`,`ipaddress`,`browser`,`cookies_user`,`messages`,`msgdate`) values ('user','$ipaddress','$browser','$cookies_visitor','$usermessages','$saatini') ";

	$last_id=0;
	
	$namafile="simpan.txt";
	$contentdebug="sql=".$sql;
	debug_text($namafile,$contentdebug);
	
	try {
			$conn = new PDO("mysql:host=$mySQLserver;dbname=$mySQLdefaultdb", $mySQLuser, $mySQLpassword);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conn->exec($sql);
			$last_id = $conn->lastInsertId();
		}
	catch(PDOException $e)
	{
		$last_id=0;
	}
	$projectId="restokukuhtw";
	$sessionId=$cookies_visitor;
	$text=$usermessages; 
	$namafile="simpan2.txt";
	$contentdebug="sql=".$sql;
	$contentdebug.="jawabanbot=".$jawabanbot;
	debug_text($namafile,$contentdebug);
	$jawabanbot=detect_intent_texts($projectId, $text, $sessionId);
	$sessionunique="user";
		
		$sql="insert into `log_conversation` (`actor`,`ipaddress`,`browser`,`cookies_user`,`messages`,`msgdate`) values ('bot','$ipaddress','$browser','$cookies_visitor','$jawabanbot','$saatini') ";
		
	$namafile="simpan3.txt";
	$contentdebug="sql=".$sql;
	$contentdebug="jawabanbot=".$jawabanbot;
	debug_text($namafile,$contentdebug);
		
	try {
			$conn = new PDO("mysql:host=$mySQLserver;dbname=$mySQLdefaultdb", $mySQLuser, $mySQLpassword);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conn->exec($sql);
			$last_id = $conn->lastInsertId();
		}
	catch(PDOException $e)
	{
		$last_id=0;
	}
	
		
}

function detect_intent_texts($projectId, $text, $sessionId, $languageCode = 'en-US')
{
    // new session
    $test = array('credentials' => 'restokukuhtw-5b307e2bfefd.json');
    $sessionsClient = new SessionsClient($test);
    $session = $sessionsClient->sessionName($projectId, $sessionId ?: uniqid());
    //printf('Session path: %s' . PHP_EOL, $session);

    // create text input
    $textInput = new TextInput();
    $textInput->setText($text);
    $textInput->setLanguageCode($languageCode);

    // create query input
    $queryInput = new QueryInput();
    $queryInput->setText($textInput);

    // get response and relevant info
    $response = $sessionsClient->detectIntent($session, $queryInput);
    $queryResult = $response->getQueryResult();
    $queryText = $queryResult->getQueryText();
    $intent = $queryResult->getIntent();
    $displayName = $intent->getDisplayName();
    $confidence = $queryResult->getIntentDetectionConfidence();
    $fulfilmentText = $queryResult->getFulfillmentText();

    // output relevant info
    //print(str_repeat("=", 20) . PHP_EOL);
    //printf('Query text: %s' . PHP_EOL, $queryText);
   // printf('Detected intent: %s (confidence: %f)' . PHP_EOL, $displayName,
     //  $confidence);
	 // echo "<br>displayName=".$displayName;
   // print(PHP_EOL);
  //  printf('Fulfilment text: %s' . PHP_EOL, $fulfilmentText);
	//echo "<br>Guest:".$queryText;

	//echo "<br>Bot:".$fulfilmentText;
	$sessionsClient->close();
	return $fulfilmentText;
    
}
function debug_text($namafile,$contentdebug) {
	$myfile = fopen($namafile, "w") or die("Unable to open file!");
	fwrite($myfile, $contentdebug);
	fclose($myfile);
}

?>