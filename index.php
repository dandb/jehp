<!-- login form that can possibly be used -->
<!--
<form name="form1" id='login' action='reportBuilder.php' method='POST' accept-charset='UTF-8'>
<fieldset>
<legend>Login</legend>
<input type='hidden' name='submitted' id='submitted' value='1'/>
 
<label for='username' >UserName:</label>
<input type='text' name='username' id='username'  maxlength="50" />
 
<label for='password' >Password or API token:</label>
<input type='password' name='password' id='password' maxlength="70" />
 
<input type='submit' name='Submit' value='Login' />
 
</fieldset>
</form>
-->



<?php
//Get the $USER, $PASSWORD, and $SITE using a config file
$params = parse_ini_file('config');
$allValues = array_values($params);
$USER = $allValues[0];
$PASSWORD = $allValues[1];
$SITE = $allValues[2];



//*****************************************
//Get the $USER, $PASSWORD, and $SITE  using a login form at the top of index.php
//$USER = $_POST['username'];
//$PASSWORD = $_POST['password'];
//$SITE='ci.malibucoding.com';
//*****************************************


//Pulls the xml code and writes it to status.xml in the results subdirectory.
$xml = file_get_contents("https://$USER:$PASSWORD@$SITE/api/xml");    
file_put_contents('results/status.xml' , $xml);

//Writes the job health html page to display.
include('pageWriter.php');


//Displays the job health in quick_health.html
$myfile = fopen("quick_health.html", "r") or die("Unable to open file!");
echo fread($myfile,filesize("quick_health.html"));
fclose($myfile);

?>
