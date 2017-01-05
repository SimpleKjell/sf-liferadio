jQuery(document).ready(function(jQuery)
{
	sfl_frontend = new SFLFrontEnd();
});

function SFLFrontEnd() {
  this.init();
};

SFLFrontEnd.prototype = {
	/* Alles wichtige was am Anfang gebraucht wird */
	init:function () {
		/* Aufrufen von wichtigen Funktionen */
		this.fbInit();
		this.formSubmit();
		this.svgInit();
		this.wallInit();
	},
	wallInit: function() {

		// Bereche, wieviele nebeneinander können
		var window_width = jQuery('.sfl_wall_sub_container').width();
		var wall_container_width = window_width * 2/3;

		jQuery('.sfl_wall_sub_container_inner').css('width', wall_container_width);

		var numItems = jQuery('.sfl_wall_sub_single').length
		var itemWidth = jQuery('.sfl_wall_sub_single').outerWidth();
		var howManyToShowSlide = Math.floor(wall_container_width / itemWidth);
		console.log(itemWidth);
		console.log(howManyToShowSlide);
		if(numItems > howManyToShowSlide) {
			jQuery('.sfl_wall_sub_container_inner_block').slick({

				centerMode: true,
			  centerPadding: '60px',
			  slidesToShow: 3,
				variableWidth: true,
				arrows: false
			});
			/*
			infinite: true,
			slidesToShow: 3,
			slidesToScroll: 3,
			variableWidth: true,
			centerMode: true,
			adaptiveHeight: true,
			autoplay: true,
			autoplaySpeed: 4000,
			arrows: false
			*/

			jQuery('.sfl_nav_right').click(function() {
					var currentSlide = parseInt(jQuery('.slick-current').attr('data-slick-index'));
					jQuery('.sfl_wall_sub_container_inner_block').slick('slickGoTo',currentSlide+1);
			})

			jQuery('.sfl_nav_left').click(function() {
					var currentSlide = parseInt(jQuery('.slick-current').attr('data-slick-index'));
					if(currentSlide != '0') {
							jQuery('.sfl_wall_sub_container_inner_block').slick('slickGoTo',currentSlide-1);
					}
			})
		} else {
			// Es sind nicht genügend Slides vorhanden, also blende die Pfeile aus
			jQuery('.sfl_nav_left').hide();
			jQuery('.sfl_nav_right').hide();
		}



	},
	fbInit: function() {

		window.fbAsyncInit = function() {
	    FB.init({
	      appId      : '365222543851422',
	      xfbml      : true,
	      version    : 'v2.7'
	    });
	  };

	  (function(d, s, id){
	     var js, fjs = d.getElementsByTagName(s)[0];
	     if (d.getElementById(id)) {return;}
	     js = d.createElement(s); js.id = id;
	     js.src = "//connect.facebook.net/en_US/sdk.js";
	     fjs.parentNode.insertBefore(js, fjs);
	   }(document, 'script', 'facebook-jssdk'));

	},
	svgInit: function() {


		// Schreibe aus dem Textfeld den Value in das SVG Element Text
		jQuery('#wunsch').keyup(function() {
			var value = jQuery(this).val();
			value = value.toUpperCase();
			jQuery('#fb_image_text').text(value);
			jQuery('#fb_image_text_wall').text(value);
		})

		// Schreibe den Namen auf das Wall Bild
		jQuery('#vorname').keyup(function() {
			var value = jQuery(this).val();
			jQuery('#wall_image_name').text(value);
		})


	},
	formSubmit: function() {



		jQuery('#sfl_form_container').submit(function(evt) {

			// Stop form from submitting normally
  		evt.preventDefault();

			// Validierung hier
			if(jQuery('#sfl_form_container').parsley().validate()) {

				var vorname = jQuery('#sfl_form_container #vorname').val();
				var nachname = jQuery('#sfl_form_container #nachname').val();
				var tel = jQuery('#sfl_form_container #telefon').val();
				var mail = jQuery('#sfl_form_container #email').val();
				var nonce = jQuery('#sfl_form_container #form_nonce').val();



				// Canvas erstellen FB Teilung
				// Canvas Element FB Teilung
				var c=document.getElementById("svg-canvas");
				var html = jQuery('#mySVG').html();
				html = html.replace(/>\s+/g, ">").replace(/\s+</g, "<");
				canvg(c, '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="1200" height="627">'+html+'</svg>', { ignoreMouse: true, ignoreAnimation: true });

				// Canvas erstellen Wall
				// Canvas Element Wall
				var c=document.getElementById("svg-canvas-wall");
				var html = jQuery('#mySVGWall').html();
				html = html.replace(/>\s+/g, ">").replace(/\s+</g, "<");
				canvg(c, '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="250" height="250">'+html+'</svg>', { ignoreMouse: true, ignoreAnimation: true });


				data = {
					 action: 'add_new_suberino',
					 nonce : nonce,
					 vorname : vorname,
					 nachname : nachname,
					 telefon : tel,
					 mail : mail
				}

				var addNewSuberino = jQuery.ajax(
					Custom.ajaxurl,{
						data,
						type : "POST",
						beforeSend: function( xhr ) {

							jQuery('#sfl_form_container').hide();
							jQuery('.sfl_container').append('<div class="xhrWait sfl_box sfl_flow_text"><center><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br /><br />Dein Wunsch wird bearbeitet. Bitte habe ein wenig Geduld.</center></div>');

						},
					}
				);

				addNewSuberino.done(function(response) {
					console.log(response);


					// Jetzt das jpg speichern und dem User zuordnen
					// FB Teilung
					var c=document.getElementById("svg-canvas");
					var img_src =  c.toDataURL('image/png')
					jQuery('#svg-img').attr('src', img_src);

					// Wall
					var c=document.getElementById("svg-canvas-wall");
					var img_src_wall =  c.toDataURL('image/png')
					jQuery('#svg-img-wall').attr('src', img_src_wall);

					var teilnehmer_id = response;
					jQuery.ajax({
							url : Custom.ajaxurl,
							type : 'post',
							data : {
								 action : 'add_design_to_sub',
								 teilnehmer_id : teilnehmer_id,
								 img_src : img_src,
								 img_src_wall : img_src_wall
							},
							beforeSend: function( xhr ) {
							},
							error : function(error) {
								console.log(error)
								alert('Keine Berechtigung')
							},
							success: function(uri) {
								console.log(uri);
								jQuery('.xhrWait').hide();
								jQuery('.finish_process_sub_teilnahme').css('display', 'inline-block');



								// FB Share
								jQuery("#sfl_fb_share").click(function(a) {
			            a.preventDefault(), FB.ui({
			                method: "feed",
											name: "LifeRadio verdoppelt Dein Gehalt!",
			                link: "http://gehaltverdoppeln.liferadio.at",
											picture: uri,
			                caption: "Liferadio verdoppelt dein Gehalt!",
			                description: "So einfach geht Dein Traum in Erfüllung: Mitspielen auf http://gehaltverdoppeln.liferadio.at, jeden Morgen kurz nach 7 Uhr LifeRadio hören und gewinnen!",
			            }, function(a) {})
									jQuery('.finish_process_sub_teilnahme').hide();
									jQuery('.finished_sub_teilnahme').css('display', 'inline-block');
								})

							}
					})

				})
			}

			return false;
		})
	},
}
