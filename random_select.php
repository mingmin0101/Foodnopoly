<?php
session_start();
include("pdoInc.php");     //PDO
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="spin_game/css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="spin_game/css/htmleaf-demo.css">
    <link rel="stylesheet" type="text/css" href="spin_game/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <title>大Food翁 - 機會 命運</title>
    <!-- 轉盤範例  http://www.htmleaf.com/html5/html5-canvas/201611174201.html 
                  http://www.htmleaf.com/Demo/201611174202.html -->
    <style type="text/css">
        .button {
          /*background-color: #4CAF50; /* Green */*/
          border: none;
          color: white;
          padding: 20px 10px;
          text-align: center;
          text-decoration: none;
          font-size: 22px;
          margin: 10px 10px;
          -webkit-transition-duration: 0.4s; /* Safari */
          transition-duration: 0.4s;
          cursor: pointer;
          border-radius: 8px;
          width: 124px;
        }
        .button3 {
          background-color: transparent; 
          color: black; 
          border: 2px solid #FFBE04;
        }

        .button3:hover {
          background-color: #FFBE04;
          color: white;
        }

        .buttonActive {
          background-color: #FFBE04;
          color: white;
        }

    </style>
</head>
<body style="font-family: 微軟正黑體; background-image: url(pic/restaurant_background.png)">

<!-- https://www.w3schools.com/bootstrap4/tryit.asp?filename=trybs_navbar_color&stacked=h -->
<nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
    <!-- Brand/logo -->
    <a class="navbar-brand" href="index.php">大Food翁</a>
    <!-- Links -->
    <ul class="nav navbar-nav">
        <li class="nav-item"><a class="nav-link" href="index.php">店家總覽</a></li>
        <li class="nav-item"><a class="nav-link" href="random_select.php?restaurant_type=隨機">餐廳推薦</a></li> 
        
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

<br>
<div class='row'>
    <div class="col-sm-1"></div>
    <div class='col-sm-3'><br><br>
        <form action="<?php echo basename($_SERVER['PHP_SELF']);?>" method="GET">
        <button class="button button3 <?php if(isset($_GET['restaurant_type'])&& $_GET['restaurant_type']=='隨機'){echo 'buttonActive';} ?>" name='restaurant_type' value='隨機'><b>隨 機</b></button>
        <button class="button button3 <?php if(isset($_GET['restaurant_type'])&& $_GET['restaurant_type']=='日本料理'){echo 'buttonActive';} ?>" name='restaurant_type' value='日本料理'><b>日 式</b></button><br>
        <button class="button button3 <?php if(isset($_GET['restaurant_type'])&& $_GET['restaurant_type']=='中式料理'){echo 'buttonActive';} ?>" name='restaurant_type' value='中式料理'><b>中 式</b></button>
        <button class="button button3 <?php if(isset($_GET['restaurant_type'])&& $_GET['restaurant_type']=='韓式料理'){echo 'buttonActive';} ?>" name='restaurant_type' value='韓式料理'><b>韓 式</b></button><br>
        <button class="button button3 <?php if(isset($_GET['restaurant_type'])&& $_GET['restaurant_type']=='東南亞料理'){echo 'buttonActive';} ?>" name='restaurant_type' value='東南亞料理'><b>南 洋</b></button>
        <button class="button button3 <?php if(isset($_GET['restaurant_type'])&& $_GET['restaurant_type']=='義式料理'){echo 'buttonActive';} ?>" name='restaurant_type' value='義式料理'><b>義 式</b></button><br>
        <button class="button button3 <?php if(isset($_GET['restaurant_type'])&& $_GET['restaurant_type']=='美式料理'){echo 'buttonActive';} ?>" name='restaurant_type' value='美式料理'><b>美 式</b></button>
        <button class="button button3 <?php if(isset($_GET['restaurant_type'])&& $_GET['restaurant_type']=='早午餐店'){echo 'buttonActive';} ?>" name='restaurant_type' value='早午餐店'><b>早午餐</b></button><br> 
        <button class="button button3 <?php if(isset($_GET['restaurant_type'])&& $_GET['restaurant_type']=='飲料店'){echo 'buttonActive';} ?>" name='restaurant_type' value='飲料店'><b>飲 品</b></button>
        <button class="button button3 <?php if(isset($_GET['restaurant_type'])&& $_GET['restaurant_type']=='下午茶'){echo 'buttonActive';} ?>" name='restaurant_type' value='下午茶'><b>下午茶</b></button><br> 
        </form>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.buttonClassA').click(function(){
               $(this).removeClass('buttonClassA').addClass('buttonClassB');
            });
        });
    </script>

    <div class='col-sm-7'>
        <div class="htmleaf-container" >
            <br><br>
        <div class="htmleaf-content" style="width: 500px; height: 500px; margin: 10px 70px;">
            <div class="banner">
                <div class="turnplate" style="background-image:url(spin_game/images/turnplate-bg.png);background-size:100% 100%;">
                    <canvas class="item" id="wheelcanvas" width="422px" height="422px"></canvas>
                    <img class="pointer" src="spin_game/images/turnplate-pointer3.png"/>
                </div>
            </div>
        </div>
        </div>
    </div>
    <div class="col-sm-1"></div>

