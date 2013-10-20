var System = {

values: [],

init: function() {

	$(".editbuttons").hide();

	$(".showeditfields").click(function () {
		$(this).fadeOut("slow", function() {
			$(".values").fadeOut("slow", function() {
				$(".editbuttons").fadeIn("slow");
    				System.values = [];
				$(".field").each(function() {

					System.values.push($(this).text());
					$(this).parent().append("<input type='text' title='" + $(this).attr("title") +"' class='field' id='" + $(this).attr("id") + "' value='" + $(this).text() + "'/>");
					$(this).remove();
			
				});
	
				$(".values").fadeIn("slow");

				$("#editmemberform .field").not($("#socialNumberEdit")).blur(function () {
    		function checkEmpty(text) {
        		return (/^\s*$/).test(text);
    		}

		console.log("Trigger!");

    		var title = $(this).attr("title");

    		if (checkEmpty($(this).val())) {       
        		if ($("." + title + "error").length == 0) $(".error").append('<div class="' + title + 'error">' + title + ' saknas!</div>');
    			} else {
        		if ($("." + title + "error").length != 0) $("." + title + "error").remove();
    		}
	});

	$("#socialNumberEdit").blur(function () {

    	if (!(/^\d{6,6}-\d{4,4}$/).test($("#socialNumberEdit").val())) {
        	if ($(".socialerror").length == 0) $(".error").append('<div class="socialerror"> Personnummer är felformaterat! Ex. 123456-1234</div>');
    		} else {
        	if ($(".socialerror").length != 0) $(".socialerror").remove();
    	}

	});
			});
		});
		return false;
	});

	$(".canceledit").click(function () {
		
		$(".editbuttons").fadeOut("slow", function() {			
			$(".values").fadeOut("slow", function() {

				$(".showeditfields").fadeIn("slow");
				var id = 0;	
				$(".field").each(function() {

					$(this).parent().append("<p class='field' id='" + $(this).attr("id") + "'>" + System.values[id] + "</p>");
					$(this).remove();
					id++;
				});
				$(".values").fadeIn("slow");
			});
		});
		return false;
	});

	$(".saveedit").click(function () {
		return false;
	});

}

};

window.onload = System.init;