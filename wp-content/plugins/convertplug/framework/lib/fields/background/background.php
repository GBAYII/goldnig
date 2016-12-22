<?php
// Add new input type "background"
if ( function_exists('smile_add_input_type'))
{
	smile_add_input_type('background' , 'background_settings_field' );
}

/**
* Function to handle new input type "background"
*
* @param $settings		- settings provided when using the input type "background"
* @param $value			- holds the default / updated value
* @return string/html 	- html output generated by the function
*/
function background_settings_field($name, $settings, $value)
{
	$input_name = $name;
	$type = isset($settings['type']) ? $settings['type'] : '';
	$class = isset($settings['class']) ? $settings['class'] : '';
	$options = isset($settings['options']) ? $settings['options'] : '';
	
	$bg = explode("|", $value );
	$repeat_val = $bg[0];
	$pos_val = $bg[1];
	$size_val = $bg[2];
	
	$background_repeat = array( 
		__( "No Repeat", "smile" ) 			=> "no-repeat",
		__( "Repeat", "smile" ) 				=> "repeat",
		__( "X Repeat", "smile" ) 	=> "repeat-x",
		__( "Y Repeat", "smile" ) 	=> "repeat-y"
	);
	
	$background_position = array( 
		__( "Center", "smile" ) 	=> "center",
		__( "Left", "smile" ) 		=> "left",
		__( "Right", "smile" ) 		=> "right",
	);
	
	$background_size = array( 
		__( "Cover", "smile" ) 		=> "cover",
		__( "Contain", "smile" ) 	=> "contain",
		__( "Default", "smile" ) 	=> "auto",
	);
	
	$output = '';
	
	// Background input field
	$output = '<input type="hidden" id="smile_'.$input_name.'" class="form-control smile-input smile-'.$type.' '.$input_name.' '.$type.' '.$class.'" name="' . $input_name . '" value="'.$value.'" />';
	
	// Background Repeat
	$output .= '<strong><label for="smile_bg_repeat">'.__( "Background Repeat", "smile" ).'</label></strong>';
	$input_name = 'bg_rpt';
	$output .= '<p><select id="smile_' . $input_name . '" class="smile_' . $input_name . '" >';
	foreach( $background_repeat as $title => $val ) {
		$selected = ( $val == $repeat_val ) ? "selected='selected'" : "";
		$output .= '<option value="'.$val.'" '.$selected.'>'.$title.'</option>';
	}
	$output .= '</select></p>';
	
	// Background Position
	$output .= '<strong><label for="smile_' . $input_name . '">'.__( "Background Position", "smile" ).'</label></strong>';
	$input_name = 'bg_pos';
	$output .= '<p><select id="smile_' . $input_name . '" class="smile_' . $input_name . '">';
	foreach( $background_position as $title => $val ) {
		$selected = ( $val == $pos_val ) ? "selected='selected'" : "";
		$output .= '<option value="'.$val.'" '.$selected.'>'.$title.'</option>';
	}
	$output .= '</select></p>';

	// Background Size
	$output .= '<strong><label for="smile_' . $input_name . '">'.__( "Background Size", "smile" ).'</label></strong>';
	$input_name = 'bg_size';
	$output .= '<p><select id="smile_' . $input_name . '" class="smile_' . $input_name . '" >';
	foreach( $background_size as $title => $val ) {
		$selected = ( $val == $size_val ) ? "selected='selected'" : "";
		$output .= '<option value="'.$val.'" '.$selected.'>'.$title.'</option>';
	}
	$output .= '</select></p>';
	$output .= cp_background_script($name);
	
	return $output;
}

function cp_background_script($name){
ob_start();
?>
<script type="text/javascript">
jQuery(document).ready(function(){
	var input = jQuery("#smile_<?php echo $name; ?>");
	var parent = input.closest(".smile-element-container");

	var bg_repeat = parent.find(".smile_bg_rpt");
	var bg_pos = parent.find(".smile_bg_pos");
	var bg_size = parent.find(".smile_bg_size");
	
	var value = "";
	value = bg_repeat.val()+"|"+bg_pos.val()+"|"+bg_size.val();
	input.val(value);
	bg_repeat.on( "change", function(){
		value = jQuery(this).val()+"|"+bg_pos.val()+"|"+bg_size.val();
		input.val(value);
		input.trigger('change');
	});
	bg_pos.on( "change", function(){
		value = bg_repeat.val()+"|"+jQuery(this).val()+"|"+bg_size.val();
		input.val(value);
		input.trigger('change');
	});
	bg_size.on( "change", function(){
		value = bg_repeat.val()+"|"+bg_pos.val()+"|"+jQuery(this).val();
		input.val(value);
		input.trigger('change');
	});
});
</script>
<?php
return ob_get_clean();
}