// jQuery.fn.center = function () {
// 	var $this = this.find('.cp-modal-content');
// 	this.css("top", ( ( window.outerHeight - $this.height() ) / 2 ) + jQuery(window).scrollTop() + "px");
// 	return this;
// }



jQuery(document).ready(function(){

	//	Add CSS file of this style
	var css_file = '/special_offer/special_offer.min.css';
	jQuery('head').append('<link rel="stylesheet" href="' + cp.demo_dir + css_file + '" type="text/css" />');

	jQuery("body").on("click", ".cp-title", function(e){ parent.setFocusElement('modal_title_bg_color'); e.stopPropagation(); });	

	// do the stuff to customize the element upon the action "smile_data_received"
	jQuery(this).on('smile_data_received',function(e,data){
		// data - this is an object that stores all your input information in a format - input:value

		// Common variables 
		var style 				= data.style,
			cp_submit 			= jQuery(".cp-submit"),
			cp_form_button      = jQuery(".form-button"),
			cp_modal_body		= jQuery(".cp-modal-body"),
			cp_modal			= jQuery(".cp-modal"),
			cp_modal_content	= jQuery(".cp-modal-content"),
			modal_overlay		= jQuery(".cp-overlay"),
			cp_modal_body_inner	= jQuery(".cp-modal-body-inner"),
			cp_md_overlay       = jQuery(".cp-modal-body-overlay"),
			form_with_name 		= jQuery(".cp-form-with-name"),
			form_without_name 	= jQuery(".cp-form-without-name"),
			cp_title 			= jQuery(".cp-title"),
			cp_short_description = jQuery(".cp-short-description"),
			cp_email_form		= jQuery(".cp-email-form"),
			cp_email			= jQuery(".cp-email"),
			cp_submit_container	= jQuery(".cp-submit-container"),
			cp_form_container 	= jQuery(".cp-form-container"),
			cp_description 		= jQuery(".cp-description");			 	

		// style dependent variables  	
		var modal_size					= data.modal_size,
			cp_modal_width				= data.cp_modal_width,
			modal_title 				= data.modal_title1,
			bg_color					= data.modal_bg_color,
			overlay_bg_color			= data.modal_overlay_bg_color,
			modal_title_color			= data.modal_title_color,
			tip_color					= data.tip_color,
			border_str 					= data.border,
			box_shadow_str 				= data.box_shadow,
			modal_content				= data.modal_content,
			close_txt					= data.close_txt,
			content_padding				= data.content_padding,
			modal_bg_image				= data.modal_bg_image,
			opt_bg						= data.opt_bg,
			modal_bg_image_size			= data.modal_bg_image_size,
			namefield 					= data.namefield,
			affiliate_title 			= data.affiliate_title,
			cp_google_fonts 			= data.cp_google_fonts,
			cp_name_form        		= jQuery(".cp-name-form"),
			cp_submit_container         = jQuery('.cp-submit-container'),
			image_vertical_position 	= data.image_vertical_position,
			image_horizontal_position 	= data.image_horizontal_position,
			image_size 					= data.image_size,
			image_resp_width 		  	= data.image_resp_width,
			modal_image_size			= data.modal_image_size,
			title_bg_color				= data.modal_title_bg_color,
			btn_disp_next_line 			= data.btn_disp_next_line;
 
		/**
 		 *	Add Selected Google Fonts
 		 *--------------------------------------------------------*/
		cp_get_gfonts(cp_google_fonts);	

		// add custom css 
		cp_add_custom_css(data);	

		// apply animations to modal
		cp_apply_animations(data);

		// affilate settings 
		cp_affilate_settings(data);
		cp_affilate_reinitialize(data);

		cp_tooltip_settings(data); // close button and tooltip related settings 
		cp_tooltip_reinitialize(data); // reinitialize tooltip on modal resize

		var border = generateBorderCss(border_str);		
		var box_shadow = generateBoxShadow(box_shadow_str);
		var style = '';		

		if( box_shadow.indexOf("inset") > -1 ) {
				style = border; 
				cp_modal_content.attr('style', style);
				cp_md_overlay.attr('style', box_shadow);
				cp_modal_content.css('box-shadow', 'none');
			
		} else {
				cp_md_overlay.css('box-shadow', 'none');
				style = border+';'+box_shadow; 
				cp_modal_content.attr('style', style);						
		}
		
		if( typeof content_padding !== "undefined" && content_padding !== "" ){
			if( content_padding == "1" || content_padding == 1){
				cp_modal_body.addClass('no-padding');
			} else {
				cp_modal_body.removeClass('no-padding');
			}
		}
		
		// set modal width
		cp_modal_width_settings(data);	
				
		//if(modal_title!==''){
			cp_description.attr('style', 'border-top-color: '+title_bg_color+'!important');
		//}
		cp_description.css({'background-color': title_bg_color });

		//for short description
		modal_content = htmlEntities(modal_content);
		cp_short_description.html(modal_content);
		if( modal_content !== "" && typeof modal_content !== "undefined" ){
			CKEDITOR.instances.short_desc_editor.setData(modal_content);
		}

		// setup all editors
		cp_editor_setup(data);	
		cp_form_style(data);	

		modal_overlay.css('background',overlay_bg_color);		
		
		if( !cp_modal.hasClass("cp-modal-exceed") ){
			cp_modal.attr('class', 'cp-modal '+modal_size);
		} else {
			cp_modal.attr('class', 'cp-modal cp-modal-exceed '+modal_size);
		}
			
		if( modal_title == "" ){
			jQuery(".cp-row.cp-blank-title").css('display','none');
		} else {
			jQuery(".cp-row.cp-blank-title").css('display','block');
		}

		// modal background image
		cp_bg_image(data);
		
		//input form field	
		if( namefield == 1 ){
			form_without_name.css({"display":"none"});
			form_with_name.css({"display":"block"});	
			cp_submit.removeClass('cp-button-on-same-line');
			cp_email_form.removeClass('col-xs-12 col-md-8 col-sm-8 col-lg-8').addClass('col-md-12');
			cp_submit_container.removeClass('col-xs-12 col-md-8 col-sm-8 col-lg-8').addClass('col-md-12');
			cp_submit_container.removeClass('col-md-4 col-xs-12 col-sm-4 col-lg-4').addClass('col-md-12');
			cp_submit.removeClass('cp-button-on-same-line');
			cp_email.removeClass('cp-text-align-left');
			cp_form_container.removeClass('form-with-btn-on-same-line');
			cp_submit.attr('data-font-size-init','40px');
			cp_submit.attr('data-line-height-init','50px');
		} else {
			form_without_name.css({"display":"block"});
			form_with_name.css({"display":"none"});
			if(btn_disp_next_line == 1){				
				cp_email_form.removeClass('col-xs-12 col-md-8 col-sm-8 col-lg-8').addClass('col-md-12');
				cp_submit_container.removeClass('col-xs-12 col-md-8 col-sm-8 col-lg-8').addClass('col-md-12');
				cp_submit_container.removeClass('col-md-4 col-xs-12 col-sm-4 col-lg-4').addClass('col-md-12');
				cp_submit.removeClass('cp-button-on-same-line');	
				cp_email.removeClass('cp-text-align-left');			
				cp_form_container.removeClass('form-with-btn-on-same-line');
				cp_submit.attr('data-font-size-init','40px');
				cp_submit.attr('data-line-height-init','50px');
			}else{
				cp_email_form.removeClass('col-md-12').addClass('col-xs-12 col-md-8 col-sm-8 col-lg-8');
				cp_submit_container.removeClass('col-md-12').addClass('col-md-4 col-xs-12 col-sm-4 col-lg-4');
				cp_submit.addClass('cp-button-on-same-line');
				cp_email.addClass('cp-text-align-left');
				cp_form_container.addClass('form-with-btn-on-same-line');
				cp_submit.attr('data-font-size-init','15px');
				cp_submit.attr('data-line-height-init','22px');
			}			
		}
		
		jQuery(".cp-name-form").removeClass('cp_big_name');	

		jQuery(window).resize(function(e) {						
			cp_affilate_reinitialize(data);
			cp_tooltip_reinitialize(data);	
			calwidth();
		});

		jQuery(document).ready(function(e) {
			calwidth();//function for reponsive form
		});		

		function calwidth(){
			var width = window.outerWidth;
			var vw = jQuery(window).width();
			var vh = jQuery(window).height();			
			//for responsive name and email field	
					
			if(btn_disp_next_line !== '1'){
				if(vw <= 768){
					cp_submit.removeClass('cp-button-on-same-line');

				}else{
					if(namefield == 1){
						cp_submit.removeClass('cp-button-on-same-line');
					}else{
						cp_submit.addClass('cp-button-on-same-line');
						
					}					
				}
			}else{					
					cp_submit.removeClass('cp-button-on-same-line');
				}

			
		}
		
		//cp_modal.center();
		
		// add cp-empty class to empty containers
		jQuery.each( cp_empty_classes, function( key, value) {
			if( jQuery(value).length !== 0 ) {
				cp_add_empty_class(key,value);
			}	
		});

		// blinking cursor  
		cp_blinking_cursor('.cp-title',modal_title_color);
	});
});