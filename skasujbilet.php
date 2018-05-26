<?PHP session_start();
// header("Pragma: no-cache");
 //     header("cache-Control: no-cache, must-revalidate"); // HTTP/1.1
 //     header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

	  
   	if(isset($_SESSION['czas'])&&(time()-$_SESSION['czas']>900))
	    {
		  session_destroy();
	if(isset($_SESSION['login']))
		unset($_SESSION['login']);
	if(isset($_SESSION['user']))
		unset($_SESSION['user']);
	if(isset($_SESSION['czas']))
		unset($_SESSION['czas']);		
	if(isset($_SESSION['ID']))
		unset($_SESSION['ID']);

		}
		

   
   ?>
<html>
<head>
 <META HTTP-EQUIV="Content-type" CONTENT="text/html; charset=UTF-8">
 <meta http-equiv="Content-Language" content="pl">
 <META HTTP-EQUIV="EXPIRES" CONTENT=0>
  <body>

<?PHP

$e=1;

include 'pas2.php';
include 'listabiletow.php';
//logowanie i otwarcie sesji
$zalogowano=0;

   //ob_start();


if(isset($_POST['log'])&&!empty($_POST['login'])&&!empty($_POST['haslo']))
{
 if(array_key_exists(md5($_POST['login']),$hasla))
 {
  if($hasla[md5($_POST['login'])]==md5($_POST['haslo']))
   {
	$zalogowano=1;
	$_SESSION['login']=1;
	$_SESSION['user']=$_POST['login'];
	$_SESSION['ID']=md5($_POST['login']);
	$_SESSION['czas']=time();
   }
   else
   {
   printf("<center>Błędne dane logowania</center>");
   }
  }
  else
   {
    printf("<center>nieprawidłowy login</center>");
   }
 
}

if(isset($_POST['logout'])&&!empty($_POST['logout']))
{
  session_destroy();
	if(isset($_SESSION['login']))
		unset($_SESSION['login']);
	if(isset($_SESSION['user']))
		unset($_SESSION['user']);
	if(isset($_SESSION['czas']))
		unset($_SESSION['czas']);
		if(isset($_SESSION['ID']))
		unset($_SESSION['ID']);
	
	$zalogowany=0;
}

if(isset($_SESSION['login'])&&(time()-$_SESSION['czas'])<900&&array_key_exists($_SESSION['ID'],$hasla))
{
 $_SESSION['czas']=time();
 $zalogowano=1;
 
if(isset($_GET['bilet'])&& !empty($_GET['bilet']))
{


$_SESSION['bilet']=$_GET['bilet'];
$_SESSION['zapis']=1;

}
 
if(isset($_POST['skasuj'])=="skasuj"&&$_SESSION['bilet']!="")  //kasowanie biletu
  {
 
    $numer=",\"".$_SESSION['bilet']."\"";
    $fp = fopen('skasowane.txt', 'a+');
      fwrite($fp, $numer);
      fclose($fp);
	  
    if(isset($_SESSION['bilet']))
		unset($_SESSION['bilet']);
		 if(isset($_SESSION['waznosc']))
		unset($_SESSION['waznosc']);
		
	printf("<br>skasowano<br> możesz skasować kolejny bilet<br>"); 
  }

 }
else{//printf("");
if(!$zalogowano){
printf("<table border=0 align=center> <tr><td align=center>");
    printf("<form name='login' method='POST' action='skasujbilet.php' enctype='multipart/form-data'>");
	printf("login:</td></tr><tr><td align=center>");
    printf("<input type='text' length=20 name='login'><br>");
	printf("</td></tr><tr><td align=center>hasło:</td></tr><tr><td>");
	printf("<input type='password' length=20 name='haslo'><br>");
   printf("</td></tr><tr><td align=center>");
	printf("<input type='submit' value='logowanie' name='log'></form>");
		printf("</td></tr></table>");
		
		
	}}
	
		if(isset($_GET['bilet'])&&!empty($_GET['bilet']))
		 {
		$wsad="";
		  if(in_array($_GET['bilet'],$bilety))
		    {
           
			//	$csv = array_map('str_getcsv', file("skasowane.txt"));
			//	var_dump($csv);
				
				   $fp = fopen('skasowane.txt', 'r');
				   $contents = fread($fp, filesize('skasowane.txt'));
                   fclose($fp);
				     
					 $csv=explode(",",str_replace("\"","",$contents));
					// var_dump($csv);
				
				if(!in_array($_GET['bilet'],$csv))
				{
				 $_SESSION['waznosc']=1;
			     $kolor="00ff00";
				 $wsad="\r\n\t\tbielt numer <br> ".$_GET['bilet']." <br> jest ważny, <br>  można go skasować ";
				}
				else
				{
				 $_SESSION['waznosc']=0;
				$kolor="ff0000";
				 $wsad="\r\n\t\tbielt numer <br> ".$_GET['bilet']." <br> został już skasowany ";
				}
				
			}
			else
			{
			 $_SESSION['waznosc']=0;
			 $kolor="ff0000";
			 $wsad="\r\n\t\tbielt o numerze <br> ".$_GET['bilet']." <br> nie istnieje ";
			}
			 printf("\r\n\t<table border=0 align=center> <tr><td align=center style='font-size: 38; color:#000000; background-color:#".$kolor."'>");
              printf($wsad);
		printf("\r\n\t</td></tr></table>");		

		 }
		     
  ?>
<?PHP
 if(isset($zalogowano)&&$zalogowano)
  {
  //wyloguj...
  printf(" <form name='logout' method='POST' action='skasujbilet.php' enctype='multipart/form-data'>");
printf("	<input type='submit' value='wyloguj' name='logout'></form>");

  
  if(isset($_SESSION['waznosc'])&&$_SESSION['waznosc']&&$_SESSION['bilet']!="")
   {
   printf("bilet numer".$_SESSION['bilet']."<br>");
  printf(" <form name='zawartosc' method='POST' action='skasujbilet.php' enctype='multipart/form-data'>");
  printf("	<input type='submit' value='skasuj' name='skasuj'>");
  printf("  </form> ");
   
   }
  
  		   $fp = fopen('skasowane.txt', 'r');
		   $contents = fread($fp, filesize('skasowane.txt'));
           fclose($fp);
				     
			$csv=explode(",",str_replace("\"","",$contents));
			
		printf("\r\n\t\t<br> skasowano ".count($csv)." z ".count($bilety)." biletów");
		printf("\r\n\t\t<br> numery skasowanych biletów:");
		for($i=0;$i<count($csv);$i++)
		 printf("\r\n\t\t<br> ".$csv[$i]);
  

   }
   
 
   ?>
   
 </body>
</html>
