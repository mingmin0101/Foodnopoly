<?php
session_start();
include("pdoInc.php");
?>
<?php
//沒有登入不執行後面程式碼並重導至member.php
if(!isset($_SESSION['account'])){ die('<meta http-equiv=REFRESH CONTENT=0;url=member.php)>'); }

//修改會員資料
$resultStr = '';
if(isset($_POST['realname']) && isset($_POST['nickname']) && isset($_POST['password']) && isset($_POST['email_addr'])){
    $sth = $dbh->prepare('SELECT account FROM member WHERE account = ? and password = ?');   //檢查密碼對不對
    $sth->execute(array($_SESSION['account'], hash('sha256',$_POST['password'])));
    if($sth->rowCount() == 1){  //帳號密碼比對正確，剛好只有撈取到一筆資料
        if($_POST['newpwd1'] != '' && $_POST['newpwd2'] != ''){  //密碼、確認密碼輸入欄位皆不是空的
            if($_POST['newpwd1'] == $_POST['newpwd2']){   //密碼、確認密碼兩個輸入值相同
                $sth2 =  $dbh->prepare('UPDATE member SET realname = ?, nickname = ?, password = ?, email_addr = ? WHERE account = ?');  //更新資料 UPDATE
                $sth2->execute(array($_POST['realname'], $_POST['nickname'],  hash('sha256',$_POST['newpwd1']), $_POST['email_addr'], $_SESSION['account']));
                $resultStr = '修改暱稱或密碼成功';
                $_SESSION['nickname'] = $_POST['nickname'];
                $_SESSION['realname'] = $_POST['realname'];
                $_SESSION['email_addr'] = $_POST['email_addr'];
            }
            else {
                $resultStr = '兩次新密碼填寫不同';
            }
        }
        else {  //沒有要修改密碼
            $sth2 =  $dbh->prepare('UPDATE member SET realname = ?, nickname = ?, email_addr = ? WHERE account = ?');
            $sth2->execute(array($_POST['realname'], $_POST['nickname'], $_POST['email_addr'], $_SESSION['account']));
            $_SESSION['realname'] = $_POST['realname'];
            $_SESSION['nickname'] = $_POST['nickname'];
            $_SESSION['email_addr'] = $_POST['email_addr'];

            $resultStr = '修改名稱或暱稱成功';
        }
    }
    else {
        $resultStr = '密碼填寫錯誤';
    }
}
?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="styles/coupon.css"> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script src='js/sortTable.js'></script>
<!-- pills -->
<style type="text/css">
.nav-pills .nav-link.active, .nav-pills .show>.nav-link {
    color: white;
    background-color: #FFBE04;
}
a {
    color: black;
    text-decoration: none;
    background-color: transparent;
}
a:hover {
    color: #FFBE04;
    text-decoration: none;
}
</style>
<!-- point record css -->
<style type="text/css">
    .count { width: 20%; text-align: right;}
    .record { width: 60%;}
    .date { width: 20%; text-align: left;}
