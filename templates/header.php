<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $page_title ;?></title>
<?php
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $link_part = explode("/",$actual_link);
    
    // if(isset($inside_folder)){
    //   $actual_link = str_replace($link_part[count($link_part) - 3].'/'.$link_part[count($link_part) - 2].'/'.$link_part[count($link_part) - 1],"api/notification",$actual_link);
    // }else{
    // }
    $actual_link = str_replace($link_part[count($link_part) - 1],"api/notification",$actual_link);
    include "css_list.php";
?>