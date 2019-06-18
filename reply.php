<?php
include('pdoInc.php');
    
    function replyTopic($id){
        include('pdoInc.php');
        $select = mysqli_query($con, "SELECT *
                                      FROM restaurant
                                      WHERE restaurant_id = $id");
            while($row = mysqli_fetch_assoc($select)){
                echo "<h4>大家對於 <b>".$row['name']."</b> 的評價</h4>";
            }
    }

    function replyContent($id){
        include('pdoInc.php');
        $select = mysqli_query($con, "SELECT *
                                      FROM   reply
                                      WHERE  restaurant_id = $id");

        $member = $dbh->prepare('SELECT * FROM member WHERE account = ?'); //目前登入的會員
        if(isset($_SESSION['account'])){  //有登入
            $member->execute(array($_SESSION['account']));  //目前登入的會員
            while ($memberRow= $member->fetch(PDO::FETCH_ASSOC)) {
                
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


                        if(isset($_GET['id']) && ($memberRow['id'] == $row['member_id'] || $memberRow['is_admin'] == 1)){ //如果是自己留言的 || 如果是管理員
                            echo '<a href="restaurant.php?id='.$_GET['id'].'&del='.$row['reply_id'].'"><img src="pic/trash.png" style="height:18px;float:right;" onmouseover="this.src=\'pic/trash_hover.png\'" onmouseleave="this.src=\'pic/trash.png\'"></a>';
                        }

                        echo '</div></div><br>';
                    }
                }
                
            }
        }
    }

// function replyContent($id){
//         include('pdoInc.php');
//         $select = mysqli_query($con, "SELECT replier, content, img_content, rate, date_posted
//                                       FROM   reply
//                                       WHERE  restaurant_id = $id");
//         if(mysqli_num_rows($select) != 0){
//             while($row = mysqli_fetch_assoc($select)){
//                 echo '<div class="card text-dark" style="background-color:rgb(255,255,255,0.3)">
//                         <div class="card-body">';

//                 echo '<div style="float:right">評 價 ';
//                 for ($i=0;$i<$row['rate'];$i++){
//                     echo '<span class="fa fa-star checked"></span>';
//                 }
//                 for ($j=0;$j<5-$row['rate'];$j++){
//                     echo '<span class="fa fa-star"></span>';
//                 }
//                 echo '</div>';

//                 echo "<p>".$row['replier']." 在 ".$row['date_posted']." 寫了一則評論</p>";
//                 // echo "<p> 評分：".$row['rate']."分</p>";

//                 if($row['img_content'] != '0'){
//                     echo "<img src='test/".$row['img_content']."' width='200px'>";
//                 }
//                 echo "<p style='margin:0px;'>".$row['content']."</p>";
//                 // echo "<div class='like'>Like!</div> <br/><br/>";

//                 echo '</div></div><br>';
//             }
//         }
//     }
    
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