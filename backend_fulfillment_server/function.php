<?php
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

*/

function processMessage($update) {
	//sayHello
    if($update["queryResult"]["action"] == "ss"){
        sendMessage(array(
            "source" => $update["queryResult"]["source"],
           "fulfillmentText" => "Hello from webhook, hurray akhirnya bisa  !!!"
           
        ));
    }
}

function sendMessage($parameters) {


	$jsonencodeparameters=json_encode($parameters);
	 echo json_encode($parameters);
	 
	 /*
	$txt="";
	$myfile = fopen("sendMessage.txt", "w") or die("Unable to open file!");
	$txt .= "sendMessage : ".$parameters. "";
	fwrite($myfile, $txt);
	fclose($myfile);
*/
}

function update_restocart_invoiceid($session,$invoiceid,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword) {
	$sql= " update restocart set isconfirm='1',invoiceid='$invoiceid' where session='$session' and isconfirm='0' ";
	
	$txt="";
	$myfile = fopen("update_restocart_invoiceid.txt", "w") or die("Unable to open file!");
	$txt .= "content sql: ".$sql. "\n";
		fwrite($myfile, $txt);
	fclose($myfile);
	
	
	$query = mysqli_query($link,$sql)or die ('gagal insert data'.mysqli_error($link));
	return $sql;	
}

function cekjumlahitemdicart($session,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword) {
	$sql = "select sum(harga) as total from restocart where isconfirm='0' and session='$session' ";
$options = array(
	PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
			);
			$conn = new PDO("mysql:host=$mySQLserver;dbname=$mySQLdefaultdb", $mySQLuser, $mySQLpassword);
			// set the PDO error mode to exception
			$total=0;
			$infodatacart="ini data pesanan ada saat ini \n\n";
			foreach($conn->query($sql) as $row) {
					$total=$row['total'];
		}

		return $total;	

}

function check_apakah_produksudahada_di_cart($session,$namaproduct,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword) {
	$sql = "select qty from restocart where namaproduct = '$namaproduct' and isconfirm='0' and session='$session' ";
$options = array(
	PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
			);
			$conn = new PDO("mysql:host=$mySQLserver;dbname=$mySQLdefaultdb", $mySQLuser, $mySQLpassword);
			// set the PDO error mode to exception
			$qty=0;
			$infodatacart="ini data pesanan ada saat ini \n\n";
			foreach($conn->query($sql) as $row) {
					$qty=$row['qty'];
		}

		return $qty;	
}

function insert_data_to_cart($session,$namaproduct,$qty,$harga,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword) {

$sql= " insert into restocart (`session`,`namaproduct`,`qty`,`harga`,`cartdate`) values ('$session','$namaproduct','$qty','$harga','$saatini') ";
$query = mysqli_query($link,$sql)or die ('gagal insert data'.mysqli_error($link));

return $sql;		
}



function cek_profiledata_apakahsudah_diisisemua($session,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword) {
	$datanamacustomer =ambil_value_datauser($NAMATABLEUSER,"nama",$session,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);	
	$datahapecustomer =ambil_value_datauser($NAMATABLEUSER,"hape",$session,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);	
$dataemailcustomer =ambil_value_datauser($NAMATABLEUSER,"email",$session,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);	
$dataalamatcustomer =ambil_value_datauser($NAMATABLEUSER,"alamat",$session,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);	

	if ($datanamacustomer=="") {
			$warning .=" \n Data customer masih kosong";
	}
	if ($datahapecustomer=="") {
			$warning .=" \n Data hape customer masih kosong";
	}

	if ($dataemailcustomer=="") {
			$warning .=" \n Data email customer masih kosong";
	}

	if ($dataemailcustomer!="") {
			$emailcustomervalid=checkemailvalid($dataemailcustomer);
			if ($emailcustomervalid==0) {
				$warning .=" \n Data email customer invalid ";
			}
			
	}

	if ($dataalamatcustomer=="") {
			$warning .=" \n Data alamat customer masih kosong";
	}
    return $warning;
}

