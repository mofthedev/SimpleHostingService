<?php
session_start();

$databaseFile = 'db.csv'; // CHMOD = 750 !!! It would be easily hackable otherwise.

$maxAttempts = 3;
$timeoutMinutes = 15;

if (!isset($_SESSION['attempts']))
{
    $_SESSION['attempts'] = 0;
}

// if(isset($_GET['reset']))
// {
//     $_SESSION['attempts'] = 0;
// }


$querylogfile = "queries.log";
if(!file_exists($querylogfile))
{
    file_put_contents($querylogfile,"");
}

function addLog($text = "")
{
    global $querylogfile;

    $additional = "(".date("d/m/Y H:i:s").")";
    file_put_contents($querylogfile, file_get_contents($querylogfile)."\n".$additional." ".$text."\n");
}

function checkAttempts()
{
    global $maxAttempts, $timeoutMinutes;

    $_SESSION['attempts']++;

    if ($_SESSION['attempts'] > $maxAttempts)
    {
        $lastAttemptTime = $_SESSION['last_attempt'] ?? 0;
        if (time() - $lastAttemptTime < $timeoutMinutes * 60)
        {
            return true;
        }
        $_SESSION['attempts'] = 1; // Reset
    }

    $_SESSION['last_attempt'] = time();
    return false;
}

// Function to search for student in database
function searchStudent($idDigits, $studentNo)
{
    global $databaseFile;

    if (($handle = fopen($databaseFile, "r")) !== FALSE)
    {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
        {
            $requiredIdNo = $data[0];
            if (!empty($requiredIdNo) && $requiredIdNo == $idDigits && $data[1] == $studentNo)
            {
                fclose($handle);
                return $data;
            }
        }
        fclose($handle);
    }
    return null;
}

$screen_buffer = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    addLog(json_encode($_POST));

    $digit1 = $_POST['digit1'];
    $digit2 = $_POST['digit2'];
    $digit3 = $_POST['digit3'];
    $digit4 = $_POST['digit4'];
    $digit5 = $_POST['digit5'];
    $idDigits = $digit1 . '**' . $digit2 . '**' . $digit3 . '**' . $digit4 . $digit5;
    $studentNo = $_POST['student_number'];

    if (checkAttempts())
    {
        $screen_buffer .= "<p class='error'>Too many failed attempts. Please <a href=\"./\">try again</a> later.</p>";
    }
    else
    {
        $result = searchStudent($idDigits, $studentNo);

        if ($result)
        {
            $screen_buffer .= "<div class='result'>";
            
            $screen_buffer .= "<h2>Here are your hosting informations:</h2>";

            $screen_buffer .= "<div class='heading'>Personal</div>";

            $screen_buffer .= "<p>ID Number: " . $result[0] . "</p>";
            $screen_buffer .= "<p>Student Number: " . $result[1] . "</p>";

            $screen_buffer .= "<div class='heading'>FTP</div>";

            $screen_buffer .= "<p>IP: This server's IP</p>";
            $screen_buffer .= "<p>FTP Port: 21</p>";
            $screen_buffer .= "<p>Linux Username: " . $result[2] . "</p>";
            $screen_buffer .= "<p>Linux Password: " . $result[5] . "</p>";

            $screen_buffer .= "<div class='heading'>MariaDB (MySQL)</div>";

            $screen_buffer .= "<p>Database User: " . $result[3] . "</p>";
            $screen_buffer .= "<p>Database Password: " . $result[6] . "</p>";
            $screen_buffer .= "<p>Database Name: " . $result[4] . "</p>";

            $screen_buffer .= "</div>";

            // Reset
            $_SESSION['attempts'] = 0;
        }
        else
        {
            $screen_buffer .= "<p class='error'>No match found. Please <a href=\"./\">try again</a>.</p>";
        }
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="A simple hosting system for the students of computer engineering dept. of Bursa Technical University.">
    <meta name="keywords" content="simple, hosting, education, academy">
    <meta name="author" content="Möf Selvi">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Hosting for BTU CompEng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            /* height: 100vh; */
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            margin: 100px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 55vw;
            text-align: center;
        }
        .container h1 {
            color: #333;
        }
        .container form {
            margin-top: 20px;
        }
        .container form label {
            display: block;
            margin-bottom: 8px;
        }
        .container form input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .container form input[type="submit"] {
            width: 100%;
            padding: 12px;
            border-radius: 4px;
            border: none;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .container form input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .result {
            background-color: #f0f0f0;
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: left;
        }
        .error {
            color: #ff0000;
            margin-top: 20px;
        }
        .info {
            color: #2a378c;
            margin-top: 20px;
        }
        .heading {
            color: #2a378c;
            padding-top: 5px;
            font-weight: bolder;
        }
    </style>
</head>

<body>
    <div class="container">
    <h1>Get Your Hosting Details</h1>

    <?php
        if ($_SESSION['attempts'] > $maxAttempts)
        {
            $lastAttemptTime = $_SESSION['last_attempt'] ?? 0;
            if (time() - $lastAttemptTime < $timeoutMinutes * 60)
            {
                echo "<p class='error'>Too many attempts. Please <a href=\"./\">try again</a> around ".intval($timeoutMinutes-((time() - $lastAttemptTime)/60))." minutes later.</p>";;
            }
        }
    ?>

    <?php
    if(empty($screen_buffer))
    {
    
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="digit1">1st Digit of ID Number:</label>
        <input type="text" id="digit1" name="digit1" maxlength="1" required pattern="[0-9]">
        <br><br>
        <label for="digit2">3rd Digit of ID Number:</label>
        <input type="text" id="digit2" name="digit2" maxlength="1" required pattern="[0-9]">
        <br><br>
        <label for="digit3">6th Digit of ID Number:</label>
        <input type="text" id="digit3" name="digit3" maxlength="1" required pattern="[0-9]">
        <br><br>
        <label for="digit4">9th Digit of ID Number:</label>
        <input type="text" id="digit4" name="digit4" maxlength="1" required pattern="[0-9]">
        <br><br>
        <label for="digit5">10th Digit of ID Number:</label>
        <input type="text" id="digit5" name="digit5" maxlength="1" required pattern="[0-9]">
        <br><br>
        <label for="student_number">Student Number:</label>
        <input type="text" id="student_number" name="student_number" minlength="5" maxlength="20" required pattern="[0-9]{5,}">
        <br><br>
        <input type="submit" value="Submit">
        <br>
        <p class='info'>By using this system, you agree that all actions you take and all data you provide can be stored and used by the system administrators.</p>
    </form>

    <?php
    }
    else
    {
        echo $screen_buffer;
    }
    ?>
    </div>
</body>

</html>
