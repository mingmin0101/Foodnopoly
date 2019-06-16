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
    <link rel="stylesheet" href="styles/checkbox.css">
    <link rel="stylesheet" href="styles/coupon.css">
    <!-- coupon 輪軸 -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/assets/owl.carousel.min.css'>
    <script src="http://cdn.bootcss.com/jquery/1.11.0/jquery.min.js" type="text/javascript"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script src='js/sortTable.js'></script>
    <script src='js/couponFilter.js'></script>

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
<!-- 修改會員資料css -->
<style>
    td{
        padding:4px 0px;
        height:45px;
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

<!-- coupon js -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js'></script>
<script type="text/javascript">
        $(function(){
            $('.mhn-slide').owlCarousel({
                nav:true,
                //loop:true,
                slideBy:'page',
                rewind:false,
                responsive:{
                    0:{items:1},
                    480:{items:2},
                    600:{items:3},
                    1000:{items:4},
                },
                smartSpeed:70,
                navText:['<svg viewBox="0 0 24 24"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"></path></svg>','<svg viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"></path></svg>']
            });
        });
</script>

<!-- coupon modal -->
<script>
// When the user clicks the button, open the modal 
function useCoupon(id){ 
    var model_id = "myModal" + id.toString();
    document.getElementById(model_id).style.display = "block";
}

// When the user clicks on <span> (x), close the modal
function closeModal(id) {
    var model_id = 'myModal'+ id.toString();
    document.getElementById(model_id).style.display = "none";
}

</script>
<style type="text/css">
 /* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  /*left: 0;*/
  /*top: 0;*/
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 10px 35px 20px 35px;
  border: 1px solid #888;
  width: 50%;

}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  text-align:right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
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
    </ul>

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
                        <td><input name="email_addr" class="form-control" value="<?php echo $_SESSION['email_addr']?>"></td>
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

            <!-- <?php echo $resultStr;?>    -->  
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
                echo '<tr><td>'.$restaurantRow['name'].'</td><td>'.$discount.'% off</td><td>'.$couponInfoRow['details'].'</td><td>'.$couponInfoRow['expiration date'].'</td><td><button class="btn btn-warning" onclick="useCoupon('.$couponRow['id'].')">兌換條碼</button></td></tr>';

                echo '<div id="myModal'.$couponRow['id'].'" class="modal">
                        <div class="modal-content"><span onclick=closeModal('.$couponRow['id'].') class="close">&times;</span><p>兌換條碼<br><br>
                        <img src='.$couponInfoRow['barcode'].' style="width:100%;"><br><button class="btn btn-warning" style="float:right;" onclick="changeCouponStatus('.$couponRow['id'].')">使 用</button><br>
                        </p></div></div>';

            } else if ((strtotime(date('Y-m-d H:i:s')) < strtotime($couponInfoRow['expiration date'])) && $couponRow['status'] != 'valid') {
                echo '<tr><td>'.$restaurantRow['name'].'</td><td>'.$discount.'% off</td><td>'.$couponInfoRow['details'].'</td><td>'.$couponInfoRow['expiration date'].'</td><td><button class="btn btn-secondary" disabled>已兌換</button></td></tr>';
            } else if (strtotime(date('Y-m-d H:i:s')) > strtotime($couponInfoRow['expiration date'])){
                echo '<tr><td>'.$restaurantRow['name'].'</td><td>'.$discount.'% off</td><td>'.$couponInfoRow['details'].'</td><td>'.$couponInfoRow['expiration date'].'</td><td><button class="btn btn-danger" disabled>已到期</button></td></tr>';
            };

        }  
        
    }

?>

<!-- ###############################3 -->
<script>
function changeCouponStatus(couponID){
    $
    $.post(
        "coupon_used.php",
        {
            id: couponID
        }
    );
    window.location.reload();
}
 
</script>
<!-- ############################### -->
                       
                    </tbody>
                    </table>
                </div>
            </div>


            
        </div>


        <!-- 兌換coupon頁面 -->
        <div class="tab-pane container fade" id="coupon">
            <br><br>
            
            <div class='row'>
                <div class="col-sm-12">
                    <div class="htmleaf-container">
                        <div class="container">
                            <div class="mhn-slide owl-carousel">
                <?php
                if (isset($_SESSION['account'])){   //有登入
                    $coupon = $dbh->query('SELECT * from coupon');  //撈出全部coupon

                    while($couponRow = $coupon->fetch(PDO::FETCH_ASSOC)){
                        $restaurant = $dbh->prepare('SELECT * FROM restaurant WHERE restaurant_id = ?');
                        $restaurant->execute(array($couponRow['restaurant_id']));
                        $restaurantRow = $restaurant->fetch(PDO::FETCH_ASSOC);
                        
                        if(strtotime(date('Y-m-d H:i:s')) < strtotime($couponRow['expiration date'])){  //檢查有效期限
                            echo '<div class="mhn-item filter_card '.$restaurantRow['category'].'"><div class="mhn-inner">
                            <div class="mhn-img" style="background-image: url(rest_pic/'.$restaurantRow['restaurant_id'].'.jpg)"></div>
                            <div class="mhn-text">
                            <div class="row">
                            <div class="col-sm-8"><h5 style="text-align: left;">'.$restaurantRow['name'].'</h5></div>
                            <div class="col-sm-4"><h4 style="color: #FFBE04;">'.$couponRow['discount'].'折</h4></div>
                            </div>
                            <div class="row"><div class="col-sm-12"><p>'.$couponRow['details'].'</p></div></div>
                            <div class="row"><div class="col-sm-12"><p>到期日:'.$couponRow['expiration date'].'</p></div></div>
                            <button class="coupon_button" onclick="redeemCoupon()">兌 換</button>
                            </div></div></div>';
                        }                       
                    }
                }
                ?>

                <script>
                function redeemCoupon() {
                  var txt;
                  if (confirm("你確定要兌換這張coupon麻!")) {
                    txt = "You pressed OK!";
                  } else {
                    txt = "You pressed Cancel!";
                  }
                  document.getElementById("demo").innerHTML = txt;
                }
                </script>
                <style type="text/css">
                    /*.filter_card{ display: none; }/* Hidden by default */*/
                    .show{ display: block;}/* The "show" class is added to the filtered elements */
                </style>
                            </div>
                        </div>
                    </div>
                </div>
                <p id="demo"></p>


    
           

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