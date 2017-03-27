jQuery(function( $ ){
	$(".launch-audio").click(function(event){
		event.preventDefault();
		
		var playerImg = $(".post-background.wp-post-image").attr('src');
		console.log(playerImg);
		$(".mejs-container").addClass("imgLoaded");
		$(".mejs-container").css('background-image', 'url(' + playerImg + ')');
		/*
		var href = $(this).attr('href');
		alert(href);
		var player = $("#mep_0").clone();
		console.dir(player);
		$("#player-home").html(player);
		*/
	});
		

	console.log("loaded");
	var playerImg = $(".post-background.wp-post-image").attr('src');
	console.log(playerImg);
	$(".mejs-container").addClass("imgLoaded");
	$(".mejs-container").css('background-image', 'url(' + playerImg + ')');
	
	if ( $("audio").length ) {
		console.log("loaded");
	} else {
		console.log("no element");
	}
	
	var checkExist = setInterval(function() {
   if ($('.mejs-container').length) {
	var playerImg = $(".post-background.wp-post-image").attr('src');
	console.log(playerImg);
	$(".mejs-container").addClass("imgLoaded");
	$(".mejs-container").css('background-image', 'url(' + playerImg + ')');
      clearInterval(checkExist);
   }
}, 100); // check every 100ms
});

function loadMedia(){
	console.log("FUNCTION: LoadMedia");
	$('article video,article audio').mediaelementplayer().load();
	console.log("LOADING MEDIA");
	var playerImg = $(".post-background.wp-post-image").attr('src');
	console.log("playerImg: "+ playerImg);
	  
	if($("article .mejs-container").length ) {
		console.log("found mejs: "+$("article .mejs-container").length);
		$("article .mejs-container").addClass("imgLoaded");
		$("article .mejs-container").css('background-image', 'url(' + playerImg + ')');
	} else if($(".wp-audio-shortcode").length ){
		console.log("found audio elm");
		$("article .wp-audio-shortcode").addClass("imgLoaded");
		$("article .wp-audio-shortcode").css('background-image', 'url(' + playerImg + ')');
	} else {
		console.log("not found");
	}
	
	
	
	if ( $("audio").length ) {
		console.log("loaded");
	} else {
		console.log("no element");
	}
	
	var checkExist = setInterval(function() {
		if ($('.mejs-container').length) {
			var playerImg = $(".post-background.wp-post-image").attr('src');
			//console.log(playerImg);
			$("article .mejs-container").css('background-image', 'url(' + playerImg + ')');
			$("article .mejs-container").addClass("imgLoaded").delay(1000);
			clearInterval(checkExist);
		}
	}, 100); // check every 100ms
	
	jQuery("#footer_media_link").click(function(){
		console.log("loadMedia");
		var audioCode = jQuery(".wp-audio-shortcode .mejs-mediaelement").html();
		if(jQuery("#soundcloud_link").length > 0){
			console.log("soundcloud");
			audioCode = jQuery("#soundcloud_link").html();
		}
		var title = jQuery("h1.entry-title").html();
		var url = window.location.pathname; 
		var link = '<label><a href="'+url+'">'+title+'</a></label>';
		console.dir(audioCode);
		jQuery("#footer_media_player").html(link+audioCode);
		jQuery("#footer_media_player").addClass("loaded");
		  
		
		var player = new MediaElementPlayer('#footer_media_player audio');
		player.play();
		
		//$('#footer_media_player audio').mediaelementplayer().load().play();
		//$('#footer_media_player audio').mediaelementplayer();
		
		/*
		player.pause();
		player.setSrc('mynewfile.mp4');
		*/
	});
	
}

function loadAudioPlayer(){
	jQuery("#audio_player").click(function(){
		console.log("loadMedia");
		var audioCode = jQuery(".wp-audio-shortcode .mejs-mediaelement").html();
		if(jQuery("#soundcloud_link").length > 0){
			console.log("soundcloud");
			audioCode = jQuery("#soundcloud_link").html();
		}
		var title = jQuery("h1.entry-title").html();
		var url = window.location.pathname; 
		var link = '<label><a href="'+url+'">'+title+'</a></label>';
		console.dir(audioCode);
		jQuery("#footer_media_player").html(link+audioCode);
		jQuery("#footer_media_player").addClass("loaded");
		  
		
		var player = new MediaElementPlayer('#footer_media_player audio');
		player.play();
		
		//$('#footer_media_player audio').mediaelementplayer().load().play();
		//$('#footer_media_player audio').mediaelementplayer();
		
		/*
			player.pause();
			player.setSrc('mynewfile.mp4');
		*/
	});
	jQuery("#soundcloud_media_link").click(function(){
		console.log("loadMedia");
		var audioCode = jQuery(".wp-audio-shortcode .mejs-mediaelement").html();
		if(jQuery("#soundcloud_link").length > 0){
			console.log("soundcloud");
			audioCode = jQuery("#soundcloud_link").html();
		}
		var title = jQuery("h1.entry-title").html();
		var url = window.location.pathname; 
		var link = '<label><a href="'+url+'">'+title+'</a></label>';
		console.dir(audioCode);
		jQuery("#footer_media_player").html(link+audioCode);
		jQuery("#footer_media_player").addClass("loaded");
		  
		
		var player = new MediaElementPlayer('#footer_media_player audio');
		player.play();
		
		//$('#footer_media_player audio').mediaelementplayer().load().play();
		//$('#footer_media_player audio').mediaelementplayer();
		
		/*
			player.pause();
			player.setSrc('mynewfile.mp4');
		*/
	});
}


var e = $('<div></div>');
jQuery('.site-tagline').append(e);    
e.attr('id', 'footer_media_player');


loadAudioPlayer();

/*
jQuery("#footer_media_link").click(function(){
	var audioCode = jQuery(".wp-audio-shortcode .mejs-mediaelement").html();
	var title = jQuery("h1.entry-title").html();
	var url = window.location.pathname; 
	var link = '<label><a href="'+url+'">'+title+'</a></label>';
	console.dir(audioCode);
	jQuery("#footer_media_player").html(link+audioCode);
	
	$('#footer_media_player audio').mediaelementplayer().load();
});
*/