</div>

    <script src="http://cdn.bootcss.com/jquery/1.11.0/jquery.min.js" type="text/javascript"></script>
    <script>window.jQuery || document.write('<script src="spin_game/js/jquery-1.11.0.min.js"><\/script>')</script>
    <script type="text/javascript" src="spin_game/js/awardRotate.js"></script>
    <script type="text/javascript">
    var turnplate={
            restaraunts:[],             //大轉盤獎品名稱
            colors:[],                  //大轉盤獎品區塊對應背景顏色
            outsideRadius:192,          //大轉盤外圓的半徑  192
            textRadius:155,             //大轉盤獎品位置距離圓心的距離 155
            insideRadius:68,            //大轉盤內圓的半徑
            startAngle:0,               //開始角度
            bRotate:false               //false:停止;ture:旋轉
    };


  

    $(document).ready(function(){
        //動態添加大轉盤的獎品與獎品區域的背景顏色
        // turnplate.restaraunts = ["波波恰恰", "高句麗", "小曼谷", "古早味蛋餅", "金鮨日式料理", "龍角咖啡", "左撇子", "四川飯館", "米塔義式廚房", "小公寓"];
        <?php
            if(isset($_GET['restaurant_type'])&& $_GET['restaurant_type']!='隨機'){
                $restaurant = $dbh->prepare('SELECT * from restaurant WHERE category = ? ORDER BY RAND() LIMIT 10'); //ORDER BY RAND() 隨機順序
                $restaurant->execute(array($_GET['restaurant_type']));  
                echo 'turnplate.restaraunts = [';
                while ($restaurantRow= $restaurant->fetch(PDO::FETCH_ASSOC)){
                    echo '"'.$restaurantRow['name'].'", ';
                } 
                echo '];';
            } else if (isset($_GET['restaurant_type'])&& $_GET['restaurant_type']=='隨機'){
                $restaurant = $dbh->query('SELECT * from restaurant ORDER BY RAND() LIMIT 10');  //ORDER BY RAND() 隨機順序
                echo 'turnplate.restaraunts = [';
                while ($restaurantRow= $restaurant->fetch(PDO::FETCH_ASSOC)){
                    echo '"'.$restaurantRow['name'].'", ';
                } 
                echo '];';

            }
        ?>
        turnplate.colors = ["#FFF4D6", "#FFFFFF", "#FFF4D6", "#FFFFFF","#FFF4D6", "#FFFFFF", "#FFF4D6", "#FFFFFF","#FFF4D6", "#FFFFFF"];

        
        var rotateTimeOut = function (){
            $('#wheelcanvas').rotate({
                angle:0,
                animateTo:2160,
                duration:8000,
                callback:function (){
                    alert('網路不好!請檢察你的網路連線狀態!');
                }
            });
        };

        //旋轉轉盤 item:獎品位置; txt：提示;
        var rotateFn = function (item, txt){
            var angles = item * (360 / turnplate.restaraunts.length) - (360 / (turnplate.restaraunts.length*2));
            if(angles<270){
                angles = 270 - angles; 
            }else{
                angles = 360 - angles + 270;
            }
            $('#wheelcanvas').stopRotate();
            $('#wheelcanvas').rotate({
                angle:0,
                animateTo:angles+1800,
                duration:8000,
                callback:function (){
                    // alert(txt);
                    turnplate.bRotate = !turnplate.bRotate;
                }
            });
        };

        $('.pointer').click(function (){
            if(turnplate.bRotate)return;
            turnplate.bRotate = !turnplate.bRotate;
            //获取随机数(奖品个数范围内)
            var item = rnd(1,turnplate.restaraunts.length);
            //奖品数量等于10,指针落在对应奖品区域的中心角度[252, 216, 180, 144, 108, 72, 36, 360, 324, 288]
            rotateFn(item, turnplate.restaraunts[item-1]);
            /* switch (item) {
                case 1:
                    rotateFn(252, turnplate.restaraunts[0]);
                    break;
                case 2:
                    rotateFn(216, turnplate.restaraunts[1]);
                    break;
                case 3:
                    rotateFn(180, turnplate.restaraunts[2]);
                    break;
                case 4:
                    rotateFn(144, turnplate.restaraunts[3]);
                    break;
                case 5:
                    rotateFn(108, turnplate.restaraunts[4]);
                    break;
                case 6:
                    rotateFn(72, turnplate.restaraunts[5]);
                    break;
                case 7:
                    rotateFn(36, turnplate.restaraunts[6]);
                    break;
                case 8:
                    rotateFn(360, turnplate.restaraunts[7]);
                    break;
                case 9:
                    rotateFn(324, turnplate.restaraunts[8]);
                    break;
                case 10:
                    rotateFn(288, turnplate.restaraunts[9]);
                    break;
            } */
            console.log(item);
        });
    });

    function rnd(n, m){
        var random = Math.floor(Math.random()*(m-n+1)+n);
        return random;
        
    }


    //页面所有元素加载完毕后执行drawRouletteWheel()方法对转盘进行渲染
    window.onload=function(){
        drawRouletteWheel();
    };

    function drawRouletteWheel() {    
      var canvas = document.getElementById("wheelcanvas");    
      if (canvas.getContext) {
          //根据奖品个数计算圆周角度
          var arc = Math.PI / (turnplate.restaraunts.length/2);
          var ctx = canvas.getContext("2d");
          //在给定矩形内清空一个矩形
          ctx.clearRect(0,0,422,422);
          //strokeStyle 属性设置或返回用于笔触的颜色、渐变或模式  
          ctx.strokeStyle = "#FFBE04";
          //font 属性设置或返回画布上文本内容的当前字体属性
          ctx.font = '16px Microsoft YaHei';      
          for(var i = 0; i < turnplate.restaraunts.length; i++) {       
              var angle = turnplate.startAngle + i * arc;
              ctx.fillStyle = turnplate.colors[i];
              ctx.beginPath();
              //arc(x,y,r,起始角,结束角,绘制方向) 方法创建弧/曲线（用于创建圆或部分圆）    
              ctx.arc(211, 211, turnplate.outsideRadius, angle, angle + arc, false);    
              ctx.arc(211, 211, turnplate.insideRadius, angle + arc, angle, true);
              ctx.stroke();  
              ctx.fill();
              //锁画布(为了保存之前的画布状态)
              ctx.save();   
              
              //----绘制奖品开始----
              ctx.fillStyle = "#E5302F";
              var text = turnplate.restaraunts[i];
              var line_height = 17;
              //translate方法重新映射画布上的 (0,0) 位置
              ctx.translate(211 + Math.cos(angle + arc / 2) * turnplate.textRadius, 211 + Math.sin(angle + arc / 2) * turnplate.textRadius);
              
              //rotate方法旋转当前的绘图
              ctx.rotate(angle + arc / 2 + Math.PI / 2);
              
              /** 下面代码根据奖品类型、奖品名称长度渲染不同效果，如字体、颜色、图片效果。(具体根据实际情况改变) **/
              if(text.indexOf("M")>0){//流量包
                  var texts = text.split("M");
                  for(var j = 0; j<texts.length; j++){
                      ctx.font = j == 0?'bold 20px Microsoft YaHei':'16px Microsoft YaHei';
                      if(j == 0){
                          ctx.fillText(texts[j]+"M", -ctx.measureText(texts[j]+"M").width / 2, j * line_height);
                      }else{
                          ctx.fillText(texts[j], -ctx.measureText(texts[j]).width / 2, j * line_height);
                      }
                  }
              }else if(text.indexOf("M") == -1 && text.length>6){//奖品名称长度超过一定范围 
                  text = text.substring(0,6)+"||"+text.substring(6);
                  var texts = text.split("||");
                  for(var j = 0; j<texts.length; j++){
                      ctx.fillText(texts[j], -ctx.measureText(texts[j]).width / 2, j * line_height);
                  }
              }else{
                  //在画布上绘制填色的文本。文本的默认颜色是黑色
                  //measureText()方法返回包含一个对象，该对象包含以像素计的指定字体宽度
                  ctx.fillText(text, -ctx.measureText(text).width / 2, 0);
              }
              
              //把当前画布返回（调整）到上一个save()状态之前 
              ctx.restore();
              //----绘制奖品结束----
          }     
      } 
    }

    </script>
</body>
</html>