<?php
session_start();
include("pdoInc.php");     //PDO
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--
https://leafletjs.com/examples/quick-start/
https://leafletjs.com/reference-1.4.0.html#map-example
-->
<html>
<head>
    <title>Map Page</title>
    <meta charset="utf-8">
    <!-- bootstrap -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <!-- Font Awesome Icon Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- leaflet map api -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css"
      integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
      crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"
      integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg=="
      crossorigin=""></script>
    <style type="text/css">
       #mapid { height: 180px; }
    </style>

    <!-- bootstrap 版面的css -->
    <style>
    /* Add a gray background color and some padding to the footer */
    footer {
      background-color: #f2f2f2;
      padding: 25px;
    }

    .carousel-inner img {
      width: 100%; /* Set width to 100% */
      min-height: 200px;
    }

    /* Hide the carousel text when the screen is less than 600 pixels wide */
    @media (max-width: 600px) {
      .carousel-caption {
        display: none;
      }
    }
    /* maggie rate style----------------------------------------------- */
        .fa {
          font-size: 25px;
        }
        .checked {
          color: orange;
        }

    /* maggie rate style----------------------------------------------- */

    </style>

    <!-- 聊天室相關 -->
    <style>
        table,tr,td{width:100%; font-family: 微軟正黑體; font-size: 15px;}
        table{height:100%}
        #showMsgHere{width:100%;height:200px;font-size:15px;resize:none;}
    </style>
    <script>
    function sendMsg(){
        $.post(
            "ajax_chatroom_insert.php",
            {
                nickname: $("#nickname").val(),
                msg: $("#msg").val()
            }
        );
        $("#msg").val("");
    }

    function showMsg(t){
        $.post(
            "ajax_chatroom_disp.php",
            {
                'getNewMsgOnly': t
            }
        ).done(function(data){
            $("#showMsgHere").append(data);
            setTimeout("showMsg(1);", 1000);
        });
    }

    $(function(){
        // 網頁載入，抓取全部訊息
        showMsg(0);
        // 按下 enter 後送出訊息
        $("#msg").bind("keydown",
            function(e){
                if(e.which == 13){
                    sendMsg();
                }
            }
        )
    })
    </script>



</head>
<body style="font-family: 微軟正黑體; background-image: url(pic/restaurant_background.png)">


<!-- https://www.w3schools.com/bootstrap4/tryit.asp?filename=trybs_navbar_color&stacked=h -->
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
        <!-- Brand/logo -->
        <a class="navbar-brand" href="index.php">大Food翁</a>
        <!-- Links -->
        <ul class="nav navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="index.php">店家總覽</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="random_select.php?restaurant_type=隨機">餐廳推薦</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto" >
            <?php
                if(isset($_SESSION['account']) && $_SESSION['account'] != null){
                    //修改會員資料
                    echo '<a href="member_info.php"><img src="pic/profile.png" style="height:32px; margin:0px 5px;" onmouseover="this.src=\'pic/profile_hover.png\'" onmouseleave="this.src=\'pic/profile.png\'"></a>';
                    //會員登出
                    echo '<li class="nav-item"><a href="php_sess_logout.php"><img src="pic/logout.png" style="height:32px; margin:0px 5px;" onmouseover="this.src=\'pic/logout_hover.png\'" onmouseleave="this.src=\'pic/logout.png\'" onclick="<?php echo \'<meta http-equiv=REFRESH CONTENT=0;url=index.php>\';?>"></a></li>';
                }
                else{
                    echo '<li class="nav-item"><a href="member.php"><img src="pic/login.png" style="height:32px;" onmouseover="this.src=\'pic/login_hover.png\'" onmouseleave="this.src=\'pic/login.png\'"></a></li>';
                }
            ?>
        </ul>
    </nav>


    <div class="container-fluid">

    <div id='mapView' class="row">
      <div class="col-sm-8" style="padding: 5px">
        <!-- map -->
        <br><br><br>
        <div id="mapid" style="height: 500px; width: 100%;"></div>
      </div>

      <div class="col-sm-4" style="padding: 5px">
        <br><br><br>


