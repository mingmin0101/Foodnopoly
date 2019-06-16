<?php
session_start();
include("../pdoInc.php");
?>

<html>
<head></head>
<body>

<?php
if(isset($_SESSION["account"]) && isset($_POST["redeem_coupon_id"])){  //$_POST["id"] couponid
    echo $_POST["redeem_coupon_id"];
    // 根據登入session挑選出member資料
    $member = $dbh->prepare('SELECT * from member WHERE account = ?');  
    $member->execute(array($_SESSION['account']));
    $memberRow = $member->fetch(PDO::FETCH_ASSOC);
    echo 'memberid:';
    echo $memberRow['id'];
    
    // 根據 $_POST["id"] 找出該coupon資料
    $coupon = $dbh->prepare('SELECT * from coupon WHERE id = ?');  
    $coupon->execute(array($_POST["redeem_coupon_id"]));
    $couponRow = $coupon->fetch(PDO::FETCH_ASSOC);
    echo 'couponinfo:';

    // 找出該coupon的餐廳資訊
    $restaurant = $dbh->prepare('SELECT * from restaurant WHERE restaurant_id = ?');  
    $restaurant->execute(array($couponRow["restaurant_id"]));
    $restaurantRow = $restaurant->fetch(PDO::FETCH_ASSOC);
    echo $restaurantRow['name'];
    echo '<br>'.$memberRow['point'].'<br>'.$couponRow['cost'].'<br>';

    if($memberRow['point'] > $couponRow['cost']){ // 檢查該會員的點數能不能夠兌換
        // pointrecord insert 一筆新紀錄 
        $newpointrecord = $dbh->prepare('INSERT INTO pointrecord (member_id, point_out, record) VALUES (?, ?, ?)');
        $record = "兌換coupon (".$restaurantRow['name'].",".$couponRow['discount']."折)";
        echo $record;
        $newpointrecord->execute(array($memberRow['id'], $couponRow['cost'], $record));

        // couponrecord insert 一筆新紀錄
        $newcouponrecord = $dbh->prepare('INSERT INTO couponrecord (member_id, coupon_id, status) VALUES (?, ?, ?)');
        $newcouponrecord->execute(array($memberRow['id'], $_POST["redeem_coupon_id"], 'valid'));

        // 找出該會員的point紀錄
        $memberCoupon = $dbh->prepare('SELECT SUM(point_in) AS total_point_in, SUM(point_out) AS total_point_out from pointrecord WHERE member_id = ?');  
        $memberCoupon->execute(array($memberRow['id']));
        $memberCouponRow =$memberCoupon->fetch(PDO::FETCH_ASSOC);
        echo $memberCouponRow['total_point_in'];
        echo '<br>';
        echo $memberCouponRow['total_point_out'];
        echo '<br>';

        // update 該會員的 total point
        $total_point = $memberCouponRow['total_point_in'] - $memberCouponRow['total_point_out'];
        echo $total_point;
        $memberUpdatePoint = $dbh->prepare('UPDATE member SET point = '.$total_point.' WHERE id = ?');
        $memberUpdatePoint->execute(array($memberRow["id"]));

        // echo 'window.location.reload();';
        echo '成功';
    } else {
        echo '點數不足';
        // echo 'window.location.reload();window.alert("剩餘點數不足無法兌換!");';
    }
   
}

?>

</body>
</html>