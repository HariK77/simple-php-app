<?php

session_start();

function getTitle() {
    $file = basename($_SERVER['PHP_SELF']);
    $fileNameWithoutExtension = pathinfo($file, PATHINFO_FILENAME); 

    $title = ucwords(str_replace('-', ' ', $fileNameWithoutExtension));

    if ($title === 'Index') {
        $title = 'Home';
    }

    return $title;
}

function baseUrl($request_url = '') {
    if(isset($_SERVER['HTTPS'])){
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    }
    else{
        $protocol = 'http';
    }
    $request_uri = explode('/', $_SERVER['REQUEST_URI']);
    $appPath = '/' . $request_uri[1] . '/';
    
    return $protocol . "://" . $_SERVER['HTTP_HOST'] . $appPath . $request_url;
}

function dd($data)
{
    echo '<pre>';
    print_r($data);
    die();
    echo '</pre>';

}

function ddJs($data)
{
    echo '<script>alert('. print_r($data) . ')</script>';
}

function sanitizeInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}



?>