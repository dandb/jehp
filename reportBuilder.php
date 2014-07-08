<?php
$USER = $_POST['username'];
$PASSWORD = $_POST['password'];
$SITE='ci.malibucoding.com';
$xml = file_get_contents("https://$USER:$PASSWORD@$SITE/api/xml");
file_put_contents('results/status.xml' , $xml);


include('pageWriter.php');

$myfile = fopen("quick_health.html", "r") or die("Unable to open file!");
echo fread($myfile,filesize("quick_health.html"));
fclose($myfile);
?>
