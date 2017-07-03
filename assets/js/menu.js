$(document).on('click', function(event) {

  if (!$(event.target).closest('.list-fab').length)
  {
    $(".list-menu").removeClass("active");
  }
  else
  {
	$(".list-menu").addClass("active");
  }

});
