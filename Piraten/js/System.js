var System = {

	init: function() {
		
		$(".addmemberfields").hide();
		$("#addmember").click(function() {
			$(this).fadeOut("slow", function() {
				$(".addmemberfields").fadeIn("slow");
			});
		});

		$("#Avbryt").click(function() {
			$(".addmemberfields").fadeOut("slow", function() {
				$("#addmember").fadeIn("slow");
			});
		});

		$(".addboatcontainer").hide();
		$(".addboat").click(function() {
			$(this).parent().fadeOut("slow", function() {
				$(".addboatcontainer").fadeIn("slow");
			});
		});

		$(".abortaddboat").click(function() {
			$(".addboatcontainer").fadeOut("slow", function() {
				$(".addboat").parent().fadeIn("slow");
			});
		});

		$(".showboats").click(function() {
			var that = $(this);
			that.fadeOut("slow", function() {
				that.parent().animate({ width: "200px" }, 1000, function() {
					that.next().fadeIn("slow", function() {
						that.next().next().fadeIn("slow");
					});
				});
			});
		});

		$(".hideboats").click(function() {
			var that = $(this);
			that.next().fadeOut("slow", function() {
				that.fadeOut("slow", function() {
					that.parent().animate({ width: "65px" }, 1000, function() {
						that.prev().fadeIn("slow");
					})
				});	
			});	
		});
	}

};

window.onload = System.init;