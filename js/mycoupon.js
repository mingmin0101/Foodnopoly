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

// mycoupon filter
$(document).ready(function(){
  $("#myCouponInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myCouponTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
