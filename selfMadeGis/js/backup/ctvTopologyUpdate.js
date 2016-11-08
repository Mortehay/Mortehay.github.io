
function ctvTopologyUpdate() {
 
	  console.log('city_eng',$('#city_eng').val());
	        $.ajax({
	            url: 'ctvTopologyUpdate.php', //This is the current doc
	            type: "POST",
	            data: ({city_eng: $('#city_eng').val()}),
	            success: function(data){
	                // Why were you reloading the page? This is probably your bug
	                // location.reload();

	                // Replace the content of the clicked paragraph
	                // with the result from the ajax call
	                //$("#city_eng").html(data);
	                console.log('data', data);
	            }
	        });        
	  
}
