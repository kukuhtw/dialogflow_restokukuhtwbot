<?php
//namespace Google\Cloud\Samples\Dialogflow;
/*


    $$\   $$\          $$\                 $$\             $$$$$$$$\ $$\      $$\ 
$$ | $$  |         $$ |                $$ |            \__$$  __|$$ | $\  $$ |
$$ |$$  /$$\   $$\ $$ |  $$\ $$\   $$\ $$$$$$$\           $$ |   $$ |$$$\ $$ |
$$$$$  / $$ |  $$ |$$ | $$  |$$ |  $$ |$$  __$$\          $$ |   $$ $$ $$\$$ |
$$  $$<  $$ |  $$ |$$$$$$  / $$ |  $$ |$$ |  $$ |         $$ |   $$$$  _$$$$ |
$$ |\$$\ $$ |  $$ |$$  _$$<  $$ |  $$ |$$ |  $$ |         $$ |   $$$  / \$$$ |
$$ | \$$\\$$$$$$  |$$ | \$$\ \$$$$$$  |$$ |  $$ |         $$ |   $$  /   \$$ |
\__|  \__|\______/ \__|  \__| \______/ \__|  \__|         \__|   \__/     \__|


kukuhtw@gmail.com
whatsapp : 62.8129893706
https://www.linkedin.com/in/kukuhtw/
https://www.instagram.com/kukuhtw/
https://twitter.com/kukuhtw/
https://www.facebook.com/kukuhtw
https://www.facebook.com/profile.php?id=100083608342093


Themes Whatsapp is downloaded from this site
https://codepen.io/zenorocha/pen/eZxYOK


you need google-cloud-php-dialogflow to run demo in website
get it here
https://github.com/googleapis/google-cloud-php-dialogflow

you need generate service account key at console.dialogflow
create your own project service account key and upload in this folder


Create a service account key:

In the Google Cloud console, click the email address for the service account that you created.
Click Keys.
Click Add key, and then click Create new key.
Click Create. A JSON key file is downloaded to your computer.
Click Close.


*/
include("db.php");
//cookies

$ipaddress = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$browser = ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) ? strtolower( $_SERVER['HTTP_USER_AGENT'] ) : '';



$cookies_visitor = isset($_COOKIE['cookies_visitor']) ? $_COOKIE['cookies_visitor'] : '';

$cookies_visitor ="DEMO";
//$cookies_visitor = $ipaddress;
//$cookies_visitor=str_replace(".","",$cookies_visitor);
//$cookies_visitor="A";

echo"<br>cookies_visitor=".$cookies_visitor;


if ($cookies_visitor=="") {
	$durationcookies = 3600 * 24 * 30 * 12 * 10 ;  // 10 tahun
	$cookies_visitor=rand(1111,9999)."-".rand(1111,9999)."-".rand(1111,9999);
	$cookies_visitor ="DEMO";
	//$cookies_visitor = $ipaddress;
	//$cookies_visitor=str_replace(".","",$cookies_visitor);

	setcookie("cookies_visitor", $cookies_visitor, time()+$durationcookies);
	$cookies_visitor = isset($_COOKIE['cookies_visitor']) ? $_COOKIE['cookies_visitor'] : '';
	echo"<br>Now cookies_visitor=".$cookies_visitor;

}

date_default_timezone_set("Asia/Jakarta");
$tanggalhariini = date("Y/m/d");
$jamhariini = date("H:i:sa");
$saatini = $tanggalhariini. " ".$jamhariini;
$projectId="kukuhtw-e9efd"; //<---------- CHANGE WITH YOUR PROJECT ID HERE

//query_user
$query_user = (isset($_POST["query_user"]) ? $_POST["query_user"] : null); 
$query_user = mysqli_real_escape_string($link, $query_user);

$responsebot = "halo, ada yang bisa dibantu ?";

//echo "<br>query_user=".$query_user;
$cookies_user=$cookies_visitor;

?>

<head>
  <meta charset="UTF-8">
  <title>Demo WhatsAppBot RestoKukuhTW</title>
  
  
  <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto:400,700,300'>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.1.2/css/material-design-iconic-font.min.css'>
<link rel='stylesheet' href='https://rawgit.com/marvelapp/devices.css/master/assets/devices.min.css'>

      <link rel="stylesheet" href="style.css">

  
</head>

<body>
  <p>
  Demo ini menggunakan cookies yang sama bernama DEMO,
  Bila ada pengguna lain yang sedang berinteraksi saat ini,
  maka data pesanan anda akan tercampur baur dengan user lain tersebut.
<br>
 	ketik CLEAR untuk menghapus semua data , 
 	 	ketik HELP untuk membaca petunjuk
