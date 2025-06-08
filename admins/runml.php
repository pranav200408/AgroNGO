<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
}

@ini_set('output_buffering', 'off');
@ini_set('zlib.output_compression', false);
@ini_set('implicit_flush', true);
ob_implicit_flush(true);
while (ob_get_level() > 0) ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Run ML Model</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f5f5f5;
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }
        header, footer {
            background-color: #007bff;
            color: white;
            padding: 15px 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        header h1, footer p {
            margin: 0;
        }
        .container {
            max-width: 900px;
            margin: 20px auto 60px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h3 {
            color: #007bff;
            margin-bottom: 15px;
        }
        .console-box {
            background: #fefefe;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            height: 500px;
            overflow-y: auto;
            white-space: pre-wrap;
            font-size: 14px;
            line-height: 1.5;
            box-shadow: inset 0 0 8px #ddd;
        }
        .loader {
            margin-bottom: 20px;
            color: #007bff;
            font-weight: bold;
            animation: blink 1s infinite;
        }
        @keyframes blink {
            0% { opacity: 1; }
            50% { opacity: 0.4; }
            100% { opacity: 1; }
        }
        .glow {
            text-shadow: 0 0 3px #ccc;
        }
        a.btn-primary {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<header>
    <h1>AgroNGO - Machine Learning Console</h1>
</header>

<div class="container">
    <div class="loader glow" id="loader">
        <i class="fas fa-envelope"></i> Sending Mails <span id="dots">.</span>
    </div>

    <h3 class="glow"><i class="fa fa-terminal"></i> ML Model Console</h3>

    <div class="console-box" id="console">
<?php
echo ">>> sys.path:\n";
echo shell_exec("C:\\Python313\\python.exe -m site");

echo "\n>>> Starting ML Script...\n\n";

$python = "C:\\Python313\\python.exe";
$script = "C:\\xampp\\htdocs\\AgroNGO\\ML\\mlscript.py";

$descriptorspec = [
    1 => ['pipe', 'w'], 
    2 => ['pipe', 'w']  
];

$process = proc_open("\"$python\" \"$script\"", $descriptorspec, $pipes);

if (is_resource($process)) {
    while (!feof($pipes[1]) || !feof($pipes[2])) {
        $stdout = fgets($pipes[1]);
        $stderr = fgets($pipes[2]);

        if ($stdout !== false) {
            echo htmlentities($stdout);
            flush();
        }

        if ($stderr !== false) {
            echo "<span style='color:red;'>ERROR: " . htmlentities($stderr) . "</span>";
            flush();
        }

        usleep(100000);
    }

    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($process);
}

echo "\n>>> All tasks completed.\n";
?>
    </div>

    <a href="dashboard.php" class="btn btn-primary">
        <i class="fa fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

<footer>
    <p>© <?php echo date("Y"); ?> AgroNGO. All rights reserved.</p>
</footer>

<script>
    const consoleBox = document.getElementById("console");
    
    // MutationObserver to scroll on new output
    const observer = new MutationObserver(() => {
        consoleBox.scrollTop = consoleBox.scrollHeight;
    });
    observer.observe(consoleBox, { childList: true, subtree: true });

    // Also fallback: periodically ensure scroll stays at bottom
    setInterval(() => {
        consoleBox.scrollTop = consoleBox.scrollHeight;
    }, 200);

    // Dot animation for loader
    let dots = document.getElementById("dots");
    let count = 1;
    setInterval(() => {
        count = (count % 5) + 1;
        dots.textContent = ".".repeat(count);
    }, 500);

    // Hide loader after page load + 1.5s
    window.onload = () => {
        setTimeout(() => {
            document.getElementById("loader").style.display = "none";
        }, 1500);
    };
</script>

</body>
</html>
