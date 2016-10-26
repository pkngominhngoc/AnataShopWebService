<?php
  include_once("config.php");

  $truyvan = "SELECT * FROM loaisanpham";
  $ketqua = mysqli_query($conn, $truyvan);
  $chuoijson = array();
  echo "{";
	echo "\"LOAISANPHAM\":[";
  if($ketqua){
  			while ($dong=mysqli_fetch_array($ketqua)) {
  				// cách 1


  				// end cách 1
  				// laydanhsachloaisp($dong["MALOAISP"]);

  				//cách 2
  				 array_push($chuoijson, array("TENLOAISP"=>$dong["TENLOAISP"],'MALOAISP' => $dong["MALOAISP"]));
  				//end cách 2
  			}

  			echo json_encode($chuoijson,JSON_UNESCAPED_UNICODE);
  		}
      echo "]}";

?>