function generate_invoice($session,	$totalbilling ,$invoicedate, $customername,$customeremail ,$customerhape,$customeralamat,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword) {

	$sql = "insert into  restoinvoice 
	(
	session ,
	totalbilling ,
	invoicedate, 
	customername ,
	customeremail , 
	customerhape , 
 	customeralamat )
	values 
	
	(
	'$session' ,
	'$totalbilling' ,
	'$invoicedate', 
	'$customername' ,
	'$customeremail' , 
	'$customerhape' , 
 	'$customeralamat' 
	)
	";
	
	
	
		$txt="";
	$myfile = fopen("generate_invoice.txt", "w") or die("Unable to open file!");
	$txt .= "sql: ".$sql. "\n";
		
	fwrite($myfile, $txt);
	fclose($myfile);
	
	
	$last_id=0;
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
	
	return $last_id;

	
	
}




function update_jumlah_item($namaproduk,$jumlah,$totalharga,$sessionunique,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword)
{
	$sql= " update restocart set qty='$jumlah', harga='$totalharga' where session='$sessionunique' and namaproduct='$namaproduk' and  isconfirm='0'";
	
	$txt="";
	$myfile = fopen("update_jumlah_item.txt", "w") or die("Unable to open file!");
	$txt .= "sql: ".$sql. "\n";
	fwrite($myfile, $txt);
	fclose($myfile);

	
	$query = mysqli_query($link,$sql)or die ('gagal hapus data'.mysqli_error($link));
	
	
}

function batalkan_item($namaproduk,$session,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword){
	$sql= " delete from restocart where namaproduct='$namaproduk' and session='$session' and isconfirm='0' ";
	$query = mysqli_query($link,$sql)or die ('gagal hapus data'.mysqli_error($link));
	return $sql;		
}

function hapus_cart($session,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword) {
	$sql= " delete from restocart where session='$session' and isconfirm='0' ";
	$query = mysqli_query($link,$sql)or die ('gagal hapus data'.mysqli_error($link));

return $sql;		

}

function ambil_data_cart($isconfirm,$session,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword) {

	$sql= " select * from restocart where session='$session' and isconfirm='$isconfirm'";
	$options = array(
	PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
			);
			$conn = new PDO("mysql:host=$mySQLserver;dbname=$mySQLdefaultdb", $mySQLuser, $mySQLpassword);
			// set the PDO error mode to exception
			$total=0;
			$infodatacart="ini data pesanan ada saat ini \n\n";
			foreach($conn->query($sql) as $row) {
					$id=$row['id'];
					$session_data=$row['session'];
					$namaproduct=$row['namaproduct'];
					$qty=$row['qty'];
					$harga=$row['harga'];
					$total=$total + $harga;
					$formatharga=number_format($harga,2);
					$infodatacart .= $qty. " ".$namaproduct. " Rp ".$formatharga. "\n";
			}
			$totalformat=number_format($total,2);	
			if ($total>=1) {
	
			$infodatacart.= "\n\n Total semua = Rp ".$totalformat;		
				if ($isconfirm=="0"){	
					$infodatacart.="\n Untuk petunjuk tentang pembatalan, mau ganti jumlah item , mau isi data profile, ketik HELP ";
					$infodatacart.="\n Ketik konfirmasi apabila pesanan sudah lengkap dan data profile sudah diisi semua ";
				}
			}
		else {
			$infodatacart.= "\n\n anda belum memesan makanan ataupun minuman ";			
				}
		
return $infodatacart;		

}

