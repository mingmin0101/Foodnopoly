<?php
session_start();
include("pdoInc.php");     //PDO
include('reply.php');
$rid = $_GET['id'];
?>

<?php
$whiteList = array('image/jpeg','image/png');

if(isset($_SESSION['account']) && isset($_POST['content']) && isset($_POST['rate']) && isset($_FILES['image']) && $_FILES['image']['name'] != NULL){
    if(in_array($_FILES["image"]["type"], $whiteList)){
        $rid = $_GET['id'];
        $mid = $_SESSION['member_id'];
        $replier = $_SESSION['nickname'];
        $content = nl2br(addslashes($_POST['content']));
        // $imgContent = 0;
        $rate = $_POST['rate'];

        // file upload
        $msg= "";
        $image = $_FILES['image']['name'];
        $hash_fileneme = hash('sha256', basename($image).strtotime(date('Y-m-d H:i:s')));  //hash(名稱+時間)
        $target = "test/".$hash_fileneme.".jpg";
        
        $insert = mysqli_query($con, "INSERT INTO reply (`restaurant_id`, `member_id`, `replier`, `content`, `img_content`, `rate`, `date_posted`) 
                                    VALUES ('".$rid."', '".$mid."', '".$replier."', '".$content."', '".$hash_fileneme.".jpg', '".$rate."', NOW());");
        
        move_uploaded_file($_FILES['image']['tmp_name'], $target); 

        // 找餐廳
        $restaurant = $dbh->prepare('SELECT * from restaurant WHERE restaurant_id = ?');  
        $restaurant->execute(array($rid));
        $restaurantRow = $restaurant->fetch(PDO::FETCH_ASSOC);     

        // pointrecord insert 一筆新紀錄 
        $newpointrecord = $dbh->prepare('INSERT INTO pointrecord (member_id, point_in, record) VALUES (?, ?, ?)');
        $record = "發表評論 (".$restaurantRow['name'].", ".$content.")";
        $newpointrecord->execute(array($_SESSION['member_id'], 5, $record));

        // 找出該會員的point紀錄
        $member = $dbh->prepare('SELECT SUM(point_in) AS total_point_in, SUM(point_out) AS total_point_out from pointrecord WHERE member_id = ?');
        $member->execute(array($_SESSION['member_id']));
        $memberRow = $member->fetch(PDO::FETCH_ASSOC);

        // update 該會員的 total point
        $total_point = $memberRow['total_point_in'] - $memberRow['total_point_out'];
        $memberUpdatePoint = $dbh->prepare('UPDATE member SET point = '.$total_point.' WHERE id = ?');
        $memberUpdatePoint->execute(array($_SESSION['member_id']));  
    }
    else{
        echo '<script>alert("圖片上傳格式有誤，只能上傳.jpeg or .png")</script>';
    }
}
else if(isset($_SESSION['account']) && isset($_POST['content']) && isset($_POST['rate'])){
        $rid = $_GET['id'];
        $mid = $_SESSION['member_id'];
        $replier = $_SESSION['nickname'];
        $content = nl2br(addslashes($_POST['content']));
        // $imgContent = 0;
        $rate = $_POST['rate'];

        $insert = mysqli_query($con, "INSERT INTO reply (`restaurant_id`, `member_id`, `replier`, `content`, `img_content`, `rate`, `date_posted`) 
                                    VALUES ('".$rid."', '".$mid."', '".$replier."', '".$content."', 0, '".$rate."', NOW());");
        
        //找餐廳
        $restaurant = $dbh->prepare('SELECT * from restaurant WHERE restaurant_id = ?');  
        $restaurant->execute(array($rid));
        $restaurantRow = $restaurant->fetch(PDO::FETCH_ASSOC);

        // pointrecord insert 一筆新紀錄 
        $newpointrecord = $dbh->prepare('INSERT INTO pointrecord (member_id, point_in, record) VALUES (?, ?, ?)');
        $record = "發表評論 (".$restaurantRow['name'].", ".$content.")";
        $newpointrecord->execute(array($_SESSION['member_id'], 5, $record));

        // 找出該會員的point紀錄
        $member = $dbh->prepare('SELECT SUM(point_in) AS total_point_in, SUM(point_out) AS total_point_out from pointrecord WHERE member_id = ?');  
        $member->execute(array($_SESSION['member_id']));
        $memberRow = $member->fetch(PDO::FETCH_ASSOC);

        // update 該會員的 total point
        $total_point = $memberRow['total_point_in'] - $memberRow['total_point_out'];
        $memberUpdatePoint = $dbh->prepare('UPDATE member SET point = '.$total_point.' WHERE id = ?');
        $memberUpdatePoint->execute(array($_SESSION['member_id']));

}
?>

<html>
<head>
    <title>大Food翁</title>
    <meta charset="utf-8">
    <!-- bootstrap -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="styles/jxiu.css">   jxiu 需要的css -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    
    <!-- Font Awesome Icon Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- css, js -->
    <link rel="stylesheet" type="text/css" href="styles/chatroom.css">
    <link rel="stylesheet" type="text/css" href="styles/index.css">
    <script src="js/chatroom.js"></script>


    <style>
    
/* maggie rate style----------------------------------------------- */
    .fa {
      font-size: 25px;
    }
    .checked {
      color: orange;
    }
    /* Three column layout */
    .side {
      float: left;
      width: 15%;
      margin-top:10px;
    }
    .middle {
      margin-top:10px;
      float: left;
      width: 70%;
    }

    /* Place text to the right */
    .right {
      text-align: right;
    }
    /* Clear floats after the columns */
    .rowrate:after {
      content: "";
      display: table;
      clear: both;
    }
    /* The bar container */
    .bar-container {
      width: 100%;
      background-color: #f1f1f1;
      text-align: center;
      color: white;
    }
/* maggie rate style----------------------------------------------- */

    </style>





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
          <a class="nav-link" href="random_select.php">餐廳推薦</a>
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

<br><br>
<!-- jxiupart start -->
<div class="container">
    <div class="row">
        <div class="col-sm-6" id="restaurantImg" style="vertical-align: middle;">
            <?php
                echo '<img src="rest_pic/'.$rid.'.jpg" style="width: 80%; box-shadow: 10px 10px 5px #aaaaaa; margin-top:0px;">';
            ?>
        </div>

        <div class="col-sm-6" id="restaurantInfo" style="vertical-align: middle;">
            <br>
            <?php

                $select = mysqli_query($con, "SELECT *
                                            FROM restaurant
                                            WHERE restaurant.restaurant_id = $rid");
                while($row = mysqli_fetch_assoc($select)){
                    echo "<h3><b>".$row['name']."</b></h3></br>";
                    // echo "<p> 評價：".$row['grade']."</p>";
                    echo "<p> 類型：".$row['category']."</p>";
                    echo "<p> 地址：".$row['address']."</p>";
                    echo "<p> 電話：0".$row['phone']."</p>";
                    echo "<p> 營業時間：".$row['open_hour']."</p>";
                };
            ?>
        </div>


    </div>
    <br><br>
    <hr>
    <div class="row" id="reply">
        <div style="clear: both;"><br/><br/><br/><br/></div>
        <!--以下之後為用php改寫-->
        <div class="col-sm-8" id="reply_form">

            <?php
                // print topic according to rid
                replyTopic($rid);
                // print content according to rid
                replyContent($rid);

                if(isset($_SESSION['account'])){
                    echo "<br><form action='restaurant.php?id=".$_GET['id']."' method='POST' enctype='multipart/form-data'>

                            <div class='form-group'>
                                <h5>留下你的評論吧！</h5>
                            </div>
                            <div class='form-group'>
                                <label for='rate'>評價：</label>
                                <select id='rate' name='rate' class='form-control'>
                                    <option value='1'>1分</option>
                                    <option value='2'>2分</option>
                                    <option value='3' selected>3分</option>
                                    <option value='4'>4分</option>
                                    <option value='5'>5分</option>
                                </select>
                            </div>
                            <div class='form-group'>
                                <label for='content'></label>
                                <textarea class='form-control' id='content' name='content' row='4' placeholder='說點什麼'></textarea>
                            </div>
                            <div class='form-group'>
                                <input class='form-control-file' type='file' name='image' accept='image/png, image/jpeg'/>
                            </div>
                            <div class='form-group'>
                                <button class='btn btn-warning' type='submit' value='留言'>留言</button> 
                            </div>
                        </form>";
                 }
                else{
                    echo "<p>要先登入才能留言喔！ 或是 <a href='member.php'> 按這裡 </a> 註冊！ </p>";
                }
            ?>
            </div>
            <div class="col-sm-4">
                <br>
                        <!-- maggie rate====================================================================== -->

            <?php
                $selectt = mysqli_query($con, "SELECT *
                                            FROM reply
                                            WHERE reply.restaurant_id = $rid");
                $k=0;
                $rateOne=0;
                $rateTwo=0;
                $rateThree=0;
                $rateFour=0;
                $rateFive=0;
                while($roww = mysqli_fetch_assoc($selectt)){
                    $rates[$k] = $roww['rate'];
                    if($rates[$k] == 1){$rateOne+=1;};
                    if($rates[$k] == 2){$rateTwo+=1;};
                    if($rates[$k] == 3){$rateThree+=1;};
                    if($rates[$k] == 4){$rateFour+=1;};
                    if($rates[$k] == 5){$rateFive+=1;};
                    $k++;
                };

                if(!isset($rates)){

                  echo "<span class='heading'><b>評 價</b></span>";
                  for($i=0 ; $i<5 ; $i++ ){
                      echo"  <span class='fa fa-star'></span>";
                  }
                  echo"<p>目前尚未有人評價</p>";

                }else{
                $totalRater= count($rates);
                $averageRate= array_sum($rates)/$totalRater;

                $restUpdateRate = $dbh->prepare('UPDATE restaurant SET grade = '.round($averageRate,1).' WHERE restaurant_id = ?');
                $restUpdateRate->execute(array($rid));

                // print_r($rates); //印出array測試看看

                    echo "<span class='heading'><b>評 價</b></span>";
                    if($averageRate==0){
                      for($j=0 ; $j<5 ; $j++ ){
                          echo " <span class='fa fa-star'></span>";
                      }
                    }else{
                        for($i=0 ; $i<round($averageRate) ; $i++ ){
                            echo" <span class='fa fa-star checked'></span>";
                            if($i==round($averageRate)-1){
                              for($j=0 ; $j<5-round($averageRate) ; $j++ ){
                                  echo " <span class='fa fa-star'></span>";
                              }
                            }
                        }
                    }
                   $averageRateRound = round($averageRate,1);
                    echo"<p>總平均評價 $averageRateRound 星級 - 根據 $totalRater 個用戶</p>";
                // $rateFive/$totalRater
                $bar5=  number_format($rateFive/$totalRater*100, 2).'%';
                $bar4=  number_format($rateFour/$totalRater*100, 2).'%';
                $bar3=  number_format($rateThree/$totalRater*100, 2).'%';
                $bar2=  number_format($rateTwo/$totalRater*100, 2).'%';
                $bar1=  number_format($rateOne/$totalRater*100, 2).'%';
                    echo "
                            <div class='side'>
                            <div>5 星</div>
                            </div>
                            <div class='middle'>
                            <div class='bar-container'>
                                <div style='width: $bar5; height: 18px; background-color: #4CAF50;'></div>
                            </div>
                            </div>
                            <div class='side right'>
                    ";
                // .bar-5 {width: 60%; height: 18px; background-color: #4CAF50;}
                    echo "  <div>$rateFive</div> ";
                    echo "
                        </div>
                            <div class='side'>
                            <div>4 星</div>
                            </div>
                            <div class='middle'>
                            <div class='bar-container'>
                                <div style='width: $bar4; height: 18px; background-color: #2196F3;'></div>
                            </div>
                            </div>
                            <div class='side right'>
                    ";
                    echo "  <div>$rateFour</div> ";
                    echo "
                            </div>
                            <div class='side'>
                                <div>3 星</div>
                            </div>
                            <div class='middle'>
                                <div class='bar-container'>
                                <div style='width: $bar3; height: 18px; background-color: #00bcd4;'></div>

                                </div>
                            </div>
                            <div class='side right'>
                    ";
                    echo "  <div>$rateThree</div>";
                    echo "
                            </div>
                            <div class='side'>
                            <div>2 星</div>
                            </div>
                            <div class='middle'>
                            <div class='bar-container'>
                                <div style='width: $bar2; height: 18px; background-color: #ff9800;'></div>
                            </div>
                            </div>
                            <div class='side right'>
                    ";
                    echo "  <div>$rateTwo</div>";
                    echo "
                            </div>
                            <div class='side'>
                            <div>1 星</div>
                            </div>
                            <div class='middle'>
                            <div class='bar-container'>
                                <div style='width: $bar1; height: 18px; background-color: #f44336;'></div>
                            </div>
                            </div>
                            <div class='side right'>
                    ";
                    echo "<div>$rateOne</div>";
                    echo "
                            </div>
                    ";
             }
            ?>

<!-- maggie rate============================================================================= -->
       
            </div>
        
    </div>
</div>

<!--jxiupart end-->



<div id='chatroom' class="container" style="position: fixed; right:30px; bottom: 100px; height:400px; width: 400px; display: none; z-index:10;">
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
