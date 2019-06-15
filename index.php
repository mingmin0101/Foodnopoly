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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- css -->
    <link rel="stylesheet" type="text/css" href="styles/chatroom.css">
    <link rel="stylesheet" type="text/css" href="styles/index.css">
    <!-- js -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="js/chatroom.js"></script>

</head>
<body style="font-family: 微軟正黑體;">

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


    <div class="container">
    <div  id='restaurantList' class="container-fluid bg-3 text-center">  
    <br><br><br>  
      <div class="row" style="padding:7.5px;">
        <div class="col-sm-4" style="padding: 0px;  overflow: hidden;">
            <a href="map.php">
                <div class='imageHover' style="width:100%; margin: 7px; background-color: black;">
                <img src="pic\food_category\japanese2.jpg" class="img-responsive" style="height: 40%;" alt="Image">
                <div class="centered"><h1><b>日 式</b></h1></div>
                </div> 
            </a>
        </div>
        <div class="col-sm-4" style="padding: 0px;  overflow: hidden;"> 
            <a href="map.php">
                <div class='imageHover' style="width:100%; margin: 7px; background-color: black;">
                <img src="pic\food_category\korean.jpg" class="img-responsive" style="height: 40%;" alt="Image">
                <div class="centered"><h1><b>韓 式</b></h1></div>
                </div> 
            </a>
        </div>
        <div class="col-sm-4" style="padding: 0px;  overflow: hidden;"> 
            <a href="map.php">
                <div class='imageHover' style="width:100%; margin: 7px; background-color: black;">
                <img src="pic\food_category\italy.jpg" class="img-responsive" style="height: 40%;" alt="Image">
                <div class="centered"><h1><b>義 式</b></h1></div>
                </div> 
            </a>
        </div>
      </div>
      <div  class="row" style="padding:7.5px;">
        <div class="col-sm-4" style="padding: 0px;overflow: hidden;"> 
            <a href="map.php">
                <div class='imageHover' style="width:100%;  margin: 7px; background-color: black;">
                <img src="pic\food_category\tw.jpg" class="img-responsive" style="height: 40%;" alt="Image">
                <div class="centered"><h1><b>中 式</b></h1></div>
                </div> 
            </a>
        </div>
        <div class="col-sm-4" style="padding: 0px;overflow: hidden;"> 
            <a href="map.php">
                <div class='imageHover' style="width:100%;  margin: 7px; background-color: black;">
                <img src="pic\food_category\usa.jpg" class="img-responsive" style="height: 40%;" alt="Image">
                <div class="centered"><h1><b>美 式</b></h1></div>
                </div> 
            </a>
        </div>
        <div class="col-sm-4" style="padding: 0px;  overflow: hidden;"> 
            <a href="map.php">
                <div class='imageHover' style="width:100%; margin: 7px;background-color: black;">
                <img src="pic\food_category\southeast.jpg" class="img-responsive" style="height: 40%;" alt="Image">
                <div class="centered"><h1><b>南 洋</b></h1></div>
                </div> 
            </a>
        </div>
      </div>
      <div class="row" style="padding:7.5px;">
        <div class="col-sm-4" style="padding: 0px;  overflow: hidden;">
            <a href="map.php">
                <div class='imageHover' style="width:100%; margin: 7px;background-color: black;">
                <img src="pic\food_category\brunch.jpg" class="img-responsive" style="height: 40%;" alt="Image">
                <div class="centered"><h1><b>早 午 餐</b></h1></div>
                </div> 
            </a>
        </div>
        <div class="col-sm-4" style="padding: 0px;  overflow: hidden;"> 
            <a href="map.php">
                <div class='imageHover' style="width:100%; margin: 7px;background-color: black;">
                <img src="pic\food_category\dessert.jpg" class="img-responsive" style="height: 40%;" alt="Image">
                <div class="centered"><h1><b>下 午 茶</b></h1></div>
                </div> 
            </a>
        </div>
        <div class="col-sm-4" style="padding: 0px;  overflow: hidden;"> 
            <a href="map.php">
                <div class='imageHover' style="width:100%; margin: 7px;background-color: black;">
                <img src="pic\food_category\drink.jpg" class="img-responsive" style="height: 40%;" alt="Image">
                <div class="centered"><h1><b>飲 品</b></h1></div>
                </div> 
            </a>
        </div>
      </div>

          
    </div><br>

<!-- 聊天視窗 -->
<?php
    if(isset($_SESSION['account']) && $_SESSION['account'] != null){
        echo '<img id="chat_icon" src="pic/chat_icon.png" style="height: 80px; width: 80px; position: fixed; bottom: 30px; right: 35px;z-index:10;" onclick="this.style.display=\'none\';getElementById(\'close_icon\').style.display=\'block\';getElementById(\'chatroom\').style.display=\'block\';" >
            <img id="close_icon" src="pic/close.png" style="height: 90px; width: 90px; position: fixed; bottom: 25px; right: 30px; display: none;z-index:10;" onclick="this.style.display=\'none\';getElementById(\'chat_icon\').style.display=\'block\';getElementById(\'chatroom\').style.display=\'none\';" >';
    }
?>

<div id='chatroom' class="container" style="position: fixed; right:30px; bottom: 130px; height:350px; width: 450px; display: none; z-index:10;">
    <div class="card">
        <div class="card-header bg-dark text-white">聊天室</div>
        <div class="card-body" style="padding: 10px;">
            <table>
                <tr><td><textarea id="showMsgHere" class="form-control" disabled="disabled"></textarea></td></tr>
                <tr><td>
                    <form style="margin:0px;">
                        <div class='row'>
                            <div class="col-sm-10" style="padding: 0px 0px 0px 15px"><input type="text" id="msg" class="form-control" placeholder="訊息" style="width:100%;height:2em"></div>
                            <div class="col-sm-2" style="padding: 0px 15px 0px 0px; "><input type="button" class="btn btn-dark" style='padding:3px 10px;' value="送出" onclick="sendMsg();"></div>
                        </div>                        
                    </form>
                </td></tr>
            </table>
        </div> 
    </div>
</div>


</body>
</html>

