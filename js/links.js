$(document).ready(function(){
	$('body').on('click', '.list a', function(){
    	var link = $(this).attr('href');
		link = link.split('#');
		alert(link[1]);

    	$("#app").load(link[1]);
		//alert(window.location);
  	})
})
