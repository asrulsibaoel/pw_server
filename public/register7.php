<?php

$config = array
(
    'host' => 'mariadb',				// Host
    'user' => 'root',						// Username
    'pass' => 'root',						// Password from the database
    'name' => 'pw',						// SQL DB name
    'gold' => '1000000000',				// Gold applied to new accounts
);

$Zone_ID = 1;	//Server zoneid
$A_ID = 1;	//Server aid

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title> Registration | Perfect World</title>
    <meta http-equiv="content-type" content="text/html" ; charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <center>
        <form id="register" action="<?= $_SERVER['PHP_SELF']; ?>" method=post>
            <br>
            <h3> Registration on the server </h3><br>
            <br>
            <h3> Рerfect World Server 1.5.5 </h3><br>

            <table>
                <tr>
                    <td>Login</td>
                    <td><input class="input_box" type=text name=login></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input class="input_box" type=password name=passwd></td>
                </tr>
                <tr>
                    <td>Repeat password</td>
                    <td><input class="input_box" type=password name=repasswd></td>
                </tr>
                <tr>
                    <td>E-Mail</td>
                    <td><input class="input_box" type=text name=email></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input class="input_submit" type=submit name=submit value="Registration" style="float: right;"></td>
                </tr>


            </table>
        </form>
    </center>
</body>

</html>

<?php

function mysqli_result($res, $row = 0, $col = 0)
{
    $numrows = mysqli_num_rows($res);
    if ($numrows && $row <= ($numrows - 1) && $row >= 0) {
        mysqli_data_seek($res, $row);
        $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
        if (isset($resrow[$col])) {
            return $resrow[$col];
        }
    }
    return false;
}

if (isset($_POST['login'])) {
    $link = new mysqli($config['host'], $config['user'], $config['pass']);
    // Check connection
    if ($link->connect_error) {
        die("Connection failed: " . $link->connect_error);
    }
    mysqli_select_db($link, $config['name']);

    $Login = $_POST['login'];
    $Pass = $_POST['passwd'];
    $Repass = $_POST['repasswd'];
    $Email = $_POST['email'];

    $Login = StrToLower(Trim($Login));
    $Pass = StrToLower(Trim($Pass));
    $Repass = StrToLower(Trim($Repass));
    $Email = Trim($Email);
    if (empty($Login) || empty($Pass) || empty($Repass) || empty($Email)) {
        echo "All fields are not filled in correctly!";
    } elseif (preg_match("[^0-9a-zA-Z_-]", $Login)) {
        die("inside second if");
        echo "Invalid login format";
    } elseif (preg_match("[^0-9a-zA-Z_-]", $Pass)) {
        echo "Invalid password format";
    } elseif (preg_match("[^0-9a-zA-Z_-]", $Repass)) {
        echo "Invalid retry password format";
    } elseif (StrPos('\'', $Email)) {
        echo "Invalid E-Mail Format";
    } elseif ((StrLen($Login) < 4) or (StrLen($Login) > 10)) {
        echo "The login must contain at least 4 and not more than 10 characters.";
    } else {

        $Result = mysqli_query($link, "SELECT name FROM users WHERE name='$Login'") or die("Can't execute query 1." . mysqli_error($link));

        if (mysqli_num_rows($Result)) {
            echo "<font color='red'>Login</font> <b>" . $Login . "</b> <font color='red'>already exists in the database -_-</font>";
        } elseif ((StrLen($Pass) < 4) or (StrLen($Pass) > 10)) {
            echo "The password must contain at least 4 and not more than 10 characters.";
        } elseif ((StrLen($Repass) < 4) or (StrLen($Repass) > 10)) {
            echo "Repeat password must contain at least 4 and not more than 10 characters.";
        } elseif ((StrLen($Email) < 4) or (StrLen($Email) > 25)) {
            echo "E-Mail must contain at least 4 and not more than 25 characters.";
        } else {
            $Result = mysqli_query($link, "SELECT name FROM users WHERE name='$Email'") or die("Can't execute query2." . mysqli_error($link));
            if (mysqli_num_rows($Result)) {
                echo "<font color='red'>E-Mail</font> <b>" . $Email . "</b> <font color='red'>already exists in the database -_-</font>";
            } elseif ($Pass != $Repass) {
                echo "Passwords do not match.";
            } else {
                //$Salt = $Login.$Pass;
                //$Salt = md5($Salt);
                //$Salt = "0x".$Salt;
                $Salt = base64_encode(md5($Login . $Pass, true));
                mysqli_query($link, "call adduser('$Login', '$Salt', '0', '0', '0', '0', '$Email', '0', '0', '0', '0', '0', '0', '0', NULL, '', '$Salt')") or die("Account not registered." . mysqli_error($link));
                $mysqlresult = mysqli_query($link, "select * from `users` WHERE `name`='$Login'");
                $User_ID = mysqli_result($mysqlresult, 0, 'ID');
                mysqli_query($link, "call usecash({$User_ID},{$Zone_ID},0,{$A_ID},0," . $config['gold'] . ",1,@error)") or die("Gold is not issued." . mysqli_error($link));
                echo "<font color='green'>Account name <b>" . $Login . "</b> successfully registered :) User ID: " . $User_ID . " <br/> " . $config['gold'] . " If Gold was applied it will be available in 5-10 minutes.";
            }
        }
    }
}

?>