<p>&nbsp;</p>

  <div class="page">
  <div class="marvel-device nexus5">
    <div class="top-bar"></div>
    <div class="sleep"></div>
    <div class="volume"></div>
    <div class="camera"></div>
    <div class="screen">
      <div class="screen-container">
        <div class="status-bar">
          <div class="time"></div>
          <div class="battery">
            <i class="zmdi zmdi-battery"></i>
          </div>
          <div class="network">
            <i class="zmdi zmdi-network"></i>
          </div>
          <div class="wifi">
            <i class="zmdi zmdi-wifi-alt-2"></i>
          </div>
          <div class="star">
            <i class="zmdi zmdi-star"></i>
          </div>
        </div>
        <div class="chat">
          <div class="chat-container">
            <div class="user-bar">
              <div class="back">
                <i class="zmdi zmdi-arrow-left"></i>
              </div>
              <div class="avatar">
                <img src="robot.jpg">
              </div>
              <div class="name">
                <span>ChatBot</span>
                <span class="status">online</span>
              </div>
              <div class="actions more">
                <i class="zmdi zmdi-more-vert"></i>
              </div>
              <div class="actions attachment">
                <i class="zmdi zmdi-attachment-alt"></i>
              </div>
              <div class="actions">
                <i class="zmdi zmdi-phone"></i>
              </div>
            </div>
            <div class="conversation">
              <div class="conversation-container">
                
				<?php
					
				
					
					$sql="select * from `log_conversation` where cookies_user='$cookies_visitor' ";
					
					//echo $sql;
					$conn = new PDO("mysql:host=$mySQLserver;dbname=$mySQLdefaultdb", $mySQLuser, $mySQLpassword);
					// set the PDO error mode to exception
					$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$total=0;
					foreach($conn->query($sql) as $row) {
						$total=$total+1;
						$id=$row['id'];
						$cookies_user=$row['cookies_user'];
						$actor=$row['actor'];
						$messages=$row['messages'];
						$messages=str_replace("\n","<br>",$messages);
						$messages = preg_replace("#\*([^*]+)\*#", "<b>$1</b>", $messages);
						$messages = preg_replace("#\_([^_]+)\_#", "<i>$1</i>", $messages);
						
						$messages = preg_replace("~(http?://(?:www\.)?[^\s]+)~i","<a href='$1'>$1</a>",$messages);
						
						$messages = preg_replace("~(https?://(?:www\.)?[^\s]+)~i","<a href='$1'>$1</a>",$messages);
						
						$msgdate=$row['msgdate'];
						
						if ($actor!="bot") {
						?>
						<div class="message sent">
                  <?php 
				  echo $messages ?>
				  <span class="metadata">
                      <span class="time"></span>
					  <span class="tick"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" id="msg-dblcheck-ack" x="2063" y="2076"><path d="M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.88a.32.32 0 0 1-.484.032l-.358-.325a.32.32 0 0 0-.484.032l-.378.48a.418.418 0 0 0 .036.54l1.32 1.267a.32.32 0 0 0 .484-.034l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.88a.32.32 0 0 1-.484.032L1.892 7.77a.366.366 0 0 0-.516.005l-.423.433a.364.364 0 0 0 .006.514l3.255 3.185a.32.32 0 0 0 .484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z" fill="#4fc3f7"/></svg></span>
                  </span>
                </div>
                
						<?php
						}
						
						else {
						
						  
						  
						?>
						<div class="message received">
                  <?php 
				  include("parsing.php");
				  echo $messages ;
				
				  ?>
                <span class="metadata"><span class="time"></span></span>
                </div>
						<?php
						}
					}
				?>
				
              </div>
              <form class="conversation-compose">
                <div class="emoji">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" id="smiley" x="3147" y="3209"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.153 11.603c.795 0 1.44-.88 1.44-1.962s-.645-1.96-1.44-1.96c-.795 0-1.44.88-1.44 1.96s.645 1.965 1.44 1.965zM5.95 12.965c-.027-.307-.132 5.218 6.062 5.55 6.066-.25 6.066-5.55 6.066-5.55-6.078 1.416-12.13 0-12.13 0zm11.362 1.108s-.67 1.96-5.05 1.96c-3.506 0-5.39-1.165-5.608-1.96 0 0 5.912 1.055 10.658 0zM11.804 1.01C5.61 1.01.978 6.034.978 12.23s4.826 10.76 11.02 10.76S23.02 18.424 23.02 12.23c0-6.197-5.02-11.22-11.216-11.22zM12 21.355c-5.273 0-9.38-3.886-9.38-9.16 0-5.272 3.94-9.547 9.214-9.547a9.548 9.548 0 0 1 9.548 9.548c0 5.272-4.11 9.16-9.382 9.16zm3.108-9.75c.795 0 1.44-.88 1.44-1.963s-.645-1.96-1.44-1.96c-.795 0-1.44.878-1.44 1.96s.645 1.963 1.44 1.963z" fill="#7d8489"/></svg>
                </div>
                <input class="input-msg" name="input" placeholder="Type a message" autocomplete="off" autofocus></input>
				
				
				
                <div class="photo">
                  <i class="zmdi zmdi-camera"></i>
                </div>
                <button class="send">
                    <div class="circle">
                      <i class="zmdi zmdi-mail-send"></i>
                    </div>
                  </button>
              </form>
			</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
            
  <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js'></script>
	  <script  src="script.js.php"></script>
<p>

</body>

</html>


