<?php
// index.php for creating controller instances

require __DIR__ . "/inc/bootstrap.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

//CORS headers
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
    header('Access-Control-Allow-Origin:*');
    header('Access-Control-Allow-Headers:*');
    header('Access-Control-Allow-Methods:POST,GET,DELETE,PUT,OPTIONS');
    header('Access-Control-Allow-Credentials:true');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
    header('Access-Control-Allow-Origin:*');
    header('Access-Control-Allow-Headers:*');
    header('Access-Control-Allow-Methods:POST,GET,DELETE,PUT,OPTIONS');
    header('Access-Control-Allow-Credentials:true');
    exit;
}

header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers:*');
header('Access-Control-Allow-Methods:POST,GET,DELETE,PUT,OPTIONS');
header('Access-Control-Allow-Credentials:true');

$uri = explode( '/', $uri );

if ((isset($uri[2]) && (($uri[2] != 'user') && ($uri[2] != 'rating'))) || !isset($uri[3])) {
    header("HTTP/1.1 404 Not Found");
    exit();
}
elseif (isset($uri[2]) && ($uri[2]=='user')) {
    require PROJECT_ROOT_PATH . "/Controller/Api/UserController.php";
    $objFeedController = new UserController();
    $strMethodName = $uri[3] . 'Action';
    $objFeedController->{$strMethodName}();
}
elseif (isset($uri[2]) && ($uri[2]=='rating')) {
    require PROJECT_ROOT_PATH . "/Controller/Api/RatingController.php";
    $objFeedController = new RatingController();
    $strMethodName = $uri[3] . 'Action';
    $objFeedController->{$strMethodName}();
}
?>