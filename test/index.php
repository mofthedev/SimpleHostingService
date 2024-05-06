<h1>Your hosting service is ready.</h1>
<h2>You can upload your own files.</h2>
<br>
<?php
error_reporting(E_ALL);

echo date("d-m-Y H:i:s");

echo "<br><br>";

$db = mysqli_connect('localhost', 'dbusr***', '***', 'dbstorage***'); // Okay

if(!$db)
{
    echo "Couldn't connect to the DB!";
}
else
{
    echo "DB connection works perfectly.";
}
?>