</style>
<!-- my coupon filter -->
<script>
$(document).ready(function(){
  $("#myCouponInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myCouponTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
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
                    //會員登出
                    echo '<li class="nav-item"><a href="php_sess_logout.php"><img src="pic/logout.png" style="height:32px; margin:0px 5px;" onmouseover="this.src=\'pic/logout_hover.png\'" onmouseleave="this.src=\'pic/logout.png\'" onclick="<?php echo \'<meta http-equiv=REFRESH CONTENT=0;url=index.php>\';?>"></a></li>';
                } 
            ?>
        </ul>
    </nav>

<br><br><br><br>

<div class="container">
    <!-- Nav pills -->
    <ul class="nav nav-pills nav-justified">
      <li class="nav-item" >
        <a class="nav-link active" data-toggle="pill" href="#modifyInfo"><b>會員資料</b></a>
      </li>
      <li class="nav-item" >
        <a class="nav-link" data-toggle="pill" href="#commentRecord"><b>評論紀錄</b></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="pill" href="#myPoint"><b>我的food points</b></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="pill" href="#myCoupon"><b>我的coupon</b></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="pill" href="#coupon"><b>兌換coupon</b></a>
      </li>
<!--       <li class="nav-item">
        <a class="nav-link disabled" href="#"></a>
      </li> -->
    </ul>

    <style type="text/css">
    .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
        color: white;
        background-color: #FFBE04;
    }
    .nav-pills a {
        color: black;
        text-decoration: none;
        background-color: transparent;
    }
    .nav-pills a:hover {
        color: #FFBE04;
        text-decoration: none;
    }
    </style>

    <style>
        td{
            padding:4px 0px;
            height:45px;
        }
    </style>

    <!-- Tab panes -->
    <div class="tab-content">
        <!-- 修改會員資料頁面 -->
        <div class="tab-pane container active" id="modifyInfo">
            <br>
            <form action="<?php echo basename($_SERVER['PHP_SELF']);?>" method="POST">  
                <table>
                    <tr>
                        <td>帳號：</td>
                        <td><?php echo $_SESSION['account'];?></td>
                    </tr>
                    <tr>
                        <td>累積點數：</td>
                        <td><?php echo $_SESSION['point'];?></td>
                    </tr>
                    <tr>
                        <td>真實姓名：</td>
                        <td><input name="realname" class="form-control" value="<?php echo $_SESSION['realname']?>"></td>
                    </tr>
                    <tr>
                        <td>暱稱：</td>
                        <td><input name="nickname" class="form-control" value="<?php echo $_SESSION['nickname']?>"></td>
                    </tr>
                    <tr>
                        <td>電子郵件：</td>
                        <td><input name="email" class="form-control" value="<?php echo $_SESSION['email_addr']?>"></td>
                    </tr>
                    <tr>
                        <td>密碼：</td>
                        <td><input name="password" class="form-control" placeholder="必填"></td>
                    </tr>
                    <tr>
                        <td>修改密碼：</td>
                        <td><input name="newpwd1" class="form-control" placeholder="僅修改密碼時需填"></td>
                    </tr>
                    <tr>
                        <td>確認密碼：</td>
                        <td><input name="newpwd2" class="form-control" placeholder="僅修改密碼時需填"></td>
                    </tr>
                </table>
                <br>
                <input type="submit" class="btn btn-warning" value="修改">                
            </form>

            <?php echo $resultStr;?>     
        </div>

        <!-- 我的coupon頁面 -->
        <div class="tab-pane container fade" id="myCoupon">
            <br>
            <div class="container mt-3">
                <input class="form-control" id="myCouponInput" type="text" placeholder="搜尋" style="background-color: rgb(255,255,255,0.4);background-image: url(pic/search.png);background-position: 8px 7px; background-repeat: no-repeat; padding: 6px 12px 6px 40px;">   <!-- filter -->
                <div id="myCouponDIV" class="mt-3">  <!-- 把要搜尋的東西放在這塊div -->
                    <table class="table table-hover" id='myTable'>
                    <thead>
                        <tr>
                            <th onclick="sortTable(0)">餐廳名稱</th>
                            <th onclick="sortTable(1)">折扣</th>
                            <th onclick="sortTable(2)">詳細說明</th>
                            <th onclick="sortTable(3)">到期日</th>
                            <th onclick="sortTable(4)">使用狀態</th>
                        </tr>
                    </thead>
                    <tbody id="myCouponTable">
<?php
    if (isset($_SESSION['account'])){   //有登入
        $member = $dbh->prepare('SELECT id from member WHERE account = ?');  //找出目前登入的會員
        $member->execute(array($_SESSION['account']));
        $memberID = $member->fetch(PDO::FETCH_ASSOC);

        $coupon = $dbh->prepare('SELECT * from couponrecord WHERE member_id = ?');  
        $coupon->execute(array($memberID['id']));

        while ($couponRow = $coupon->fetch(PDO::FETCH_ASSOC)) {
            $couponInfo = $dbh->prepare('SELECT * from coupon WHERE id = ?');  //找出coupon資料
            $couponInfo->execute(array($couponRow["coupon_id"]));
            $couponInfoRow = $couponInfo->fetch(PDO::FETCH_ASSOC);
            
            $restaurant = $dbh->prepare('SELECT * from restaurant WHERE restaurant_id = ?');  //找出coupon的餐廳
            $restaurant->execute(array($couponInfoRow["restaurant_id"]));
            $restaurantRow = $restaurant->fetch(PDO::FETCH_ASSOC);

            $discount = 100 - $couponInfoRow['discount'];

            if((strtotime(date('Y-m-d H:i:s')) < strtotime($couponInfoRow['expiration date'])) && $couponRow['status'] == 'valid'){  //檢查是否超過到期日 && 有沒有被兌換過
                echo '<tr><td>'.$restaurantRow['name'].'</td><td>'.$discount.'% off</td><td>'.$couponInfoRow['details'].'</td><td>'.$couponInfoRow['expiration date'].'</td><td><button class="btn btn-warning">兌換條碼</button></td></tr>';
            } else if ((strtotime(date('Y-m-d H:i:s')) < strtotime($couponInfoRow['expiration date'])) && $couponRow['status'] != 'valid') {
                echo '<tr><td>'.$restaurantRow['name'].'</td><td>'.$discount.'% off</td><td>'.$couponInfoRow['details'].'</td><td>'.$couponInfoRow['expiration date'].'</td><td><button class="btn btn-secondary" disabled>已兌換</button></td></tr>';
            } else if (strtotime(date('Y-m-d H:i:s')) > strtotime($couponInfoRow['expiration date'])){
                echo '<tr><td>'.$restaurantRow['name'].'</td><td>'.$discount.'% off</td><td>'.$couponInfoRow['details'].'</td><td>'.$couponInfoRow['expiration date'].'</td><td><button class="btn btn-danger" disabled>已到期</button></td></tr>';


            };

        }  
        
    }

?>
                        
                    </tbody>
                    </table>
                </div>
            </div>


            
        </div>


        <!-- 兌換coupon頁面 -->
        <!-- https://www.w3schools.com/howto/howto_js_filter_elements.asp -->
        <!-- https://www.w3schools.com/howto/howto_css_product_card.asp -->
        <div class="tab-pane container fade" id="coupon">

<!-- checkbox -->
<script type="text/javascript">
// Listen for click on toggle checkbox
$('#select-all').click(function(event) {   
    if(this.checked) {
        // Iterate each checkbox
        $('input[type="checkbox"]').each(function() {
            this.checked = true;                        
        });
    } else {
        $(':checkbox').each(function() {
            this.checked = false;                       
        });
    }
});
</script>
            <br>
            <div class='row'>
                <div class="col-sm-2" style="padding: 0px 15px 0px 0px">
                    <label class="container">One
                      <input type="checkbox" checked="checked">
                      <span class="checkmark"></span>
                    </label>
                    <label class="container">Two
                      <input type="checkbox">
                      <span class="checkmark"></span>
                    </label>
                    <label class="container">Three
                      <input type="checkbox">
                      <span class="checkmark"></span>
                    </label>
                    <label class="container">Four
                      <input type="checkbox">
                      <span class="checkmark"></span>
                    </label>
                    <label class="container">all
                      <input type="checkbox" id='select-all'>
                      <span class="checkmark"></span>
                    </label>

                </div>
                <div class='col-sm-5'>
                    <div class="card">
                      <div class="card-body">六折優惠券</div> 
                    </div>
                    <br>
                    <div class="card">
                      <div class="card-body">Content</div> 
                    </div>
                </div>
                <div class='col-sm-5'>
                    <div class="card">
                      <div class="card-body">Content</div> 
                    </div>
                    <br>
                    <div class="card">
                      <div class="card-body">Content</div> 
                    </div>
                </div>
            </div>
        </div>

        <!-- 評論紀錄頁面，要串聯杰修負責的資料表 -->
        <div class="tab-pane container fade" id="commentRecord"><br>
            <div class="container" style="background-color: rgb(255,255,255,0.4); width: 100%;">
                <br>
                <div class="row" style="padding: 5px 120px;text-align: center;">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-2" style="font-size: 18px; text-align: right; margin-top: 6px;"><b>總共評論篇數</b></div>
                    <div class="col-sm-2" style="font-size: 24px; color: #FFBE04;"><b>10</b></div>
                    <div class="col-sm-4"></div>
                </div><br>
            </div><br>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="count">餐廳</th>
                        <th class="record">內容</th>
                        <th class="date">評論時間</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="count">這間餐廳</td>
                        <td class="record">還不錯</td>
                        <td class="date">2020/02/02</td>
                    </tr>

                </tbody>
            </table>



        </div>
        

        <!-- 我的food points頁面，需另創pointRecord資料表 -->
        <div class="tab-pane container fade" id="myPoint"><br>
            <div class="container" style="background-color: rgb(255,255,255,0.4); width: 100%;">
                <br>
                <div class="row" style="padding: 5px 120px;text-align: center;">
                    <div class="col-sm-3"><b>總共累積Food Points</b></div>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-4"><b>總共使用Food Points</b></div>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-3"><b>剩餘可用Food Points</b></div>
                </div>
<?php
    if (isset($_SESSION['account'])){   //有登入
        $member = $dbh->prepare('SELECT id from member WHERE account = ?');  //找出目前登入的會員
        $member->execute(array($_SESSION['account']));
        $memberID = $member->fetch(PDO::FETCH_ASSOC);

        $point1 = $dbh->prepare('SELECT sum(point_in) AS sumIN, sum(point_out) AS sumOUT, sum(point_in)-sum(point_out) AS sumTotal from pointrecord WHERE member_id = ? ORDER BY id');   
        $point1->execute(array($memberID['id']));
        $pointRow1= $point1->fetch(PDO::FETCH_ASSOC);
        echo '<div class="row" style="padding: 5px 120px;text-align: center;">
                <div class="col-sm-3" style="font-size: 25px; color: #40424C;"><b>'.$pointRow1['sumIN'].'</b></div>
                <div class="col-sm-1"><b>-</b></div>
                <div class="col-sm-4" style="font-size: 25px; color: #8793A5;"><b>'.$pointRow1['sumOUT'].'</b></div>
                <div class="col-sm-1"><b>=</b></div>
                <div class="col-sm-3" style="font-size: 25px; color: #FFBE04;"><b>'.$pointRow1['sumTotal'].'</b></div></div><br></div><br>';   
        echo '<table class="table table-hover"><thead>
                    <tr>
                        <th class="count">點數計算</th>
                        <th class="record">點數兌換/使用紀錄</th>
                        <th class="date">日期</th>
                    </tr>
                  </thead>
                  <tbody>';
        
        $point2 = $dbh->prepare('SELECT * from pointrecord WHERE member_id = ? ORDER BY id');   
        $point2->execute(array($memberID['id']));
        while ($pointRow= $point2->fetch(PDO::FETCH_ASSOC)){
            if($pointRow['point_in']!=0 && $pointRow['point_out']==0){  //增加點數
                echo '<tr><td class="count" style="color:#FFBE04;">+'.$pointRow['point_in'].'</td><td class="record">'.$pointRow['record'].'</td><td class="date">'.$pointRow['time'].'</td></tr>';
            } else if($pointRow['point_in']==0 && $pointRow['point_out']!=0){  //減少點數
                echo '<tr><td class="count" style="color:#8793A5;">-'.$pointRow['point_out'].'</td><td class="record">'.$pointRow['record'].'</td><td class="date">'.$pointRow['time'].'</td></tr>';
            }  
            
        } 
        echo '</tbody></table>';
    }
?>
                  
                
            
                

        


        </div>
    </div>

</div>



</body></html>