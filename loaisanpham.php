
<?php
  include_once("config.php");

  //$ham = $_GET["ham"];
   $ham = $_POST["ham"];

  switch($ham){
      case 'LayDanhSachMenu':
        $ham();
        break;
      case 'DangKyThanhVien':
        $ham();
        break;
      case 'KiemTraDangNhap':
        $ham();
        break;
      case 'LayDanhSachCacThuongHieuLon':
        $ham();
        break;
      case 'LayDanhSachTopDienThoaiVaMayTinhBang':
        $ham();
        break;
      case 'LayDanhSachTopPhuKien':
        $ham();
        break;
      case 'LayDanhSachPhuKien':
        $ham();
        break;
      case 'LayDanhSachTienIch':
        $ham();
      case 'LayTopTienIch':
        $ham();
      break;
      case 'LayLogoThuongHieuLon':
        $ham();
      break;
      case 'LayDanhSachSanPhamMoi':
        $ham();
      break;
      case 'LayDanhSachSanPhamTheoMaloaiDanhMuc':
        $ham();
      break;
      case 'LayDanhSachSanPhamTheoMaThuongHieu':
        $ham();
      break;
      case 'LaySanPhamVaChiTietTheoMASP':
        $ham();
      break;
      case 'ThemDanhGia':
        $ham();
      break;
      case 'LayDanhSachDanhGiaTheoMASP':
        $ham();
      break;
      case 'ThemHoaDon':
        $ham();
      break;
    }


    function ThemHoaDon(){
      global $conn;

      if(isset($_POST["danhsachsanpham"]) || isset($_POST["tennguoinhan"]) || isset($_POST["sodt"]) || isset($_POST["diachi"]) || isset($_POST["chuyenkhoan"])){
          $danhsachsanpham = $_POST["danhsachsanpham"];
          $tennguoinhan = $_POST["tennguoinhan"];
          $sodt = $_POST["sodt"];
          $diachi = $_POST["diachi"];
          $chuyenkhoan = $_POST["chuyenkhoan"];
      }

      $ngayhientai = date("d/m/y");
      $ngaygiao = date_create($ngayhientai);
      $ngaygiao = date_modify($ngaygiao,"+3 days");
      $ngaygiao = date_format($ngaygiao,"d/m/Y");

      $trangthai = "Chờ kiểm duyệt";

      $truyvan = "INSERT INTO hoadon (NGAYMUA,NGAYGIAO,TRANGTHAI,TENNGUOINHAN,SODT,DIACHI) VALUES ('".$ngayhientai."','".$ngaygiao."','".$trangthai."'
      ,'".$tennguoinhan."','".$sodt."','".$diachi."')";
      $ketqua = mysqli_query($conn,$truyvan);

      if($ketqua){
            $mahd = mysqli_insert_id($conn);
            $chuoijsonandroid = json_decode($danhsachsanpham);
            $arrayDanhSachSanPham = $chuoijsonandroid->DANHSACHSANPHAM;
            $dem = count($arrayDanhSachSanPham);
            for($i=0; $i< $dem; $i++){
                $jsonobject = $arrayDanhSachSanPham[$i];
                $masp = $jsonobject->masp;
                $soluong = $jsonobject->soluong;

                $truyvancon = "INSERT INTO chitiethoadon (MAHD,MASP,SOLUONG) VALUES ('".$mahd."','".$masp."','".$soluong."')";
                $ketqua1 = mysqli_query($conn,$truyvancon);

            }
            echo "{ ketqua : true }";

      }else{
          	echo "{ ketqua : false }".mysqli_error($conn);
      }
      mysqli_close($conn);
    }

    function LayDanhSachDanhGiaTheoMASP(){
      global $conn;
      $chuoijson = array();

      if(isset($_POST["masp"]) || isset($_POST["limit"])){
        $masp = $_POST["masp"];
        $limit = $_POST["limit"];

      }

      $truyvan = "SELECT * FROM danhgia WHERE MASP = ".$masp." ORDER BY NGAYDANHGIA LIMIT ".$limit." ,10";
      $ketqua = mysqli_query($conn,$truyvan);
      echo "{";
      echo "\"DANHSACHDANHGIA\":";
      if($ketqua){
          while($dong = mysqli_fetch_array($ketqua)){
              $chuoijson[] = $dong;
          }
      }

      echo json_encode($chuoijson,JSON_UNESCAPED_UNICODE);
      echo "}";
      mysqli_close($conn);
    }

    function ThemDanhGia(){
      global $conn;


      if(isset($_POST["madg"]) || isset($_POST["masp"]) || isset($_POST["tenthietbi"]) || isset($_POST["tieude"]) || isset($_POST["noidung"]) || isset($_POST["sosao"])){
          $madg = $_POST["madg"];
          $masp = $_POST["masp"];
          $tenthietbi = $_POST["tenthietbi"];
          $tieude = $_POST["tieude"];
          $noidung = $_POST["noidung"];
          $sosao = $_POST["sosao"];
      }

      $ngaydang = date("d/m/y");

      $truyvan = "INSERT INTO danhgia (MADG,MASP,TENTHIETBI,TIEUDE,NOIDUNG,SOSAO,NGAYDANHGIA) VALUES ('".$madg."','".$masp."','".$tenthietbi."'
      ,'".$tieude."','".$noidung."','".$sosao."','".$ngaydang."')";

      $ketqua = mysqli_query($conn,$truyvan);

      if($ketqua){
          	echo "{ ketqua : true }";
      }else{
          	echo "{ ketqua : false }".mysqli_error($conn);
      }
      mysqli_close($conn);
    }

    function LaySanPhamVaChiTietTheoMASP(){
        global $conn;
        $chuoijson = array();
        $chuoijsonchitiet = array();
        if(isset($_POST["masp"])){
              $masp = $_POST["masp"];

        }
        $truyvan = "SELECT * FROM sanpham sp, nhanvien nv WHERE MASP = ".$masp." AND sp.MANV = nv.MANV";
        $ketqua = mysqli_query($conn,$truyvan);
        echo "{";
        echo "\"CHITIETSANPHAM\":";

        $truyvanchitiet = "SELECT * FROM chitietsanpham WHERE MASP=".$masp;
        $ketquachitiet = mysqli_query($conn,$truyvanchitiet);

        if($ketquachitiet){
          while($dongchitiet = mysqli_fetch_array($ketquachitiet)){

              array_push($chuoijsonchitiet,  array($dongchitiet["TENCHITIET"]=>$dongchitiet["GIATRI"]));

          }
        }

        if($ketqua){
            while($dong = mysqli_fetch_array($ketqua)){
              array_push($chuoijson,array("MASP"=>$dong["MASP"],'TENSP' => $dong["TENSP"],'GIATIEN' => $dong["GIA"],'SOLUONG' => $dong["SOLUONG"],
              'ANHNHO' => $dong["ANHNHO"],'THONGTIN' => $dong["THONGTIN"],'MALOAISP' => $dong["MALOAISP"],'MATHUONGHIEU' => $dong["MATHUONGHIEU"],
              'MANV' => $dong["MANV"],'TENNV' => $dong["TENNV"],'LUOTMUA' => $dong["LUOTMUA"],'THONGSOKYTHUAT' =>$chuoijsonchitiet));
            }
        }



        echo json_encode($chuoijson,JSON_UNESCAPED_UNICODE);
        echo "}";
    }


    function LayDanhSachSanPhamTheoMaThuongHieu(){
      global $conn;
      $chuoijson = array();

      if(isset($_POST["maloaisp"]) || isset($_POST["limit"])){
            $maloai = $_POST["maloaisp"];
            $limit = $_POST["limit"];

      }
      echo "{";
      echo "\"DANHSACHSANPHAM\":";

      $chuoijson = LayDanhSachSanPhamTheoMaLoaiThuongHieu($conn,$maloai,$chuoijson,$limit);

      echo json_encode($chuoijson,JSON_UNESCAPED_UNICODE);
      echo "}";
    }

    function LayDanhSachSanPhamTheoMaloaiDanhMuc(){
        global $conn;
        $chuoijson = array();

        if(isset($_POST["maloaisp"]) || isset($_POST["limit"])){
              $maloai = $_POST["maloaisp"];
              $limit = $_POST["limit"];

        }
        $chuoijson = LayDanhSachDanhMucSanPhamTheoMaLoai($conn,$maloai,$chuoijson,$limit);
        echo "{";
        echo "\"DANHSACHSANPHAM\":";

        echo json_encode($chuoijson,JSON_UNESCAPED_UNICODE);
        echo "}";


    }

    function LayDanhSachSanPhamMoi(){
      global $conn;
      // Truy vấn điện thoại
      $truyvan = "SELECT * FROM sanpham ORDER BY MASP DESC LIMIT 20 ";
      $ketqua = mysqli_query($conn, $truyvan);
      $chuoijson = array();
      echo "{";
      echo "\"DANHSACHSANPHAMMOIVE\":";
      if($ketqua){
          while($dong = mysqli_fetch_array($ketqua)){

              array_push($chuoijson, array("MASP"=>$dong["MASP"],'TENSP' => $dong["TENSP"],'GIATIEN' => $dong["GIA"],'HINHSANPHAM' =>"http://".$_SERVER['SERVER_NAME'].":8080"."/webanata".$dong["ANHLON"]));

          }

      }

      echo json_encode($chuoijson,JSON_UNESCAPED_UNICODE);
      echo "}";

    }


    function LayLogoThuongHieuLon(){
      global $conn;
      // Truy vấn điện thoại
      $truyvan = "SELECT * FROM thuonghieu ";
      $ketqua = mysqli_query($conn, $truyvan);
      $chuoijson = array();
      echo "{";
      echo "\"DANHSACHTHUONGHIEU\":";
      if($ketqua){
          while($dong = mysqli_fetch_array($ketqua)){

              array_push($chuoijson, array("MASP"=>$dong["MATHUONGHIEU"],'TENSP' => $dong["TENTHUONGHIEU"],'HINHSANPHAM' =>"http://".$_SERVER['SERVER_NAME'].":8080"."/webanata/".$dong["HINHTHUONGHIEU"]));

          }

      }

      echo json_encode($chuoijson,JSON_UNESCAPED_UNICODE);
      echo "}";
    }

    function LayTopTienIch(){
      global $conn;
      // Truy vấn điện thoại
      $ketqua = LayDanhSachLoaiSanPhamTheoMaLoai($conn,82);
      $chuoijson = array();

      echo "{";
      echo "\"TOPTIENICH\":";
      if($ketqua){
            while ($dong = mysqli_fetch_array($ketqua)) {
                $ketquacon = LayDanhSachLoaiSanPhamTheoMaLoai($conn,$dong["MALOAISP"]);
                if($ketquacon){
                    while($dongcon = mysqli_fetch_array($ketquacon)){
                        $chuoijson = LayDanhSachSanPhamTheoMaLoai($conn, $dongcon["MALOAISP"],$chuoijson,10);
                    }
                }
          }
        }
        echo json_encode($chuoijson,JSON_UNESCAPED_UNICODE);
        echo "}";
    }

    function LayDanhSachTienIch(){
      global $conn;
      // Truy vấn điện thoại
      $ketqua = LayDanhSachLoaiSanPhamTheoMaLoai($conn,82);
      $chuoijson = array();

      echo "{";
      echo "\"DANHSACHTIENICH\":";
      if($ketqua){
            while ($dong = mysqli_fetch_array($ketqua)) {
                $ketquacon = LayDanhSachLoaiSanPhamTheoMaLoai($conn,$dong["MALOAISP"]);
                if($ketquacon){
                    while($dongcon = mysqli_fetch_array($ketquacon)){
                        $chuoijson = LayDanhSachSanPhamTheoMaLoai($conn, $dongcon["MALOAISP"],$chuoijson,1);
                    }
                }
          }

      }

      echo json_encode($chuoijson,JSON_UNESCAPED_UNICODE);
      echo "}";
    }

    function LayDanhSachLoaiSanPhamTheoMaLoai($conn,$maloaisp){
      $truyvancha = "SELECT * FROM loaisanpham lsp WHERE lsp.MALOAI_CHA = ".$maloaisp;
      $ketqua = mysqli_query($conn, $truyvancha);

      return $ketqua;
    }

    // Lay danh sach san pham theo danh muc
    function LayDanhSachDanhMucSanPhamTheoMaLoai($conn, $maloaisp, $chuoijson, $limit){

        $truyvantienich = "SELECT * FROM loaisanpham lsp, sanpham sp  WHERE lsp.MALOAISP =".$maloaisp."  AND lsp.MALOAISP = sp.MALOAISP ORDER BY sp.LUOTMUA DESC LIMIT ".$limit.",20";

        $ketquacon = mysqli_query($conn, $truyvantienich);

        if($ketquacon){
            while($dongtienich = mysqli_fetch_array($ketquacon)){

              array_push($chuoijson, array("MASP"=>$dongtienich["MASP"],'TENSP' => $dongtienich["TENSP"],'GIATIEN' => $dongtienich['GIA'] ,'HINHSANPHAM' =>"http://".$_SERVER['SERVER_NAME'].":8080"."/webanata".$dongtienich["ANHLON"],'HINHSANPHAMNHO' =>"http://".$_SERVER['SERVER_NAME'].":8080"."/webanata".$dongtienich["ANHNHO"]));

            }

        }
        return $chuoijson;

    }


    // Lay danh sach san pham theo thuong hieu
    function LayDanhSachSanPhamTheoMaLoaiThuongHieu($conn, $mathuonghieu, $chuoijson, $limit){

        $truyvantienich = "SELECT * FROM thuonghieu th, sanpham sp  WHERE th.MATHUONGHIEU =".$mathuonghieu."  AND th.MATHUONGHIEU = sp.MATHUONGHIEU ORDER BY sp.LUOTMUA DESC LIMIT ".$limit.",20";

        $ketquacon = mysqli_query($conn, $truyvantienich);

        if($ketquacon){
            while($dongtienich = mysqli_fetch_array($ketquacon)){

                    array_push($chuoijson, array("MASP"=>$dongtienich["MASP"],'TENSP' => $dongtienich["TENSP"],'GIATIEN' => $dongtienich['GIA'] ,'HINHSANPHAM' =>"http://".$_SERVER['SERVER_NAME'].":8080"."/webanata".$dongtienich["ANHLON"],'HINHSANPHAMNHO' =>"http://".$_SERVER['SERVER_NAME'].":8080"."/webanata".$dongtienich["ANHNHO"]));

            }

        }
        return $chuoijson;

    }

    function LayDanhSachSanPhamTheoMaLoai($conn, $maloaisp, $chuoijson, $limit){

        $truyvantienich = "SELECT * FROM loaisanpham lsp, sanpham sp  WHERE lsp.MALOAISP =".$maloaisp."  AND lsp.MALOAISP = sp.MALOAISP ORDER BY sp.LUOTMUA DESC LIMIT ".$limit;

        $ketquacon = mysqli_query($conn, $truyvantienich);

        if($ketquacon){
            while($dongtienich = mysqli_fetch_array($ketquacon)){

                    array_push($chuoijson, array("MASP"=>$dongtienich["MASP"],'TENSP' => $dongtienich["TENLOAISP"],'GIATIEN' => $dongtienich['GIA'] ,'HINHSANPHAM' =>"http://".$_SERVER['SERVER_NAME'].":8080"."/webanata".$dongtienich["ANHLON"]));

            }

        }
        return $chuoijson;

    }
    function LayDanhSachPhuKien(){
      global $conn;
      // Lấy danh sách phụ kiện cha
      $truyvancha = "SELECT * FROM loaisanpham lsp WHERE lsp.TENLOAISP lIKE 'phụ kiện điện thoại%'";
      $ketqua = mysqli_query($conn, $truyvancha);
      $chuoijson = array();

      echo "{";
      echo "\"DANHSACHPHUKIEN\":";
      if($ketqua){
            while ($dong=mysqli_fetch_array($ketqua)) {

              // Lấy danh sách phụ kiện con
              $truyvanphukien = "SELECT * FROM loaisanpham lsp, sanpham sp  WHERE lsp.MALOAI_CHA =".$dong["MALOAISP"]."  AND lsp.MALOAISP = sp.MALOAISP ORDER BY sp.LUOTMUA DESC LIMIT 10";

              $ketquacon = mysqli_query($conn, $truyvanphukien);

              if($ketquacon){
                while($dongphukiencon = mysqli_fetch_array($ketquacon)){

                       array_push($chuoijson, array("MASP"=>$dongphukiencon["MALOAISP"],'TENSP' => $dongphukiencon["TENLOAISP"],'GIATIEN' => $dongphukiencon['GIA'] ,'HINHSANPHAM' =>"http://".$_SERVER['SERVER_NAME'].":8080"."/webanata".$dongphukiencon["ANHLON"]));

                }

              }

          }

      }

          echo json_encode($chuoijson,JSON_UNESCAPED_UNICODE);

      echo "}";
    }

    function LayDanhSachTopPhuKien(){
      global $conn;
      // Truy vấn điện thoại
      $truyvancha = "SELECT * FROM loaisanpham lsp WHERE lsp.TENLOAISP lIKE 'phụ kiện điện thoại%'";
      $ketqua = mysqli_query($conn, $truyvancha);
      $chuoijson = array();

      echo "{";
      echo "\"TOPPHUKIEN\":";
      if($ketqua){
            while ($dong=mysqli_fetch_array($ketqua)) {

              $truyvan = "SELECT * FROM loaisanpham lsp, sanpham sp  WHERE lsp.MALOAI_CHA =".$dong["MALOAISP"]."  AND lsp.MALOAISP = sp.MALOAISP ORDER BY sp.LUOTMUA DESC LIMIT 10";

              $ketquacon = mysqli_query($conn, $truyvan);

              if($ketquacon){
                while($dongphukiencon = mysqli_fetch_array($ketquacon)){

                       array_push($chuoijson, array("MASP"=>$dongphukiencon["MASP"],'TENSP' => $dongphukiencon["TENSP"],'GIATIEN' => $dongphukiencon['GIA'] ,'HINHSANPHAM' =>"http://".$_SERVER['SERVER_NAME'].":8080"."/webanata".$dongphukiencon["ANHLON"]));

                }

              }

          }

      }

          echo json_encode($chuoijson,JSON_UNESCAPED_UNICODE);

      echo "}";
    }


      function LayDanhSachTopDienThoaiVaMayTinhBang(){
        global $conn;

        // Truy vấn điện thoại
        $truyvan = "SELECT * FROM loaisanpham lsp, sanpham sp  WHERE lsp.TENLOAISP like 'điện thoại%'  AND lsp.MALOAISP = sp.MALOAISP ORDER BY sp.LUOTMUA DESC LIMIT 10";
        $ketqua = mysqli_query($conn, $truyvan);
        $chuoijson = array();

        echo "{";
        echo "\"TOPDIENTHOAIVAMAYTINHBANG\":";
        if($ketqua){
              while ($dong=mysqli_fetch_array($ketqua)) {
                // cách 1
                // $chuoijson[] = $dong;
                // end cách 1
                // laydanhsachloaisp($dong["MALOAISP"]);

                //cách 2
                array_push($chuoijson, array("MASP"=>$dong["MASP"],'TENSP' => $dong["TENSP"],'GIATIEN' => $dong['GIA'] ,'HINHSANPHAM' =>"http://".$_SERVER['SERVER_NAME'].":8080"."/webanata".$dong["ANHLON"]));
                //end cách 2
              }

              //echo json_encode($chuoijson,JSON_UNESCAPED_UNICODE);
            }

        $truyvancha = "SELECT * FROM loaisanpham lsp, sanpham sp  WHERE lsp.TENLOAISP like 'máy tính bảng%'  AND lsp.MALOAISP = sp.MALOAISP ORDER BY sp.LUOTMUA DESC LIMIT 10";
        $ketquamtb = mysqli_query($conn, $truyvancha);

        if($ketquamtb){
              while ($dongmtb=mysqli_fetch_array($ketquamtb)) {

                //cách 2
                array_push($chuoijson, array("MASP"=>$dongmtb["MASP"],'TENSP' => $dongmtb["TENSP"],'GIATIEN' => $dongmtb['GIA'] ,'HINHSANPHAM' =>"http://".$_SERVER['SERVER_NAME'].":8080"."/webanata".$dongmtb["ANHLON"]));
                //end cách 2
              }

            }
     echo json_encode($chuoijson,JSON_UNESCAPED_UNICODE);

        echo "}";
      }


      function LayDanhSachCacThuongHieuLon(){
        global $conn;

        $truyvan = "SELECT * FROM thuonghieu th, chitietthuonghieu ctth WHERE th.MATHUONGHIEU  = ctth.MATHUONGHIEU ";
        $ketqua = mysqli_query($conn, $truyvan);
        $chuoijson = array();
        echo "{";
        echo "\"DANHSACHTHUONGHIEU\":";
        if($ketqua){
              while ($dong=mysqli_fetch_array($ketqua)) {
                // cách 1
                // $chuoijson[] = $dong;
                // end cách 1
                // laydanhsachloaisp($dong["MALOAISP"]);

                //cách 2
                array_push($chuoijson, array("MASP"=>$dong["MATHUONGHIEU"],'TENSP' => $dong["TENTHUONGHIEU"], 'HINHSANPHAM' =>"http://".$_SERVER['SERVER_NAME'].":8080"."/webanata".$dong["HINHLOAISPTH"]));
                //end cách 2
              }

              echo json_encode($chuoijson,JSON_UNESCAPED_UNICODE);
            }
            echo "}";
      }
      function KiemTraDangNhap(){
          global $conn;
          if(isset($_POST["tendangnhap"]) || isset($_POST["matkhau"])){
            $tendangnhap = $_POST["tendangnhap"];
            $matkhau = $_POST["matkhau"];
          }

          $truyvan = "SELECT * FROM nhanvien WHERE TENDANGNHAP='".$tendangnhap."' AND MATKHAU='".$matkhau."'";
          $ketqua = mysqli_query($conn,$truyvan);
          $demdong = mysqli_num_rows($ketqua);
          if($demdong >=1){
            $tennv = "";
            while ($dong = mysqli_fetch_array($ketqua)) {
              $tennv = $dong["TENNV"];
            }
            echo "{ ketqua : true, tennv : \"".$tennv."\" }";
          }else{
            echo "{ ketqua : false }";
          }

        }
  		function DangKyThanhVien(){
  			global $conn;
  			if(isset($_POST["tennv"]) || isset($_POST["tendangnhap"]) || isset($_POST["matkhau"]) || isset($_POST["maloainv"]) || isset($_POST["emaildocquyen"])){
  				$tennv = $_POST["tennv"];
  				$tendangnhap = $_POST["tendangnhap"];
  				$matkhau = $_POST["matkhau"];
  				$maloainv = $_POST["maloainv"];
  				$emaildocquyen = $_POST["emaildocquyen"];
  			}


  			$truyvan = "INSERT INTO nhanvien (TENNV,TENDANGNHAP,MATKHAU,MALOAINV,EMAILDOCQUYEN) VALUES ('".$tennv."','".$tendangnhap."','".$matkhau."','".$maloainv."','".$emaildocquyen."') ";

  			if(mysqli_query($conn,$truyvan)){
  				echo "{ ketqua : true }";
  			}else{
  				echo "{ ketqua : false }".mysqli_error($conn);
  			}

  			mysqli_close($conn);


  		}

  function LayDanhSachMenu(){
    global $conn;
    if(isset($_POST["maloaicha"])){
      $maloaicha = $_POST["maloaicha"];
    }
    $truyvan = "SELECT * FROM loaisanpham WHERE MALOAI_CHA = ".$maloaicha;
    $ketqua = mysqli_query($conn, $truyvan);
    $chuoijson = array();
    echo "{";
    echo "\"LOAISANPHAM\":";
    if($ketqua){
          while ($dong=mysqli_fetch_array($ketqua)) {
            // cách 1
            $chuoijson[] = $dong;
            // end cách 1
            // laydanhsachloaisp($dong["MALOAISP"]);

            //cách 2
            // array_push($chuoijson, array("TENLOAISP"=>$dong["TENLOAISP"],'MALOAISP' => $dong["MALOAISP"]));
            //end cách 2
          }

          echo json_encode($chuoijson,JSON_UNESCAPED_UNICODE);
        }
        echo "}";
        mysql_close($conn);
  }




?>
