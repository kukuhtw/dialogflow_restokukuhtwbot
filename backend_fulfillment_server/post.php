<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


$NAMATABLEUSER="restosessionuser";

include("db.php");
include("function.php");

date_default_timezone_set("Asia/Jakarta");
$tanggalhariini = date("Y/m/d");
$jamhariini = date("H:i:sa");
$saatini = $tanggalhariini. " ".$jamhariini;
$update_response = file_get_contents("php://input");
$update = json_decode($update_response, true);

$varresultaction = $update["queryResult"]["action"];
$varresultaction = mysqli_real_escape_string($link, $varresultaction);

$session = $update["session"];

$ignore1="";
$sessionunique="";

//$session="projects/restokukuhtw/agent/sessions/3c83363b-266e-47db-c971-f5e93c20e2a3";
$list  = list($ignore1,$sessionunique) = explode('sessions/', $session);

$namauser  = (isset($update["queryResult"]["parameters"]["namauser"]) ? $update["queryResult"]["parameters"]["namauser"] : null); 
$namauser = mysqli_real_escape_string($link, $namauser);


$emailuser = (isset($update["queryResult"]["parameters"]["emailuser"]) ? $update["queryResult"]["parameters"]["emailuser"] : null);
$emailuser = mysqli_real_escape_string($link, $emailuser);


$hapeuser =  (isset($update["queryResult"]["parameters"]["hapeuser"]) ? $update["queryResult"]["parameters"]["hapeuser"]  : null);
$hapeuser = mysqli_real_escape_string($link, $hapeuser);


$alamatpenerima = (isset($update["queryResult"]["parameters"]["alamatpenerima"]) ? $update["queryResult"]["parameters"]["alamatpenerima"] : null);
$alamatpenerima = mysqli_real_escape_string($link, $alamatpenerima);


/*
	$txt="";
	$myfile = fopen("pembuka.txt", "w") or die("Unable to open file!");
	$txt .= "session: ".$session. "\n";
	$txt .= "varresultaction: ".$varresultaction. "\n";
	$txt .= "namauser: ".$namauser. "\n";
	$txt .= "alamatpenerima: ".$alamatpenerima. "\n";
	$txt .= "emailuser: ".$emailuser. "\n";
	$txt .= "hapeuser: ".$hapeuser. "\n";
	
	
	
	//$txt .= "sql: ".$sql. "\n";
	fwrite($myfile, $txt);
	fclose($myfile);
	*/
	
	