<!-- 下面是map======================================================================= -->
    <script type="text/javascript">
        // https://noob.tw/openstreetmap/
        //利用leaflet初始化地圖
        var map = L.map('mapid').setView([24.987567, 121.576072], 25);  //setView(經緯度中心點，zoom大小)

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

  </script>
  <!-- https://ithelp.ithome.com.tw/articles/10208985 寫php傳值給javascript參考-->
  <?php
        // 用php從資料庫撈取餐廳資料
        // $ridd = $_GET['category'];
        // echo $ridd;
        // $result1 = mysqli_query($con,  "SELECT *
        //                             FROM restaurant WHERE restaurant.category =  ");
        //                   // WHERE restaurant.category = '$ridd'
       $k=0;
        if(isset($_GET['category'])){
            $restaurant = $dbh->prepare('SELECT * from restaurant WHERE category = ?');
            $restaurant->execute(array($_GET['category']));
            while ($restaurantRow= $restaurant->fetch(PDO::FETCH_ASSOC)){
                // echo $restaurantRow['name'];
                $name[$k] = $restaurantRow['name'];
                $category[$k] = $restaurantRow['category'];
                $longitude[$k] = $restaurantRow['longitude'];
                $latitude[$k] = $restaurantRow['latitude'];
                $address[$k] = $restaurantRow['address'];
                $grade[$k] = $restaurantRow['grade'];
                $open_hour[$k] = $restaurantRow['open_hour'];
                $id[$k] = $restaurantRow['restaurant_id'];
                $k++;
            }
          }

        // mysqli_fetch_row
        // while($row1 = mysqli_fetch_assoc($result1))
        // {
        //   //   echo $ridd;
        //   // echo "<h3>".$row1['name']."</h3></br>";
        //   $name[$k] = $row1[1];
        //   $category[$k] = $row1[2];
        //   $longitude[$k] = $row1[3];
        //   $latitude[$k] = $row1[4];
        //   $address[$k] = $row1[5];
        //   $grade[$k] = $row1[6];
        //   $open_hour[$k] = $row1[8];
        //   $k++;
        // };
        // // print_r($name); //印出array測試看看
  ?>

  <script>
        // 抓到的資料，轉成javascript用於map
       var rest_name= <?php echo json_encode($name); ?>;
       var category = <?php echo json_encode($category); ?>;
       var longitude = <?php echo json_encode($longitude); ?>;
       var latitude = <?php echo json_encode($latitude); ?>;
       var address = <?php echo json_encode($address); ?>;
       var grade = <?php echo json_encode($grade); ?>;
       var open_hour = <?php echo json_encode($open_hour); ?>;
       var id = <?php echo json_encode($id); ?>;

       var redIcon = new L.Icon({
       	iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
       	shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
       	iconSize: [25, 41],
       	iconAnchor: [12, 41],
       	popupAnchor: [1, -34],
       	shadowSize: [41, 41]
       });

       var functionName = new Array(rest_name.length);
       for(var i=0; i<rest_name.length ;i++){
        L.marker([longitude[i], latitude[i]], {icon: redIcon})
            // .on('click',function() { alert('Clicked on a member of '+i); })
            .bindPopup('<a href=#restaurant'+id[i]+'><strong>'+rest_name[i]+'</strong></a><br>'+ category[i]+'<br>'+address[i]+'<br>'+open_hour[i])
            .openPopup()
            .addTo(map);
        }

    </script>

<!-- 下面是右邊餐廳簡介======================================================================= -->

<div id='restaurantInto'  style="height: 500px; width: 100%; overflow-y:scroll;">
<h3 align=center style="background-color:#ffbe02; color:#FFFFFF">政大美食地圖</h3>
<div class="overflow-hidden">
</div>

