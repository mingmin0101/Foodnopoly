// 滑動
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

// coupon redeem
function redeemCoupon(ID) {
  var txt;
  if (confirm("你確定要兌換這張coupon麻!")) {
    // 確認兌換
    $.post(
        "php/coupon_redeem.php",
        {
            redeem_coupon_id: ID
        },
        // function(data, status, xhr){
        //     alert("Data: " + data + "\nStatus: " + status + "\nxhr: "+xhr);
        // }
    );
    
    txt = "You pressed OK!";
    window.location.reload();

  } else {
    // 取消
    txt = "You pressed Cancel!";
  }
  document.getElementById("demo").innerHTML = txt;
}
// mycoupon used
function changeCouponStatus(couponID){
    $.post(
        "php/coupon_used.php",
        {
            id: couponID
        }
    );
    window.location.reload();
}    