function ambil_data_cart_perinvoice($invoiceid,$isconfirm,$session,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword) {

	$sql= " select * from restocart where session='$session' and isconfirm='$isconfirm' and invoiceid='$invoiceid'";
	$options = array(
	PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
			);
			$conn = new PDO("mysql:host=$mySQLserver;dbname=$mySQLdefaultdb", $mySQLuser, $mySQLpassword);
			// set the PDO error mode to exception
			$total=0;
			$infodatacart="ini data pesanan ada saat ini \n\n";
			$infodatacart="InvoiceID: $invoiceid \n\n";
			foreach($conn->query($sql) as $row) {
					$id=$row['id'];
					$session_data=$row['session'];
					$namaproduct=$row['namaproduct'];
					$qty=$row['qty'];
					$harga=$row['harga'];
					$total=$total + $harga;
					$formatharga=number_format($harga);
					$infodatacart .= $qty. " ".$namaproduct. " Rp ".$formatharga. "\n";
			}
			$totalformat=number_format($total);	
			if ($total>=1) {
	
			$infodatacart.= "\n\n Total semua = Rp ".$totalformat;		
				if ($isconfirm=="0"){	
					$infodatacart.="\n Untuk petunjuk tentang pembatalan, mau ganti jumlah item , mau isi data profile, ketik HELP ";
					$infodatacart.="\n Ketik konfirmasi apabila pesanan sudah lengkap dan data profile sudah diisi semua ";
				}
			}
		else {
			$infodatacart.= "\n\n anda belum memesan makanan ataupun minuman ";			
				}
		
return $infodatacart;		

}


function ambil_value_product($NAMAPRODUK, $NAMATABLEPRODUK ,$NAMAFIELDPRODUK_GET, $NAMAFIELDPRODUK_REF ,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword) 
{
$sql = " select $NAMAFIELDPRODUK_GET as value from $NAMATABLEPRODUK where title='$NAMAPRODUK' ";




$options = array(
	PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
			);
			$conn = new PDO("mysql:host=$mySQLserver;dbname=$mySQLdefaultdb", $mySQLuser, $mySQLpassword);
			// set the PDO error mode to exception
			$total=0;
			$value="";
			$value="";
			foreach($conn->query($sql) as $row) {
					$value=$row['value'];
			}

	return $value;
}

function hapus_data_user($NAMATABLEUSER,$sessionunique,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword) {
$sql = "delete from $NAMATABLEUSER where session='$sessionunique' ";
			$conn = new PDO("mysql:host=$mySQLserver;dbname=$mySQLdefaultdb", $mySQLuser, $mySQLpassword);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conn->exec($sql);
return $sql;


}

function ambil_value_datauser($NAMATABLEUSER,$NAMAFIELDUSER,$sessionunique,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword) {
$sql = "select $NAMAFIELDUSER as value from $NAMATABLEUSER where session='$sessionunique' ";
	$options = array(
	PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
			);
			$conn = new PDO("mysql:host=$mySQLserver;dbname=$mySQLdefaultdb", $mySQLuser, $mySQLpassword);
			// set the PDO error mode to exception
			$total=0;
			$value="";
			foreach($conn->query($sql) as $row) {
					$value=$row['value'];
			}

	return $value;
}


function update_data_user($NAMATABLEUSER,$NAMAFIELDUSER,$sessionunique,$nama,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword)
{
	$sql = " select count(session) as total from $NAMATABLEUSER where session='$sessionunique' ";
	$options = array(
	PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
			);
			$conn = new PDO("mysql:host=$mySQLserver;dbname=$mySQLdefaultdb", $mySQLuser, $mySQLpassword);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$total=0;
			foreach($conn->query($sql) as $row) {
					$total=$row['total'];
			}
	
	$txt="";
	$myfile = fopen("sql.txt", "w") or die("Unable to open file!");
	$txt .= "session: ".$session. "\n";
	$txt .= "varresultaction: ".$varresultaction. "\n";
	$txt .= "namauser: ".$namauser. "\n";
	$txt .= "sql: ".$sql. "\n";
	$txt .= "total: ".$total. "\n";
	//$txt .= "sql: ".$sql. "\n";
	fwrite($myfile, $txt);
	fclose($myfile);
	
	
	
	if ($total==0) {
		$sql = " insert into $NAMATABLEUSER
			(`session`,`$NAMAFIELDUSER`,`update_at`)
			values
			('$sessionunique','$nama','$saatini')
		";
		$query = mysqli_query($link,$sql)or die ('gagal insert data'.mysqli_error($link));
		
	}
	else {
		$sql = " update  $NAMATABLEUSER set $NAMAFIELDUSER='$nama', update_at='$saatini' 
		where session='$sessionunique' ";
		
		$query = mysqli_query($link,$sql)or die ('gagal update data'.mysqli_error($link));
	
	}
	
	$txt="";
	$myfile = fopen("sql2.txt", "w") or die("Unable to open file!");
	$txt .= "session: ".$session. "\n";
	$txt .= "varresultaction: ".$varresultaction. "\n";
	$txt .= "namauser: ".$namauser. "\n";
	$txt .= "sql: ".$sql. "\n";
	$txt .= "query: ".$query. "\n";
	//$txt .= "sql: ".$sql. "\n";
	fwrite($myfile, $txt);
	fclose($myfile);
	
	
	
	return $sql;
}


