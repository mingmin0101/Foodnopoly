<?php
session_start();
include('pdoInc.php');
    
    function replyTopic($id){
        include('pdoInc.php');
        $select = mysqli_query($con, "SELECT *
                                      FROM restaurant
                                      WHERE restaurant.restaurant_id = $id");
            while($row = mysqli_fetch_assoc($select)){
                echo "<h4>大家對於 <b>".$row['name']."</b> 的評價</h4>";
            }
    }

    function replyContent($id){
        include('pdoInc.php');
        $select = mysqli_query($con, "SELECT replier, content, img_content, rate, date_posted
                                      FROM   reply
                                      WHERE  restaurant_id = $id");
        if(mysqli_num_rows($select) != 0){
            while($row = mysqli_fetch_assoc($select)){
                echo '<div class="card text-dark" style="background-color:rgb(255,255,255,0.3)">
                        <div class="card-body">';

                echo '<div style="float:right">評 價 ';
                for ($i=0;$i<$row['rate'];$i++){
                    echo '<span class="fa fa-star checked"></span>';
                }
                for ($j=0;$j<5-$row['rate'];$j++){
                    echo '<span class="fa fa-star"></span>';
                }
                echo '</div>';

                echo "<p>".$row['replier']." 在 ".$row['date_posted']." 寫了一則評論</p>";
                // echo "<p> 評分：".$row['rate']."分</p>";

                if($row['img_content'] != '0'){
                    echo "<img src='test/".$row['img_content']."' width='200px'>";
                }
                echo "<p style='margin:0px;'>".$row['content']."</p>";
                // echo "<div class='like'>Like!</div> <br/><br/>";

                echo '</div></div><br>';
            }
        }
    }

    
    // function replyContent($id){
    //     include('pdoInc.php');
    //     $select = mysqli_query($con, "SELECT replier, content, img_content, rate, date_posted
    //                                   FROM   reply
    //                                   WHERE  restaurant_id = $id");
    //     if(mysqli_num_rows($select) != 0){
    //         while($row = mysqli_fetch_assoc($select)){
    //             echo '<div class="card text-dark" style="background-color:rgb(255,255,255,0.3)">
    //                     <div class="card-body">';

    //             echo "<p>".$row['replier']." 在 ".$row['date_posted']." 寫了一則評論</p>";
    //             echo "<p> 評分：".$row['rate']."分</p>";
    //             if($row['img_content'] != '0'){
    //                 echo "<img src='test/".$row['img_content']."' width='200px'>";
    //             }
    //             echo "<p style='margin:0px;'>".$row['content']."</p>";
    //             // echo "<div class='like'>Like!</div> <br/><br/>";

    //             echo '</div></div><br>';
    //         }
    //     }
    // }



?>