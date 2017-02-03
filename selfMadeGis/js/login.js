function close_window() {
      close();
 };
$(document).ready(function(){

  $('#login').closest('form').find("input[type=text], input[type=password], textarea").val("");
  $('#login').find('input').keypress(function (e) {
	  if (e.which == 13) {
	  	console.log('click');
	    $('form#login').submit();
	    //return false;    //<---- Add this line
	  }
	});
    
});