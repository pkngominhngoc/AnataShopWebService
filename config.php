<?php
  define("DBSERVER", "localhost");
  define("DBUSERNAME","root");
  define("DBPASSWORD","");
  define("DBNAME","anatashop");

  date_default_timezone_set("Asia/Ho_Chi_Minh");

  $conn = mysqli_connect(DBSERVER,DBUSERNAME,DBPASSWORD,DBNAME);
  mysqli_set_charset($conn,"utf8");
  if(!$conn){
    die('Connect Error: '.mysqli_connect_errno());
  }else{

  }
 ?>
