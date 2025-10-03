<?php
require 'Includes/functions.php';
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/';
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
require 'Includes/database.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        if (isset($_GET['component'])) {
            $componentName = cleanString($_GET['component']);
            if (file_exists("Controller/$componentName.php")) {
                require "Controller/$componentName.php";
            }
        }
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<base href="<?php echo $basePath; ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok'it</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
    <link rel="stylesheet" href="./Includes/CSS/style.css">
</head>
<body>
<?php require './_partials/navbar.php';
    if (isset($_GET['component'])) {
        $componentName = cleanString($_GET['component']);
        if (file_exists("Controller/$componentName.php")) {
            require "Controller/$componentName.php";
        }
    } else {
        require 'Controller/home.php';
    }
?>
</body>
</html>