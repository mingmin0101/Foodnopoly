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
    <!-- icon -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
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
<body style="font-family: 微軟正黑體;">


<!-- https://www.w3schools.com/bootstrap4/tryit.asp?filename=trybs_navbar_color&stacked=h -->
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
        <!-- Brand/logo -->
        <a class="navbar-brand" href="index.php">大Food翁</a>
        <!-- Links -->
        <!-- <ul class="nav navbar-nav">
            <li class="nav-item active">
              <a class="nav-link" href="#mapView">地圖檢視</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#restaurantList">店家總覽</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#recommendation">隨機推薦</a>
            </li>

        </ul> -->
        <ul class="navbar-nav ml-auto" >
            <?php
                if(isset($_SESSION['account']) && $_SESSION['account'] != null){
                    //修改會員資料
                    // echo '<a href="modify_member_info.php"><img src="hw6_pic/profile.png" style="position: fixed; right: 140px; top:95px; height:32px;" onmouseover="this.src=\'hw6_pic/profile_hover.png\'" onmouseleave="this.src=\'hw6_pic/profile.png\'"></a>';
                    //會員登出
                    echo '<li class="nav-item"><a href="php_sess_logout.php"><img src="pic/logout.png" style="height:32px;" onmouseover="this.src=\'pic/logout_hover.png\'" onmouseleave="this.src=\'pic/logout.png\'" onclick="<?php echo \'<meta http-equiv=REFRESH CONTENT=0;url=index.php>\';?>"></a></li>';
                }
                else{
                    echo '<li class="nav-item"><a href="member.php"><img src="pic/login.png" style="height:32px;" onmouseover="this.src=\'pic/login_hover.png\'" onmouseleave="this.src=\'pic/login.png\'"></a></li>';
                }
            ?>
            <!-- <li class="nav-item">
              <a class="nav-link" href="member.php"><img src="pic/login.png" style="height:32px;" onmouseover="this.src='pic/login_hover.png'" onmouseleave="this.src='pic/login.png'"></a>
            </li>   -->
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
        $sql1 = "SELECT * FROM restaurant";
        $result1 = mysqli_query($con,$sql1);
        $token = array();
        $k=0;
        while($row1 = mysqli_fetch_row($result1))
        {
          $name[$k] = $row1[1];
          $category[$k] = $row1[2];
          $longitude[$k] = $row1[3];
          $latitude[$k] = $row1[4];
          $address[$k] = $row1[5];
          $grade[$k] = $row1[6];
          $open_hour[$k] = $row1[8];
          $k++;
        }
        // print_r($name); //印出array測試看看
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

       for(var i=0; i<rest_name.length ;i++){
        L.marker([longitude[i], latitude[i]]).addTo(map)
            .bindPopup('<strong>'+rest_name[i]+'</strong><br>'+ category[i]+'<br>'+address[i]+'<br>'+open_hour[i])
            .openPopup()
            // .on('click', onClick);
        }

        function onClick(e){
            alert(this.getLatLng());
            document.getElementBiId('intro').value = '餐廳簡介在這裡';
        }

    </script>

<!-- 下面是右邊餐廳簡介======================================================================= -->

<div id='restaurantInto'  style="height: 500px; width: 100%; overflow-y:scroll;">
<h3 align=center style="background-color:#0A5D90; color:#FFFFFF">政大美食地圖</h3>
<div class="overflow-hidden">
</div>
<script>
  // 由 for 迴圈來產生右邊餐廳簡介
  for (i=0; i<rest_name.length; i++) {
  	document.write("<a href=><font color=#0A5D90 size=5px>" + rest_name[i] + "</font></a><br>");
    // document.write("<p style=background-color:#0A5D90; color:#FFFFFF;>政大美食地圖</p>");
    document.write("<mark><img src=pic/star.png></img><font color=red size=3px background=pink>" + grade[i]+ "</font></mark><br>");
    document.write("<font size=3px>今日營業: " + open_hour[i] + "</font><br>");
    document.write("<font size=3px>" + address[i] + "</font><br><hr>");
  }
</script>
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
                        <!-- <input type="text" id="nickname" placeholder="暱稱" style="width:5em;height:2em"> -->
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
