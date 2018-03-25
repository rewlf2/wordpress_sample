(function( $ ) {
	$(window).load(function () {
		
		jQuery( "#gpctts_dialog" ).dialog({
			modal: true, title: 'Subscribe Now', zIndex: 10000, autoOpen: true,
			width: '500', resizable: false,
			position: {my: "center", at:"center", of: window },
			dialogClass: 'dialogButtons',
			buttons: {
				Yes: function () {
					// $(obj).removeAttr('onclick');
					// $(obj).parents('.Parent').remove();
					var email_id = jQuery('#txt_user_sub_gpctts').val();

					var data = {
					'action': 'add_plugin_user_gpctts',
					'email_id': email_id
					};

					// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
					jQuery.post(ajaxurl, data, function(response) {
						jQuery('#gpctts_dialog').html('<h2>You have been successfully subscribed');
						jQuery(".ui-dialog-buttonpane").remove();
					});

					
				},
				No: function () {
						var email_id = jQuery('#txt_user_sub_gpctts').val();

					var data = {
					'action': 'hide_subscribe_gpctts',
					'email_id': email_id
					};

					// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
					jQuery.post(ajaxurl, data, function(response) {
													
					});
					
					jQuery(this).dialog("close");
					
				}
			},
			close: function (event, ui) {
				jQuery(this).remove();
			}
		});
		
		jQuery("div.dialogButtons .ui-dialog-buttonset button").removeClass('ui-state-default');
		jQuery("div.dialogButtons .ui-dialog-buttonset button").addClass("button-primary woocommerce-save-button");
		jQuery("div.dialogButtons .ui-dialog-buttonpane .ui-button").css("width","80px");
		
		
		$(document).on('change','.shortcode_main',function() {
		
			var selected_name = $(this).val();
			var data = {
			'action': 'get_categories',
			'select1': selected_name
			};
		
			jQuery.post(ajaxurl, data, function(response) {
				
				$('.shortcode_main_div tr:nth-child(2),.shortcode_main_div tr:nth-child(3)').remove();	
				if(response){
					$('.shortcode_main_div table tbody.select-option').append(response);
					$('.form-table.display-view').css("display","block");
				}
			});
			
		});
		
		$(document).on('change','.select_textonomy',function() {
			
			var selected_term = $(this).val();
			//alert(selected_term);
			if(selected_term != "select Texonomy"){
			var data = {
			'action': 'get_term',
			'select_term': selected_term
			};
		
			jQuery.post(ajaxurl, data, function(response) {
				
				$('.shortcode_main_div tr:nth-child(3)').remove();
				if(response){
					$('.shortcode_main_div table tbody.select-option').append(response);
				}
				
			});
			}else{
				$('.shortcode_main_div tr:nth-child(3)').remove();
			}
		}); 
		/******  check box check functionality  ********/
		
		$(document).on('click','.form-table .color-option input[name=post_view]',function() {	
	       if($(this).is(':checked')){
	      	 $('.form-table .color-option input[name=post_view]').removeAttr("checked");
	      	 $(this).attr("checked","checked");
	      	 $('.form-table .color-option').removeClass('selected');
	      	 $(this).parent().addClass('selected');
	    } 
	    
		});
		/*****  End check box check functionality ******/
		
		/*$(document).on('click','.get_shortcode',function() {
			var select_term_data = $('.select_term').val();
			var select_texonomy_data = $('.select_textonomy').val();
			var select_post_data = $('.shortcode_main').val();
		
			var final_data = ("[post_listing_data post_name='"+select_post_data+"' texonomy_name='"+select_texonomy_data+"' term_name='"+select_term_data+"']");
			alert(final_data);
		
		});*/
		
		var overlay = $('<div id="overlay"></div>');
		
		$(document).on('click','.close',function() {
			$('.popup').hide();
			overlay.appendTo(document.body).remove();
			return false;
		});
		$(document).on('click','.x',function() {
			$('.popup').hide();
			overlay.appendTo(document.body).remove();
			return false;
		});
		$(document).ready(function() {
			$("input:text").focus(function() { 
				$(this).select(); 
				}); 
			});
		$(document).on('click','#submit',function() {
			var select_post_data = $('.shortcode_main').val();
			if( select_post_data != 'select-post-type' ){
				var select_term_data = $('.select_term').val();
				var select_texonomy_data = $('.select_textonomy').val();
				var post_type = "post_listing_data post_name='"+select_post_data+"'"
				var display_view_data = $('.form-table .color-option input[name=post_view]:checked').attr("value");
				if( select_texonomy_data != 'select Texonomy' && select_texonomy_data != 'undefined'){
					var taxonomy = " texonomy_name='"+select_texonomy_data+"'";
					var term = " term_name='"+select_term_data+"'";
				}else{
					var taxonomy = "";
					var term = "";
				}
				if(display_view_data == 'undefined'){
					var display_view_type = "";
				}else{
					var display_view_type = " display_view='"+display_view_data+"'";
				}
				
				//var final_data = "[post_listing_data post_name='"+select_post_data+"' texonomy_name='"+select_texonomy_data+"' term_name='"+select_term_data+"' display_view='"+checked_val+"']";
				var final_data = "["+post_type+taxonomy+term+display_view_type+"]";
				$('.popup label').text(final_data);
				overlay.show();
				overlay.appendTo(document.body);
				$('.popup').show();
				return false;
			}else{
				alert("Please select Post Type");
			}
		});
  	});
})( jQuery );

