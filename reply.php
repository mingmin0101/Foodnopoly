<?php
session_start();
include('pdoInc.php');
    
    function replyTopic($id){
        include('pdoInc.php');
        $select = mysqli_query($con, "SELECT *
                                      FROM restaurant
                                      WHERE restaurant.restaurant_id = $id");
            while($row = mysqli_fetch_assoc($select)){
                echo "<h3>大家對於".$row['name']."的評價</h3>";
            }
    }


    function replyContent($id){
        include('pdoInc.php');
        $select = mysqli_query($con, "SELECT replier, content, img_content, rate, date_posted
                                      FROM   reply
                                      WHERE  restaurant_id = $id");
        if(mysqli_num_rows($select) != 0){
            while($row = mysqli_fetch_assoc($select)){
                echo "<p>".$row['replier']." 在 ".$row['date_posted']." 寫了一則評論</p>";
                echo "<p> 評分：".$row['rate']."分</p>";
                echo "<p>".$row['content']."</p>";
                echo "<div class='like'>Like!</div>";
                // echo "<img src='pic/food_category/dessert.jpg'>";
            }
        }
    }

    // for reply_form


// if(isset($_SESSION['account']) && isset($_POST['content']) && isset($_POST['rate'])){
//     $rid = $_GET['id'];
//     $mid = $_SESSION['member_id'];
//     $imid = 0;
//     $replier = $_SESSION['nickname'];
//     $content = nl2br(addslashes($_POST['content']));
//     $imgContent = 0;
//     $rate = $_POST['rate'];
    

//     $insert = mysqli_query($con, "INSERT INTO reply (`restaurant_id`, `member_id`, `image_id`, `replier`, `content`, `img_content`, `rate`, `date_posted`) 
//                                   VALUES ('".$rid."', '".$mid."', '".$imid."', '".$replier."', '".$content."', '".$imgContent."', '".$rate."', NOW());");
    
//     if($insert){
//         // header("Location: /test2.php?id=".$rid."");
//     }
// }
    


?>