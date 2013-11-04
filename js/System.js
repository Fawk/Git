var System = {

	init: function() {

	    $("#formadd").submit(function () {

	        $(".formerror").text("");

	        var isvalid = true;
	        var notice = "<div class='notice'><div class='arrow_box'><span>Var god fyll i detta fält!</span></div></div>";

	        $(this).find(".input").each(function (index, obj) {
	            $(".notice").remove();
	            if ($.trim($(this).val()).length < 1) {
	                var name = $(this).attr("name");
	                var that = $(this);
	                if (!that.is(":visible")) {
	                    System.doToggle(that);
	                }

	                setTimeout(function () {
	                    $("body").prepend(notice);
	                    var pos = that.position();
	                    $(".notice").css({ top: pos.top + 2, left: pos.left + parseInt(that.css("width")) + 12 });
	                    $(".notice").show();
	                    that.select();
	                }, 500);
	                isvalid = false;
	                return false;
	            }
	        });

	        if (isvalid) {

	            $.ajax({
	                url: "?createForm",
	                type: "post",
	                data: $(this).serialize(),
	                success: function (response) {
	                    var data = $(response);
	                    $(".formerror").text(data.find(".formerror").text());
	                    if ($(".formerror").text() == "") {
	                        $(".successmessage").text(data.find(".success h3").text());
	                        window.scrollTo(0, 0);
	                        setTimeout(function() {

	                                location.href = "?";

	                        }, 1000);
	                    }
	                }
	            });
	        }
	        return false;
	    });

	    $(".field").hide();

	    $(document).delegate(".addradio", "click", function () {
	        var id = $(this).parent().index() - 1;
	        var lastf = $(this).parent().parent().find(".radios input").last();
	        last = parseInt(lastf.attr("data-id")) + 1;
	        var r = $("<div class='radioinput'><input class='input' type='text' title='Radioknapp värde' placeholder='Radioknapp värde' name='radiovalues[" + id + "][]' data-id='" + last + "' /><span title='Ta bort radioknapp' class='glyphicon glyphicon-remove'></span></div>");
	        $(this).parent().parent().parent().animate({ height: "+=40px" }, 200, function () {
	            r.insertAfter(lastf.parent());
	        });
	        return false;
	    });

	    $(document).delegate(".field .radioinput span[title='Ta bort radioknapp']", "click", function () {
	        if ($(this).prev().attr("data-id") < 3) {
	            alert("Det måste finnas minst två radioknappar!");
	        } else {
	            $(this).parent().fadeOut("fast", function () { $(this).parent().parent().parent().animate({ height: "-=40px" }); $(this).remove(); });
	        }
	    });

	    $(".showanswers").click(function () {

	        if ($(this).next().is(":visible")) {
	            $(this).text("Visa svar");
	            $(this).next().slideToggle("fast");
	        } else {
	            $(this).text("Göm svar");
	            $(this).next().slideToggle("fast");
	        }

	    });
        
	    $("select").each(function (index, obj) {
	        var text = "";
	        var value = "";
	        $(this).find("option").each(function () {
	            if ($(this).attr("selected") == "selected") {
	                text = $(this).text();
	                value = $(this).val();
	            }
	        });

	        $(this).find(".bootstrap-select .filter-option").text(text);
	        $(this).val(value);
	    });

	    $("select").selectpicker();
		
		if($(".error").text() != "") {
			
			if(System.checkParameterExists("register")) {
				$(".loginfield").hide();
				$(".registerfield").show();
			}
    				$(".topbar").css({ marginTop: "-10px" });
				$(".forms").css({ boxShadow: "0px 6px 10px rgba(0, 0, 0, 0.75)" });
				$(".togglepanel").text("Göm Panel");
		}

		if($(".message").text() != "") {
			$(".topbar").css({ marginTop: "-10px" });
			$(".forms").css({ boxShadow: '0px 6px 10px rgba(0, 0, 0, 0.75)' });
		}

		if($(".loggedin").text() != "") {
    			$(".togglepanel").hide();
			$(".loginfield").hide();
			$(".registerfield").hide();
			var w = parseInt($(".loggedin").css("width"));
			$(".forms").css({ boxShadow: '0px 6px 10px rgba(0, 0, 0, 0.75)' });
			$(".topbar").css({ width: w + 30 + "px", marginTop: "-267px" });
    			$(".loggedin").show();
		}    

		$(".togglepanel").click(function() {
    			if(parseInt($(this).parent().css("margin-top")) < -10) {
				$(".forms").css({ boxShadow: "0px 6px 10px rgba(0, 0, 0, 0.75)" });
        			$(this).parent().animate({ marginTop: "-10px" }, 200);
        			$(this).text("Göm Panel");
    			} else {
				$(".forms").css({ boxShadow: "0px 6px 10px rgba(0, 0, 0, 0.0)" });
        			$(this).parent().animate({ marginTop: "-290px" }, 200);
        			$(this).text("Visa Panel");
    			}
		});

		$(".register").click(function() {
    				$(".loginfield").fadeOut("slow", function() {
				$(".error").fadeOut("slow");
        			$(".registerfield").fadeIn("slow");
    			});
		});


		$(".login").click(function() {
    				$(".registerfield").fadeOut("slow", function() {
				$(".error").fadeOut("slow");
        			$(".loginfield").fadeIn("slow");
    			});
		});

		$("button[data-href]").click( function() {
        		location.href = $(this).attr("data-href");
    		});

		if(System.checkParameterExists("creatingForm") || System.checkParameterExists("createForm")) {	
			$(".cform").hide();
		}

		if($(".success").text() != "") {
			$(".cform").show();
		}

		$( "#sortable" ).sortable({
      			placeholder: "ui-state-highlight"
    		});
		$("#sortable").disableSelection();

		$(document).delegate("#sortable .field select", "change", function () {
		    $(".notice").remove();
		    var s = $(this).parent().parent().find("span.typeof");
		    s.text($(this).find("option:selected").text());
		    var id = $(this).parent().parent().index();
		    var r = $("<div class='radioinput'><input type='text' class='input' title='Radioknapp värde' placeholder='Radioknapp värde' name='radiovalues[" + id + "][]' data-id='1' /><span title='Ta bort radioknapp' class='glyphicon glyphicon-remove'></span></div><div class='radioinput'><input type='text' class='input' title='Radioknapp värde' placeholder='Radioknapp värde' name='radiovalues[" + id + "][]' data-id='2' /><span title='Ta bort radioknapp' class='glyphicon glyphicon-remove'></span></div><a href='#' class='addradio'>Lägg till ny radioknapp</a>");
		    var that = $(this);
		    if (s.text() == "Radioknappar") {
		        $(this).parent().parent().animate({ height: "310px" }, 200, function () {
		            var c = that.parent().find(".bootstrap-select");
		            var c2 = that.parent().find(".selectpicker");
		            var ra = that.parent().find(".radios");
		            if (!ra.length) {
		                var rr = $("<div class='radios'></div>");
		                rr.insertAfter(c2);
		            }
		            if (c.length) {
		                r.insertAfter(c);
		            } else { that.parent().find(".radios").append(r); }
		        });
		    } else {
		        that.parent().find(".radios").remove();
		        that.parent().parent().animate({ height: "200px" }, 200);
		    }
		});
		
		$(document).delegate("#sortable .fieldhead span[title='Ta bort fält']", "click", function() {
		    var that = $(this).parent().parent();
		    $(".notice").remove();
			that.fadeOut("slow", function() { that.remove(); });
		
		});
		$(document).delegate("#sortable .fieldhead span[title='Redigera fält']", "click", function() {
			
		    var that = $(this).parent();
		    System.doToggle(that);
			

		});
		$(".addinput").click(function() {
		    var box = $("<div class='ui-state-default' style='display:none;'><div class='fieldhead'><span class='typeof'>Textfält</span><span title='Ta bort fält' class='glyphicon glyphicon-remove'></span><span title='Redigera fält' class='glyphicon glyphicon-wrench'></span></div><div class='field' style='display:none;'><select name='type[]' class='selectpicker' title='Typ av fält'><option value='text'>Textfält</option><option value='number'>Nummer</option><option value='checkbox'>Kryssruta</option><option value='radio'>Radioknappar</option><option value='textarea'>Stycketext</option><option value='date'>Datum</option><option value='datetime-local'>Datum/Tid</option><option value='time'>Tid</option><option value='email'>Epost</option></select><input type='text' placeholder='Titel på fält' title='Titel på fält' name='title[]' class='input' /><input type='text' name='desc[]' title='Beskrivning av fält' placeholder='Beskrivning av fältet' class='input' /><input type='checkbox' class='required' name='required[]' value='1' /><span class='required'>Är fältet obligatoriskt?</span></div></div>");
            		$("#sortable").append(box);
			box.fadeIn("slow");
		});
	},

	checkParameterExists: function(parameter) {

   		fullQString = window.location.search.substring(1);

   		if(fullQString.length > 0) {

       			paramArray = fullQString.split("&");
			if(paramArray.length == 0) {

				currentParameter = fullQString.split("=");
				if(currentParameter == parameter) {
					return true;
				}

			} else {

       				for (i=0;i<paramArray.length;i++) {
         				currentParameter = paramArray[i].split("=");
         				if(currentParameter[0] == parameter) {	
            					return true;
         				}
       				}
			}
   		}
   		return false;
	},

	doToggle: function (that) {

	    var cont = that.parents(".ui-state-default");
        
	    if (parseInt(cont.css("height")) < 200) {
	        var radios = cont.find(".radios input").length;
	        if (!isNaN(radios)) {
	            if (radios > 1) {
	                radios = 30 + 40 * radios;
	            }
	        } else { radios = 0; }
	        cont.animate({ height: 200 + radios + "px" }, 300, function () {
	            cont.find(".field").fadeIn("slow");
	        });

	    } else {
	        cont.find(".field").fadeOut("slow", function () {
	            $(".notice").hide();
	            cont.animate({ height: "38px" }, 300);
	        });
	    }
	}
};

window.onload = System.init;