if ($varresultaction=="konfirmasi_order" ){
	$boleh_konfirmasi=1;
	$warning="";
	$totalbelanja = cekjumlahitemdicart($sessionunique,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
	if ($totalbelanja==0) {
		$boleh_konfirmasi=0;
		$warning .= "\n belum ada pesanan yang anda lakukan !";
	}
	
	$datanamacustomer =ambil_value_datauser($NAMATABLEUSER,"nama",$sessionunique,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);	
	if ($datanamacustomer=="") {
		$boleh_konfirmasi=0;
		$warning .= "\n belum ada data nama anda !";
	}
	
	$datahapecustomer =ambil_value_datauser($NAMATABLEUSER,"hape",$sessionunique,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);	
	if ($datahapecustomer=="") {
		$boleh_konfirmasi=0;
		$warning .= "\n belum ada data nomor hape anda !";
	}
	
	
	$dataemailcustomer =ambil_value_datauser($NAMATABLEUSER,"email",$sessionunique,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);	
	if ($dataemailcustomer=="") {
		$boleh_konfirmasi=0;
		$warning .= "\n belum ada data email anda !";
	}
	
	
	if ($dataemailcustomer!="") {
		$emailcustomervalid=checkemailvalid($dataemailcustomer);
			if ($emailcustomervalid==0) {
				$boleh_konfirmasi=0;
				$warning .= "\n format email masih salah !";
			}
			
	}

	$dataalamatcustomer =ambil_value_datauser($NAMATABLEUSER,"alamat",$sessionunique,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);	
	if ($dataalamatcustomer=="") {
		$boleh_konfirmasi=0;
		$warning .= "\n belum ada data alamat anda !";
	}
	
	if ($boleh_konfirmasi==0) {
	$respondotuser = $warning . "\n\n maaf, belum bisa dilakukan konfirmasi order, lengkapi dahulu data profile dan lakukan pemesanan ";
		sendMessage(array(
            "source" => null,
           "fulfillmentText" => $respondotuser
        ));
	exit;
	}

	//bila konfirmai oke
	if ($boleh_konfirmasi==1) {
	$respondotuser="\n\n konfirmasi dilakukan ";
	
	$invoiceid=	generate_invoice($sessionunique,$totalbelanja ,$saatini, $datanamacustomer,$dataemailcustomer ,$datahapecustomer,$dataalamatcustomer,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
	
	update_restocart_invoiceid($sessionunique,$invoiceid,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
	
	
	$respondotuser = ambil_data_cart_perinvoice($invoiceid,'1',$sessionunique,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);

 	$respondotuser.= "\n\n".ambildataprofileuser($NAMATABLEUSER,$sessionunique,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);

	//$respondotuser.= "\n\n Order sudah diproses, cek email $dataemailcustomer ";
	$respondotuser.= "\n\n Order sudah diproses, , pelayan kami akan menguhubungi anda. Terima kasih.";
	
	
	
	$contentemail =  	$respondotuser;
	
	/*
	require 'phpmailer/PHPMailerAutoload.php';
		$mail = new PHPMailer;
		$emailsubject = "[DemoChatBot_RestoKukuhtw] InvoiceID ".$invoiceid;
		$content = $contentemail;
		$content=str_replace("\n","<br>",$content);
		kirimmail($mail,$dataemailcustomer,$emailsubject,$content);
	*/
	
	sendMessage(array(
            "source" => null,
           "fulfillmentText" => $respondotuser
        ));
		
	exit;
	}

}	

if ($varresultaction=="user_ganti_jumlah_item" ){
	$Makanan  = (isset($update["queryResult"]["parameters"]["Makanan"]) ? $update["queryResult"]["parameters"]["Makanan"] : null); 
	$Minuman  = (isset($update["queryResult"]["parameters"]["Minuman"]) ? $update["queryResult"]["parameters"]["Minuman"] : null); 
	
	if($Makanan!="") {
		$jumlahPorsiMangkokMakanan  = (isset($update["queryResult"]["parameters"]["jumlahPorsiMangkokMakanan"]) ? $update["queryResult"]["parameters"]["jumlahPorsiMangkokMakanan"] : null); 

		if ($jumlahPorsiMangkokMakanan<=0) {
			$jumlahPorsiMangkokMakanan=1;
		}

		$hargamakanan =  ambil_value_product($Makanan, "produk" , "harga" , "session" ,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
		$totalhargamakanan=$hargamakanan * $jumlahPorsiMangkokMakanan;
		$namaproduk=$Makanan;
		$jumlah=$jumlahPorsiMangkokMakanan;
		$totalharga=$totalhargamakanan;
			//check ada dicart ?
		$qtymakanan=check_apakah_produksudahada_di_cart($sessionunique,$Makanan,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
		if ($qtymakanan<=0) {
			insert_data_to_cart($sessionunique,$namaproduk,$jumlah,$totalharga,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
		}	
	
	}
	if($Minuman!="") {
		$jumlahgelasminuman  = (isset($update["queryResult"]["parameters"]["jumlahgelasminuman"]) ? $update["queryResult"]["parameters"]["jumlahgelasminuman"] : null); 
		$jumlahgelasminuman=intval($jumlahgelasminuman);
		if ($jumlahgelasminuman<=0) {
			$jumlahgelasminuman=1;
		}

		$hargamainuman =  ambil_value_product($Minuman, "produk" ,"harga", "session" ,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
		$totalhargamaminuman=$hargamainuman * $jumlahgelasminuman;
		$namaproduk=$Minuman;
		$jumlah=$jumlahgelasminuman;
		$totalharga=$totalhargamaminuman;
		$qtyminuman=check_apakah_produksudahada_di_cart($sessionunique,$Minuman,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
		if ($qtyminuman<=0) {
			insert_data_to_cart($sessionunique,$namaproduk,$jumlah,$totalharga,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
		}	
	}
	
	update_jumlah_item($namaproduk,$jumlah,$totalharga,$sessionunique,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword) ;
	$respondotuser="Oke pesanan $namaproduk diganti menjadi $jumlah  !";
	$respondotuser = $respondotuser. ambil_data_cart('0',$sessionunique,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);

	$respondotuser.= "\n\n ".ambildataprofileuser($NAMATABLEUSER,$sessionunique,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
	
	sendMessage(array(
            "source" => null,
           "fulfillmentText" => $respondotuser
        ));
	

}
if ($varresultaction=="user_batal_item" ){
	$Makanan  = (isset($update["queryResult"]["parameters"]["Makanan"]) ? $update["queryResult"]["parameters"]["Makanan"] : null); 
	$Makanan = mysqli_real_escape_string($link, $Makanan);


	$Minuman  = (isset($update["queryResult"]["parameters"]["Minuman"]) ? $update["queryResult"]["parameters"]["Minuman"] : null); 
	$Minuman = mysqli_real_escape_string($link, $Minuman);
	
	if($Makanan!="") {
		$itembatal=$Makanan;
		batalkan_item($Makanan,$sessionunique,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
	}
	if($Minuman!="") {
		$itembatal=$Minuman;
		batalkan_item($Minuman,$sessionunique,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
	}
	$respondotuser="Oke pesanan $itembatal dibatalkan !\n\n";

	$respondotuser = $respondotuser. ambil_data_cart('0',$sessionunique,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
	$respondotuser.= "\n\n".ambildataprofileuser($NAMATABLEUSER,$sessionunique,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
	
	
	sendMessage(array(
            "source" => null,
           "fulfillmentText" => $respondotuser
        ));
	
}
if ($varresultaction=="user_batal" )
{
  hapus_cart($sessionunique,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
 $respondotuser="Oke pesanan anda dibatalkan !";
  sendMessage(array(
            "source" => null,
           "fulfillmentText" => $respondotuser
        ));
}

if ($varresultaction=="lihat_pesan" )
{
	$respondotuser = ambil_data_cart('0',$sessionunique,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);

 	$respondotuser.= "\n\n".ambildataprofileuser($NAMATABLEUSER,$sessionunique,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
	
 
     sendMessage(array(
            "source" => null,
           "fulfillmentText" => $respondotuser
           
        ));
 
}


$Makanan  = (isset($update["queryResult"]["parameters"]["Makanan"]) ? $update["queryResult"]["parameters"]["Makanan"] : null); 
$Makanan = mysqli_real_escape_string($link, $Makanan);


$Minuman  = (isset($update["queryResult"]["parameters"]["Minuman"]) ? $update["queryResult"]["parameters"]["Minuman"] : null); 
$Minuman = mysqli_real_escape_string($link, $Minuman);


$tanyamakanan=0;
$tanyaminuman=0;

if ($varresultaction=="user_tanya_harga" && $Makanan!="")
{
  $hargamakanan =  ambil_value_product($Makanan, "produk" , "harga" , "session" ,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
  $hargamakanan_format=number_format($hargamakanan);

  $respondtouser="Harga ".$Makanan. " @ Rp ".$hargamakanan_format;

 sendMessage(array(
            "source" => null,
           "fulfillmentText" => $respondtouser
           
        ));
  $tanyamakanan=1;
  $tanyaminuman=0;

} 

if ($varresultaction=="user_tanya_harga" && $Minuman!="")
{
  $hargaminuman =  ambil_value_product($Minuman, "produk" , "harga" , "session" ,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
  $hargaminuman_format=number_format($hargaminuman);

  $respondtouser="Harga ".$Minuman. " @ Rp ".$hargaminuman_format;

 sendMessage(array(
            "source" => null,
           "fulfillmentText" => $respondtouser
        ));
    $tanyamakanan=0;
    $tanyaminuman=1;
        
}
if ($varresultaction=="user_tanya_harga" && $tanyamakanan==0 && $tanyaminuman==0)  {
  $respondtouser="Barang Tidak tersedia";

 sendMessage(array(
            "source" => null,
           "fulfillmentText" => $respondtouser
        ));
}

if ($varresultaction=="orderstep1" )
{
$Makanan  = (isset($update["queryResult"]["parameters"]["Makanan"]) ? $update["queryResult"]["parameters"]["Makanan"] : null); 
$Makanan = mysqli_real_escape_string($link, $Makanan);


$Makanan1  = (isset($update["queryResult"]["parameters"]["Makanan"]) ? $update["queryResult"]["parameters"]["Makanan"] : null); 
$Makanan1 = mysqli_real_escape_string($link, $Makanan1);


$Minuman  = (isset($update["queryResult"]["parameters"]["Minuman"]) ? $update["queryResult"]["parameters"]["Minuman"] : null); 
$Minuman = mysqli_real_escape_string($link, $Minuman);


$jumlahPorsiMangkokMakanan  = (isset($update["queryResult"]["parameters"]["jumlahPorsiMangkokMakanan"]) ? $update["queryResult"]["parameters"]["jumlahPorsiMangkokMakanan"] : null); 
$jumlahPorsiMangkokMakanan = mysqli_real_escape_string($link, $jumlahPorsiMangkokMakanan);


$jumlahPorsiMangkokMakanan1  = (isset($update["queryResult"]["parameters"]["jumlahPorsiMangkokMakanan1"]) ? $update["queryResult"]["parameters"]["jumlahPorsiMangkokMakanan1"] : null); 

$jumlahPorsiMangkokMakanan1 = mysqli_real_escape_string($link, $jumlahPorsiMangkokMakanan1);



$jumlahgelasminuman  = (isset($update["queryResult"]["parameters"]["jumlahgelasminuman"]) ? $update["queryResult"]["parameters"]["jumlahgelasminuman"] : null); 

$jumlahgelasminuman = mysqli_real_escape_string($link, $jumlahgelasminuman);

$hargamakanan =  ambil_value_product($Makanan, "produk" , "harga" , "session" ,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
$hargamakanan1 =  ambil_value_product($Makanan1, "produk" , "harga" , "session" ,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);



$hargamainuman =  ambil_value_product($Minuman, "produk" ,"harga", "session" ,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);

$qtymakanan=check_apakah_produksudahada_di_cart($sessionunique,$Makanan,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);

$qtymakanan1=check_apakah_produksudahada_di_cart($sessionunique,$Makanan1,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);


$qtyminuman=check_apakah_produksudahada_di_cart($sessionunique,$Minuman,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);



if ($qtymakanan>=1) {
	batalkan_item($Makanan,$sessionunique,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
	$jumlahPorsiMangkokMakanan=$jumlahPorsiMangkokMakanan+$qtymakanan;
}



if ($qtyminuman>=1) {
	batalkan_item($Minuman,$sessionunique,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
	$jumlahgelasminuman=$jumlahgelasminuman+$qtyminuman;
}

if ($jumlahPorsiMangkokMakanan==0 || $jumlahPorsiMangkokMakanan=="") {
	$jumlahPorsiMangkokMakanan=1;
}

if ($jumlahgelasminuman==0 || $jumlahgelasminuman=="") {
	$jumlahgelasminuman=1;
}


$totalhargamakanan= intval($hargamakanan) * intval($jumlahPorsiMangkokMakanan);


$totalhargamaminuman=intval($hargamainuman) * intval($jumlahgelasminuman);


  

if ($Makanan !="") {
  insert_data_to_cart($sessionunique,$Makanan,$jumlahPorsiMangkokMakanan,$totalhargamakanan,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
}



if ($Minuman !="") {
  insert_data_to_cart($sessionunique,$Minuman,$jumlahgelasminuman,$totalhargamaminuman,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
}

	$Makanan1  = (isset($update["queryResult"]["parameters"]["Makanan1"]) ? $update["queryResult"]["parameters"]["Makanan1"] : null); 

$txt="";
  $myfile = fopen("item.txt", "w") or die("Unable to open file!");
  $txt .= "Makanan: ".$Makanan. "\n";
  $txt .= "Makanan1: ".$Makanan1. "\n";
  $txt .= "hargamakanan: ".$hargamakanan. "\n";
  $txt .= "hargamakanan1: ".$hargamakanan1. "\n";
  
  $txt .= "jumlahPorsiMangkokMakanan: ".$jumlahPorsiMangkokMakanan. "\n";
  $txt .= "jumlahPorsiMangkokMakanan1: ".$jumlahPorsiMangkokMakanan1. "\n";
  
  $txt .= "totalhargamakanan: ".$totalhargamakanan. "\n";
   $txt .= "totalhargamakanan1: ".$totalhargamakanan1. "\n";


  $txt .= "Minuman: ".$Minuman. "\n";
  $txt .= "hargamainuman: ".$totalhargamaminuman. "\n";
  $txt .= "jumlahgelasminuman: ".$jumlahgelasminuman. "\n";
  $txt .= "totalhargamaminuman: ".$totalhargamaminuman. "\n";


  fwrite($myfile, $txt);
  fclose($myfile);
  



if ($Makanan1!="") {
	$hargamakanan1 =  ambil_value_product($Makanan1, "produk" , "harga" , "session" ,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);

   $jumlahPorsiMangkokMakanan1  = (isset($update["queryResult"]["parameters"]["jumlahPorsiMangkokMakanan1"]) ? $update["queryResult"]["parameters"]["jumlahPorsiMangkokMakanan1"] : null); 

$jumlahPorsiMangkokMakanan1 = mysqli_real_escape_string($link, $jumlahPorsiMangkokMakanan1);


	
	$totalhargamakanan1=$hargamakanan1 * $jumlahPorsiMangkokMakanan1;
	$qtymakanan1=check_apakah_produksudahada_di_cart($sessionunique,$Makanan1,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
	if ($qtymakanan1>=1) {
		batalkan_item($Makanan1,$sessionunique,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
		$jumlahPorsiMangkokMakanan1=$jumlahPorsiMangkokMakanan1+$qtymakanan1;
	}
		
	insert_data_to_cart($sessionunique,$Makanan1,$jumlahPorsiMangkokMakanan1,$totalhargamakanan1,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
}  

$Minuman1  = (isset($update["queryResult"]["parameters"]["Minuman1"]) ? $update["queryResult"]["parameters"]["Minuman1"] : null); 
$Minuman1 = mysqli_real_escape_string($link, $Minuman1);


$jumlahgelasminuman1  = (isset($update["queryResult"]["parameters"]["jumlahgelasminuman1"]) ? $update["queryResult"]["parameters"]["jumlahgelasminuman1"] : null); 
$jumlahgelasminuman1 = mysqli_real_escape_string($link, $jumlahgelasminuman1);


if ($Minuman1!="") {
	$hargamainuman1 =  ambil_value_product($Minuman1, "produk" ,"harga", "session" ,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
	$totalhargamaminuman1=$hargamainuman1 * $jumlahgelasminuman1;
	
	$qtyminuman1=check_apakah_produksudahada_di_cart($sessionunique,$Minuman1,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
	if ($qtyminuman1>=1) {
		batalkan_item($Minuman1,$sessionunique,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
		$jumlahgelasminuman1=$jumlahgelasminuman1+$qtyminuman1;
	}

	insert_data_to_cart($sessionunique,$Minuman1,$jumlahgelasminuman1,$totalhargamaminuman1,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);

}

  
/*
$respondtouser="oke anda memesan ".$jumlahPorsiMangkokMakanan." " .$Makanan." dan ".$jumlahgelasminuman ." ". $Minuman.". Pesanan ini sudah dicatat.  Bila ingin membatalkan ketik batal. bila ingin menambah pesanan klik`'lagi`, bila ingin melihat apa saja yang telah anda pesan ketik lihat pesanan saya \n\n";
 */
 
 $respondotuser = ambil_data_cart('0',$sessionunique,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
	$respondotuser.= "\n\n".ambildataprofileuser($NAMATABLEUSER,$sessionunique,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
	
 

 sendMessage(array(
            "source" => null,
           "fulfillmentText" => $respondotuser
           
        ));
    


}



if ($varresultaction=="user_lihat_profiledata" )
{
	$infodata=  ambildataprofileuser($NAMATABLEUSER,$sessionunique,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
   
	   sendMessage(array(
            "source" => null,
           "fulfillmentText" => "Data Profile Anda \n ".$infodata
           
        ));
    
}	
	
	
if ($varresultaction=="user_provide_info_nama" && $namauser!="")
{
	$sql=update_data_user($NAMATABLEUSER,"nama",$sessionunique,$namauser,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
	$infodata=  ambildataprofileuser($NAMATABLEUSER,$sessionunique,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
   	
	$txt="";
	$myfile = fopen("sql3.txt", "w") or die("Unable to open file!");
	$txt .= "session: ".$session. "\n";
	$txt .= "varresultaction: ".$varresultaction. "\n";
	$txt .= "namauser: ".$namauser. "\n";
	$txt .= "sql: ".$sql. "\n";
	$txt .= "post.php ";
	
	//$txt .= "sql: ".$sql. "\n";
	fwrite($myfile, $txt);
	fclose($myfile);
	
	
	   sendMessage(array(
            "source" => null,
           "fulfillmentText" => "Oke nama anda $namauser ".$infodata
           
        ));
    
}	
	
if ($varresultaction=="user_provide_info_email" && $emailuser!="")
{	
	$sql=update_data_user($NAMATABLEUSER,"email",$sessionunique,$emailuser,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
	$infodata=  ambildataprofileuser($NAMATABLEUSER,$sessionunique,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
      
	   sendMessage(array(
            "source" => null,
           "fulfillmentText" => "Oke email anda $emailuser ".$infodata
           
        ));
    
}	

if ($varresultaction=="user_provide_info_hape" && $hapeuser!="")
{
	$sql=update_data_user($NAMATABLEUSER,"hape",$sessionunique,$hapeuser,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
	$infodata=  ambildataprofileuser($NAMATABLEUSER,$sessionunique,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
      
	   sendMessage(array(
            "source" => null,
           "fulfillmentText" => "Oke hape anda $hapeuser ".$infodata
           
        ));
    
}	
					   

if ($varresultaction=="user_provide_info_alamat" && $alamatpenerima!="")
{

//===ambil data dari database =================


	
	
		//=============== tampilkan semua data  ====================
 
	$sql=update_data_user($NAMATABLEUSER,"alamat",$sessionunique,$alamatpenerima,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
	
	
	$infodata=  ambildataprofileuser($NAMATABLEUSER,$sessionunique,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);

	

   	
	   sendMessage(array(
            "source" => null,
           "fulfillmentText" => "Oke alamat anda $alamatpenerima ".$infodata
        ));
    
}	

	

if ($varresultaction=="reset_data") {
	$sql=hapus_data_user($NAMATABLEUSER,$sessionunique,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);
	$infodata=  ambildataprofileuser($NAMATABLEUSER,$sessionunique,$saatini,$link,$mySQLserver,$mySQLdefaultdb,$mySQLuser,$mySQLpassword);

	$txt="";
	$myfile = fopen("sql4.txt", "w") or die("Unable to open file!");
	$txt .= "sql: ".$sql. "\n";
	$txt .= "post.php ";
	$txt .= "sql: ".$sql. "\n";
	fwrite($myfile, $txt);
	fclose($myfile);

	
	
      sendMessage(array(
            "source" => $update["queryResult"]["source"],
           "fulfillmentText" => "oke data profile sudah dihapus "
           
        ));

		
}
	

if ($varresultaction=="pesanMakanan")
{
	$param_makanan=$update["queryResult"]["parameters"]["makanan"];
	$param_minuman=$update["queryResult"]["parameters"]["minuman"];

	$param_makanan = mysqli_real_escape_string($link, $param_makanan);
	$param_minuman = mysqli_real_escape_string($link, $param_minuman);

	
      sendMessage(array(
            "source" => $update["queryResult"]["source"],
           "fulfillmentText" => "simpan ke database. makananan= ".$param_makanan." , minuman= ".$param_minuman. " "
           
        ));
  
}

	/*
	
		$txt="";
	$myfile = fopen("sql.txt", "w") or die("Unable to open file!");
	$txt .= "session: ".$session. "\n";
	$txt .= "varresultaction: ".$varresultaction. "\n";
	$txt .= "namauser: ".$namauser. "\n";
	
	//$txt .= "sql: ".$sql. "\n";
	fwrite($myfile, $txt);
	fclose($myfile);
	
	
	$txt="";
	$myfile = fopen("content.txt", "w") or die("Unable to open file!");
	$txt .= "content update: ".$update_response. "\n";
	$txt .= "content ambildataanama: ".$ambildataanama. "\n";
	$txt .= "content ambildataemail: ".$ambildataemail. "\n";
	$txt .= "content ambildatahape: ".$ambildatahape. "\n";
	$txt .= "content ambildataalamat: ".$ambildataalamat. "\n\n";

	fwrite($myfile, $txt);
	fclose($myfile);
	*/
	

//=======================================================================================================
	
/*
{
  "responseId": "722bafce-2273-4281-8740-c83dcb3e1d8c",
  "queryResult": {
    "queryText": "nama james bond",
    "action": "user_provide_info_nama",
    "parameters": {
      "namauser": "james bond"
    },
    "allRequiredParamsPresent": true,
    "fulfillmentText": "nama anda james bond , email apa ?",
    "fulfillmentMessages": [
      {
        "text": {
          "text": [
            "nama anda james bond , email apa ?"
          ]
        }
      }
    ],
    "outputContexts": [
      {
        "name": "projects/restokukuhtw/agent/sessions/3c83363b-266e-47db-c971-f5e93c20e2a3/contexts/user_provide_info_nama-followup",
        "lifespanCount": 2,
        "parameters": {
          "namauser": "james bond",
          "namauser.original": "james bond"
        }
      }
    ],
    "intent": {
      "name": "projects/restokukuhtw/agent/intents/81e23c46-d335-4f04-b7bf-b297add880ab",
      "displayName": "user_provide_info_nama"
    },
    "intentDetectionConfidence": 0.87,
    "diagnosticInfo": {
      "webhook_latency_ms": 314
    },
    "languageCode": "id"
  },
  "webhookStatus": {
    "code": 3,
    "message": "Webhook call failed. Error: Failed to parse webhook JSON response: Expect message object but got: null."
  }
}
*/
//===============================================================================================	

?>
