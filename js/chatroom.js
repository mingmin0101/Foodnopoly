function sendMsg(){
    $.post(
        "ajax_chatroom_insert.php",
        {
            // nickname: $("#nickname").val(),
            msg: $("#msg").val()
        }
    );
    $("#msg").val("");
}
 
function showMsg(t){
    $.post(
        "ajax_chatroom_disp.php",
        {
            'getNewMsgOnly': t
        }
    ).done(function(data){
        $("#showMsgHere").append(data);
        setTimeout("showMsg(1);", 1000);
    });
}
 
$(function(){
    // 網頁載入，抓取全部訊息
    showMsg(0);
    // 按下 enter 後送出訊息
    $("#msg").bind("keydown",
        function(e){
            if(e.which == 13){
                sendMsg();
            }
        }
    )
})