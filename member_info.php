<?php
session_start();
include("pdoInc.php");
?>
<?php
if(!isset($_SESSION['account'])){  // $_SESSION['account'])類似身分代號的樣子，沒有登入過就導向login頁面
    die('<meta http-equiv=REFRESH CONTENT=0;url=member.php)>');   //die直接不跑後面的東西
}

//修改會員資料
$resultStr = '';
if(isset($_POST['realname']) && isset($_POST['nickname']) && isset($_POST['password'])){
    $sth = $dbh->prepare('SELECT account FROM member WHERE account = ? and password = ?');   //檢查密碼對不對
    $sth->execute(array($_SESSION['account'], hash('sha256',$_POST['password'])));
    if($sth->rowCount() == 1){  //帳號密碼比對正確，剛好只有撈取到一筆資料
        if($_POST['newpwd1'] != '' && $_POST['newpwd2'] != ''){  //密碼、確認密碼輸入欄位皆不是空的
            if($_POST['newpwd1'] == $_POST['newpwd2']){   //密碼、確認密碼兩個輸入值相同
                $sth2 =  $dbh->prepare('UPDATE member SET realname = ?, nickname = ?, password = ? WHERE account = ?');  //更新資料 UPDATE
                $sth2->execute(array($_POST['realname'], $_POST['nickname'],  hash('sha256',$_POST['newpwd1']), $_SESSION['account']));
                $resultStr = '修改暱稱或密碼成功';
                $_SESSION['nickname'] = $_POST['nickname'];
                $_SESSION['realname'] = $_POST['realname'];
            }
            else {
                $resultStr = '兩次新密碼填寫不同';
            }
        }
        else {  //沒有要修改密碼
            $sth2 =  $dbh->prepare('UPDATE member SET realname = ?, nickname = ? WHERE account = ?');
            $sth2->execute(array($_POST['realname'], $_POST['nickname'], $_SESSION['account']));
            $_SESSION['realname'] = $_POST['realname'];
            $_SESSION['nickname'] = $_POST['nickname'];

            $resultStr = '修改名稱或暱稱成功';
        }
    }
    else {
        $resultStr = '密碼填寫錯誤';
    }
}

//管理員可以修改會員權限
if(isset($_SESSION['account'])){  //有登入狀態
     if(isset($_POST['user_admin_'.$_SESSION['account']]) && $_POST['user_admin_'.$_SESSION['account']] != null ){  //自己有登入代表自己一定在會員清單內
        $sth3 = $dbh->query('SELECT * from member');
        while($row = $sth3->fetch(PDO::FETCH_ASSOC)){
            if(isset($_POST['user_admin_'.$row['account']]) && $_POST['user_admin_'.$row['account']] != null ){                                
                if($_POST['user_admin_'.$row['account']] != $row['is_admin']){ //如果有修改權限設定 
                $sth2 = $dbh->prepare('UPDATE member SET is_admin = ? WHERE account = ?');  //更新資料 UPDATE
                $sth2->execute(array($_POST['user_admin_'.$row['account']], $row['account']));
                }   
            }  
        }
    }
}

?>

 
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    

</head>
<body style="font-family: 微軟正黑體; ">
<!-- https://www.w3schools.com/bootstrap4/tryit.asp?filename=trybs_navbar_color&stacked=h -->
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
        <!-- Brand/logo -->
        <a class="navbar-brand" href="index.php">大Food翁</a>
        <!-- Links -->
        <ul class="nav navbar-nav">
            <!-- <li class="nav-item active">
              <a class="nav-link" href="#mapView">地圖檢視</a>
            </li> -->
            <li class="nav-item">
              <a class="nav-link" href="#">會員資料</a>
            </li>
            
            <!-- <li class="nav-item">
              <a class="nav-link" href="#">兌換coupon</a>
            </li> -->
            
        </ul>
        <ul class="navbar-nav ml-auto" >
            <?php
                if(isset($_SESSION['account']) && $_SESSION['account'] != null){ 
                    //修改會員資料
                    // echo '<a href="member_info.php"><img src="pic/profile.png" style="height:32px; margin:0px 5px;" onmouseover="this.src=\'pic/profile_hover.png\'" onmouseleave="this.src=\'pic/profile.png\'"></a>';
                    //會員登出
                    echo '<li class="nav-item"><a href="php_sess_logout.php"><img src="pic/logout.png" style="height:32px; margin:0px 5px;" onmouseover="this.src=\'pic/logout_hover.png\'" onmouseleave="this.src=\'pic/logout.png\'" onclick="<?php echo \'<meta http-equiv=REFRESH CONTENT=0;url=index.php>\';?>"></a></li>';
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

<br><br><br>

<div class="container">
 <br>
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#modify">修改會員資料</a>
    </li>
    <?php 
        if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != null && $_SESSION['is_admin'] == 1){  //如果是管理員就可以"任免新的管理員""
            echo '<li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#admin">管理員權限</a>
                  </li>';
        }
    ?>

  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div id="modify" class="container tab-pane active"><br>
      <!-- <h3>修改會員資料</h3> -->
         <form action="<?php echo basename($_SERVER['PHP_SELF']);?>" method="POST">   <!-- basename($_SERVER['PHP_SELF']) 抓本身的檔名 -->
            帳號：<?php echo $_SESSION['account'];?><br/>
            真實姓名：<input name="realname" value="<?php echo $_SESSION['realname']?>"><br/>
            暱稱：<input name="nickname" value="<?php echo $_SESSION['nickname']?>"><br/>
            密碼：<input name="password" placeholder="必填"><br/>
            修改密碼：<input name="newpwd1" placeholder="僅修改密碼時需填"><br/>
            確認密碼：<input name="newpwd2" placeholder="僅修改密碼時需填"><br/>
            <input type="submit">
        </form>

        <?php echo $resultStr;?>

    </div>
    
    <?php 
        if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != null && $_SESSION['is_admin'] == 1){  //如果是管理員就可以"任免新的管理員""
            echo '<div id="admin" class="container tab-pane fade"><br>';
            
            //印出所有使用者和其身分
            echo '<form action=\'member_info.php\' method="POST"><table>';
            echo '<tr><th>會員帳號</th><th>身分</th></tr>';
            $sth = $dbh->query('SELECT * from member ORDER BY id');
            while($row = $sth->fetch(PDO::FETCH_ASSOC)){
                echo '<tr>';
                echo '<td>'.$row['account'].'</td>';
                echo '<td><select name="user_admin_'.$row['account'].'">';

                if($row['is_admin']==1){
                    echo '<option value="0">user</option>
                          <option value="1" selected>admin</option>';
                } else if($row['is_admin']==0){
                    echo '<option value="0" selected>user</option>
                          <option value="1">admin</option>';
                }
                            
                echo '</select></td>';
            }
            echo '</table><br><br>';


            echo '<input type="submit" value="修改權限資料">';
            echo '</div>';
        }

    ?>



  </div>
</div>


</body></html>