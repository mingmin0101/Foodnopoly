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
                if($row['img_content'] != '0'){
                    echo "<img src='test/".$row['img_content']."' width='200px'>";
                }
                echo "<p>".$row['content']."</p>";
                // echo "<div class='like'>Like!</div> <br/><br/>";
            }
        }
    }



?>