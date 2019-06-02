<?php
session_start();
include("pdoInc.php");     //PDO
?>

<html>
<head>
    <title>大Food翁</title>
    <meta charset="utf-8">
    <!-- bootstrap -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

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
        <a class="navbar-brand" href="#">大Food翁</a>
        <!-- Links -->
        <ul class="nav navbar-nav">
            <!-- <li class="nav-item active">
              <a class="nav-link" href="#mapView">地圖檢視</a>
            </li> -->
            <li class="nav-item">
              <a class="nav-link" href="#restaurantList">店家總覽</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">餐廳推薦</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">線上訂位</a>
            </li>
            
        </ul>
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


    <div class="container">

    <div  id='restaurantList' class="container-fluid bg-3 text-center">  
    <br><br><br>  
      <div class="row">
        <div class="col-sm-6 col-md-4">

            <a href="map.php"><img src="https://lh6.ggpht.com/jM60CHqgXtyITf0yTeHZjGiwp2wqGGVGu_ttV6HY-M6JnGveWE9cqorqPXSkNsgxqKI69u_NNoGyCYvkKKtY-OuLDpudYA=s600" class="img-responsive" style="width:100%; margin: 15px 8px; " alt="Image"></a>
        </div>
        <div class="col-sm-6 col-md-4"> 
          <img src="https://lh3.googleusercontent.com/BTRH2tzsJEUvdoTnd5Q8hJEzTZzYvRL2gYLJBSnpZyBJ-ay-YzJXjfJQ9zOCN5SR6u4OxWCaEjE8N6M0W_HtaFOtVRka9Yw=s1200" class="img-responsive" style="width:100%; margin: 15px 8px;" alt="Image">
        </div>
        <div class="col-sm-6 col-md-4"> 
          <img src="https://lh3.googleusercontent.com/BTRH2tzsJEUvdoTnd5Q8hJEzTZzYvRL2gYLJBSnpZyBJ-ay-YzJXjfJQ9zOCN5SR6u4OxWCaEjE8N6M0W_HtaFOtVRka9Yw=s1200" class="img-responsive" style="width:100%; margin: 15px 8px;" alt="Image">
        </div>
      </div>

      <div class="row">
        <div class="col-sm-6 col-md-4">
          <img src="https://lh3.ggpht.com/M-_2LLsrg50_CVNq4_92TVESb67Iy09LXKciLQjqQtIUvybrMlTDERTSUDstj2dIpiVZOoDPxalgRNc8Oq7Q9tKwpGqhETc=s600" class="img-responsive" style="width:100%; margin: 15px 8px;" alt="Image">
        </div>
        <div class="col-sm-6 col-md-4"> 
          <img src="https://lh3.ggpht.com/M-_2LLsrg50_CVNq4_92TVESb67Iy09LXKciLQjqQtIUvybrMlTDERTSUDstj2dIpiVZOoDPxalgRNc8Oq7Q9tKwpGqhETc=s600" class="img-responsive" style="width:100%; margin: 15px 8px;" alt="Image">
        </div>
        <div class="col-sm-6 col-md-4"> 
          <img src="https://lh3.ggpht.com/M-_2LLsrg50_CVNq4_92TVESb67Iy09LXKciLQjqQtIUvybrMlTDERTSUDstj2dIpiVZOoDPxalgRNc8Oq7Q9tKwpGqhETc=s600" class="img-responsive" style="width:100%; margin: 15px 8px;" alt="Image">
        </div>
      </div>
    </div><br>


    <!-- <div id='recommendation' class="container text-center">    
      <h3>今天吃甚麼! (推薦店家 或者做隨機抽籤的功能)</h3>
      <br>
      <div class="row">
        <div class="col-sm-3">
          <img src="https://placehold.it/150x80?text=IMAGE" class="img-responsive" style="width:100%" alt="Image">
          <p>Current Project</p>
        </div>
        <div class="col-sm-3"> 
          <img src="https://placehold.it/150x80?text=IMAGE" class="img-responsive" style="width:100%" alt="Image">
          <p>Project 2</p>    
        </div>
        <div class="col-sm-3">
          <div class="well">
           <p>Some text..</p>
          </div>
          <div class="well">
           <p>Some text..</p>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="well">
           <p>Some text..</p>
          </div>
          <div class="well">
           <p>Some text..</p>
          </div>
        </div>  
      </div>
      <hr>
    </div> -->


<!--     <footer class="container-fluid text-center">
      <p>製作團隊:</p>
    </footer>
 -->

<!-- 聊天視窗 -->
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

