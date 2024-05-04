<h1>Sunucunuz hazır.</h1>
<h2>Dosyalarınızı yükleyebilirsiniz.</h2>
<br>
<?php
error_reporting(E_ALL);

echo date("d-m-Y H:i:s");

echo "<br><br>";

$db = mysqli_connect('localhost', 'dbusrmof001', 'HAFP5jUyhvVt', 'dbstoragemof001'); // Okay
// $db = mysqli_connect('localhost', 'dbusrmof002', 'XEZGAn1sHJEk', 'dbstoragemof002'); // Okay
// $db = mysqli_connect('localhost', 'dbusrmof001', 'HAFP5jUyhvVt', 'dbstoragemof002'); // Error

if(!$db)
{
    echo "Veritabanı bağlantısında hata!";
}
else
{
    echo "Veritabanı bağlantısı sorunsuz.";
}
?>