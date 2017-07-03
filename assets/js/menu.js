$(document).on('click', function(event) {

  /*
  if (!$(event.target).closest('.list-fab').length)
  {
    $(".list-menu").removeClass("active");
  }
  else
  {
	   $(".list-menu").addClass("active");
  }
  */

  var io = 0;
  $(".list-fab").on("click",function(){
      if (io == 0)
      {
        $(".list-menu").addClass("active");
        io = 1;
      }
      else
      {
        $(".list-menu").removeClass("active");
        io = 0;
      }
  });

});
