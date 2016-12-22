<?php
// Add new input type "slider"
if ( function_exists('smile_add_input_type'))
{
	smile_add_input_type('slider' , 'slider_settings_field' );
}

add_action('admin_enqueue_scripts','smile_slider_admin_scripts');
function smile_slider_admin_scripts($hook){
	$cp_page = strpos( $hook, 'plug_page');
	$data  =  get_option( 'convert_plug_debug' );
	
	if( $cp_page == 7 ){
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-slider' );
		if( $cp_page == 7 && isset( $data['cp-dev-mode'] ) && $data['cp-dev-mode'] == '1' ){
			wp_enqueue_script( 'smile-slider', plugins_url('slider.js',__FILE__),array(),'1.0.0',true );
			wp_enqueue_style( 'smile-jquery-ui', plugins_url('jquery-ui.css',__FILE__) );
			wp_enqueue_style( 'smile-slider', plugins_url('slider.css',__FILE__) );
		}
	}
}

/**
* Function to handle new input type "slider"
*
* @param $settings		- settings provided when using the input type "slider"
* @param $value			- holds the default / updated value
* @return string/html 	- html output generated by the function
*/
function slider_settings_field($name, $settings, $value)
{
	$input_name = $name;
	$type 		= isset($settings['type']) ? $settings['type'] : '';
	$class 		= isset($settings['class']) ? $settings['class'] : '';
    $min 		= isset($settings['min']) ? $settings['min'] : '';

    //	If user set value larger than default max value then it will override and set max to user defined value.
    $max 		= isset($settings['max']) ? $settings['max'] : '';
    if( $value > $max ) {
    	$max = $value;
    }

    $step 		= isset($settings['step']) ? $settings['step'] : '';
    $suffix 	= isset($settings['suffix']) ? $settings['suffix'] : 'px';

    if( isset($settings['description']) && $settings['description'] !== '' ) 
    	$tooltipClass = 'with-tooltip';
    else
    	$tooltipClass = '';
  
  	$uid = uniqid();
	$output = '<div class="setting-block"><div class="row">';        
    $output .= '<label class="align-right slider-label '.$tooltipClass.'" for="'.$input_name.'">'.$suffix.'</label>';          
    $output .= '<div class="text-1 slider-input '.$tooltipClass.'"><input id="smile_'.$input_name.'_'.$uid.'" type="number"  step="'.$step.'" class="form-control smile-input smile-'.$type.' '.$input_name.' '.$type.' '.$class.'" name="' . $input_name . '" value="'.$value.'" data-min="'.$min.'" data-max="'.$max.'" data-step="'.$step.'"></div></div>';
    $output .= '<div id="slider_'.$input_name.'_'.$uid.'" class="slider-bar large ui-slider ui-slider-horizontal ui-widget ui-widget-content '.$input_name.' '.$type.' '.$class.'"><a class="ui-slider-handle ui-state-default" href="#"></a><span class="range-quantity" ></span></div></div>';	
	return $output;
}