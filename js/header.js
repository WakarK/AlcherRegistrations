function fb_init(){
	window.fbAsyncInit = function() {
		FB.init({
		  appId      : "1461710874092833",
		  xfbml      : true,
		  version    : "v2.1"
		});		
	};
	(function(d, s, id){
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) {return;}
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/en_US/sdk.js";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, "script", "facebook-jssdk"));
}
$(document).ready(function(){
	fb_init();
	$('#header_dropdown_button').click(function(){
		$('#notif_dropdown_block').hide();
		$(this).next('#header_dropdown_block').toggle();
	});
	$('.rules_drop').click(function(){
		$(this).closest('.comp_details').find('.comp_register').fadeOut(function(){
			$(this).closest('.comp_details').find('.comp_rules').slideToggle();
		});
	});
	$('.members_drop').click(function(){
		$(this).closest('.comp_details').find('.comp_rules').fadeOut(function(){
			$(this).closest('.comp_details').find('.comp_register').slideToggle();
		});
	});

	$('.module_list_block select').change(function(){
		var mod_val= $(this).val();
		$('.competitions_block').hide();
		if(mod_val==0){
			$('.competitions_block').fadeIn();
		}
		else{
			$('.competitions_block[data-module="'+mod_val+'"]').fadeIn();
		}
	});
	$('#phone_prompt_box').closest('#big_container').addClass('push_below');
});
                            