<!-- 右邊餐廳簡介用script寫======================================================================= -->
<!--
<script>
  // 由 for 迴圈來產生右邊餐廳簡介
  for (i=0; i<rest_name.length; i++) {
  	document.write("<a href=><font color=#0A5D90 size=5px>" + rest_name[i] + "</font></a><br>");
    // document.write("<p style=background-color:#0A5D90; color:#FFFFFF;>政大美食地圖</p>");
    document.write("<mark><img src=pic/star.png></img><font color=red size=3px background=pink>" + grade[i]+ "</font></mark><br>");
    document.write("<font size=3px>今日營業: " + open_hour[i] + "</font><br>");
    document.write("<font size=3px>" + address[i] + "</font><br><hr>");
  }
</script> -->

<!-- 右邊餐廳簡介用php寫======================================================================= -->
<?php
      // $sth = $dbh->query('SELECT * from restaurant ORDER BY restaurant_id');

      if(isset($_GET['category'])){
          $sth = $dbh->prepare('SELECT * from restaurant WHERE category = ?');
          $sth->execute(array($_GET['category']));

          while($row = $sth->fetch(PDO::FETCH_ASSOC)){

              echo '<div class="row" id="restaurant'.$row['restaurant_id'].'">><div class="col-sm-6"><a href="restaurant.php?id='.$row['restaurant_id'].'" ><p style="color: #ffbe02; font-size: 23px; margin-bottom:0">'.$row['name'].'</p></a>';
               // maggie rate================================================================================
                      $rate = $row['grade'];
                      echo "<span>星級:</span>";
                      // echo "$row['grade']";
                      if($rate==0){
                        for($j=0 ; $j<5 ; $j++ ){
                            echo " <span class='fa fa-star'></span>";
                        }
                      }else{
                          for($i=0 ; $i<round($rate) ; $i++ ){
                              echo" <span class='fa fa-star checked'></span>";
                              if($i==round($rate)-1){
                                for($j=0 ; $j<5-round($rate) ; $j++ ){
                                    echo " <span class='fa fa-star'></span>";
                                }
                              }
                          }
                      }
             // maggie rate=================================================================================
              echo '<p style="margin-bottom:0">今日營業: '.$row['open_hour'].'</p>';
              echo '<p style="margin-bottom:0">'.$row['address'].'</p></div>';
              echo '<div class="col-sm-1"><img src="rest_pic/'.$row['restaurant_id'].'.jpg" width="180px" height="130px"></img></div></div>';
              echo '<hr>';
          }
        }

?>

    <!-- <div>
    <h3 >餐廳</h3>
    <p>這裡是餐廳資料</p>
    </div>
    <hr> -->
</div>
</div>
</div>
</div>

<!-- 聊天視窗=================================================================================== -->
<!-- <img id='chat_icon' src='pic/chat_icon.png' style="height: 60px; width: 60px; position: fixed; bottom: 30px; right: 35px;" onclick="this.style.display='none';getElementById('close_icon').style.display='block';getElementById('chatroom').style.display='block';" >
<img id='close_icon' src='pic/close.png' style="height: 70px; width: 70px; position: fixed; bottom: 25px; right: 30px; display: none;" onclick="this.style.display='none';getElementById('chat_icon').style.display='block';getElementById('chatroom').style.display='none';" > -->

<div id='chatroom' class="container" style="position: fixed; right:30px; bottom: 100px; height:400px; width: 400px; display: none;">
    <div class="card">
        <div class="card-header bg-dark text-white">聊天室</div>
        <div class="card-body">
            <table>
                <tr><td><textarea id="showMsgHere" disabled="disabled"></textarea></td></tr>
                <tr><td>
                    <form>
                        <input type="text" id="msg" placeholder="訊息" style="width:100%;height:2em">
                        <input type="button" value="送出" onclick="sendMsg();">
                    </form>
                </td></tr>
            </table>
        </div>
    </div>
</div>


</body>
</html>