function ambildataprofileuser($NAMATABLEUSER,$sessionunique,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword) {
//===ambil data dari database =================

$isdatanama=0;
$isdataemail=0;
$isdatahape=0;
$isdataalamat=0;

$ambildataanama = ambil_value_datauser($NAMATABLEUSER,"nama",$sessionunique,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
$ambildataemail = ambil_value_datauser($NAMATABLEUSER,"email",$sessionunique,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
$ambildatahape = ambil_value_datauser($NAMATABLEUSER,"hape",$sessionunique,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
$ambildataalamat = ambil_value_datauser($NAMATABLEUSER,"alamat",$sessionunique,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);

if ($ambildataanama!="") {
  $isdatanama=1;
}
if ($ambildataemail!="") {
  $isdataemail=1;
}
if ($ambildatahape!="") {
  $isdatahape=1;
}

if ($ambildataalamat!="") {
  $isdataalamat=1;
}


//=============== tampilkan semua data  ====================
$infodata ="\n Nama : ". $ambildataanama;
$infodata .="\n Email : ".$ambildataemail;
$infodata .="\n Hp : ".$ambildatahape;
$infodata .="\n Alamat : ".$ambildataalamat;

if ($isdataalamat==0) {
$infodata .="\n\n Alamat anda masih kosong, tolong isi alamat anda , dimulai dengan kata Alamat [ketik_alamat_disini] ";  
}

if ($isdatahape==0) {
  $infodata .="\n\n Nomor hape anda masih kosong, tolong isi nomor hape anda , dimulai dengan kata Hp [ketik_nomor_hape_disini] ";  
}

if ($isdataemail==0) {
  $infodata .="\n\n Email anda masih kosong, tolong isi email anda , dimulai dengan kata Email [ketik_email_disini] ";  
}

if ($isdataemail==1) {
	$emailcustomervalid=checkemailvalid($ambildataemail);
	if ($emailcustomervalid==0) {
		$infodata .=" \n\n Data email masih  salah, tidak sesuai format. Tolong isi email anda , dimulai dengan kata Email [ketik_email_disini]  ";
	}

}


if ($isdatanama==0) {
  $infodata .="\n\n Nama anda masih kosong, tolong isi nama anda , dimulai dengan kata Nama [ketik_nama_anda_disini] ";  
}

return $infodata;
}



// KIRIM MAIL
function kirimmail($mail,$emailuser,$emailsubject,$content) {
	include("phpmailer/settingmailbotchatid.php");
	$mail->addAddress($emailuser, $emailuser); 
	$mail->Subject = $emailsubject;
	$mail->Body    = $content;
	$mail->AltBody = $content;
		
	if(!$mail->send()) {
	//	echo 'Message could not be sent.';
		$respond = "<div class='alert alert-danger'>Maaf, kirim email ke ".$emailuser." gagal";
		$respond .= "<p>pesan : ".$mail->ErrorInfo." gagal</p>";
		$respond .= "</div>";
		//echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
			
		$respond = "".$emailuser." telah dikirim ke email ".$emailuser.", cek email tersebut.";
	}

	return $respond;
}



function checkemailvalid($emailaddress) {


$pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';



	if (preg_match($pattern, $emailaddress) === 1) {
	    // emailaddress is valid
		return 1;
	}
	else {
		return 0;

	}
	

}
?>