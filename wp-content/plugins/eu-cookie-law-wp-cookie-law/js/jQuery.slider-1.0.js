(function( $ ) {
  $.fn.wpCookieSlider = function(options) {
  
    // New - you don't have to specify options!
    options = options || {};

	// Default awesomeness
    var defaults = {
      bgColour: '#666',         // The directory we're in
      fontColour: '#fff',           // change me to "right" if you want rightness
	  borderColour: '#000',
      imgLocation: 'privacy-image.png' ,       // If this is set to true, the fold will curl/uncurl on mouseover/mouseout.
	  headerTxt: '',
	  subHeaderTxt: '',
 	  subdHeaderTxt: '',
	  redirectUrl: 'http://www.wpcookielaw.com/cookie-privacy',
	  poweredBy: ''
    };

	// Merge options with the defaults
    var options = $.extend(defaults, options);

	addSlider = function(){
		//color: #91a44d; border-color: #c2d288; background-color: #e3ebc6; padding: 10px 10px 10px 40px;'
		//<b><a id=\"powered-by\" href=\"http://www.wpcookielaw.com/\" title=\"Powered By WP Cookie Law\" target=\"_blank\">Powered By WP Cookie Law</a></b>
		$("body").prepend("<div id='sl-container' class='hide-on-phones' style='display: block; font-family: sans-serif; font-size: 11px; border: solid 1px; color: "+options.fontColour+";  background-color: "+options.bgColour+"; border-color: "+options.borderColour+"; padding: 6px 10px 10px 40px;' />");
		$("#sl-container").append("<div id='sl-centre'/>");
		$("#sl-centre").append("<div id='sl-imgDiv' style='float: left;'/>");
		$("#sl-imgDiv").append("<img src='"+options.imgLocation+"' alt='' />");
		$("#sl-centre").append("<div id='sl-content' style='float: left; width: 30%; text-align: left; font-family: \"Trebuchet MS\",\"Lucida Sans Unicode\",\"Lucida Grande\",\"Lucida Sans\",Arial,sans-serif; font-size: 12px; font-weight: normal; margin-left: 25px;'/>");
		$("#sl-content").append("<p style=' margin: 10px 0 -30px 0; color: #FFF; font-size: 2.45em; '>"+options.headerTxt+"</p><p style=' margin: 10px 0 0; font-size: 12px;'><br>Wij zijn van: <b>"+options.subDVHeaderTxt+"</b> t/m <b>"+options.subDTHeaderTxt+"</b> gesloten.</p></div>");
		
		$("#sl-centre").append("<div id='sl-content2' style='float: left; width: 30%; text-align: left; font-family: \"Trebuchet MS\",\"Lucida Sans Unicode\",\"Lucida Grande\",\"Lucida Sans\",Arial,sans-serif; font-size: 11px; font-weight: normal; margin-left: 25px;' /> ");
		$("#sl-content2").append("<p style=' margin: 5px 0 ; font-size: 11px;'>"+options.subHeaderTxt+"</p>");
		$("#sl-content2").append("<p style=' margin: 0px 0 0; font-size: 11px;'><li><b>"+options.subOpening1+"</b> van "+options.subtijdv1+" uur tot "+options.subtijdt1+" uur.</li>");
		$("#sl-content2").append("<li><b>"+options.subOpening2+"</b> van "+options.subtijdv2+" uur tot "+options.subtijdt2+" uur.</li></p>");

		$("#sl-centre").append("<div id='sl-buttons' style='float: right;'/>");
		$("#sl-buttons").append("<p style='margin: 10px 0 0; margin-left: 25px; text-align: right;'>"+"<a class=\"wp-cookie-button green\" style='color: #ffffff;  text-shadow: 0 1px 1px rgba(0, 0, 0, 0.9);' onclick=\"alue_cookie.doClick();\" title=\"Click To Continue\">Niet meer weergeven</a></p>");
		$("#sl-centre").append("<div style='clear: both;'></div>");
		$("#sl-centre").append("<span style='font-size: 10px;'>"+options.poweredBy+"<span>");
	}

	addSlider();

  };
})( jQuery );