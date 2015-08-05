<?php
 
/*
Plugin Name:  WP Google Maps Auto Business Place Finder
Plugin URI: http://www.luciaintelisano.it/wp-google-maps-auto-business-place-finder
Description: A plugin to get/find business places on google maps in a very easy way
Version: 1.0.0
Author: Lucia Intelisano
Author URI: http://www.luciaintelisano.it
*/

/*  Copyright 2015  WP Google Maps Auto Business Place Finder  (email : lucia.intelisano@gmail.com) */

  	// init plugin
	wgabf_init();
	
 	 
	/* Extract text inside two tags */
	function getRow4($cnt, $tagStart, $tagEnd) {
		$start = 0;
		$end = strlen($cnt);
		if (!(strpos($cnt, $tagStart)===false)) {
			$start = strpos($cnt, $tagStart)+strlen($tagStart);
		}	
		if (!(strpos($cnt, $tagEnd)===false)) {
			$end = strpos($cnt, $tagEnd);
		}
		$row = substr($cnt, $start, $end-$start);	
		return $row;
	}
		
	function wgabf_is_shortcode() {
 
		global $post;
 
		if ( strstr( $post->post_content, '[wgabf ' ) ) {
			 return true;
		} else {
			$cats = strtolower(get_option('wgabf_view_on_cat'));
		 
			$attachok=0;
			if  ($cats!="") {
				 $arrCat = split(",",$cats);
				 $categories = get_the_category();
				 
				 if($categories){
					foreach($categories as $category) {
						 
							foreach($arrCat as $cat) {
								
								if (strtolower($category->name)==$cat) {
								
									$attachok=1;
								}
							}
					}
				}	
			}
			$tagnames = trim(strtolower(get_option('wgabf_view_on_tag')));	 
			if ($tagnames!="") {
		 
				$posttags = get_the_tags();
				if ($posttags!="") {
					 $arrTags = split(",",$tagnames);
				  foreach($posttags as $tagpost) {
						foreach($arrTags as $tagname) {
							if (trim(strtolower($tagpost->name))==$tagname) {
									$attachok=1;
							}	 
						}
				  }
				}
			}	
			 
			if ($attachok==1) {
				return true;
			}
		
		}
		return false;
	}	
	
	
	/**
 		* Function for adding header style sheets and js
 	*/
	function wgabf_theme_name_scripts() {	 
		if (wgabf_is_shortcode())  {
			wp_enqueue_style('default_style_wpmhf_1', plugins_url('css/stylemapwgabf.css', __FILE__), false, time());
			wp_enqueue_script('default_scripts_wpmhf_1', "https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places,geometry", array(), '', false );
			wp_enqueue_script('default_scripts_wpmhf_2', plugins_url('js/scriptmapwgabf.js', __FILE__), array(), time(), true );
		}	
	} 
		
		
	/**
 	* Function for adding a link on main menu of wp
 	*/	
	function wgabf_plugin_setup_menu(){
       $hookPage = add_options_page('WP Google Auto Business Finder', 'WP Google Auto Business Finder', 'administrator', __FILE__, 'wgabf_settings_page',plugins_url('/images/icon.png', __FILE__));
		//add_action('load-'.$hookPage ,'do_on_my_plugin_settings_save');
	}
 
 
	
	/**
 	* Function for init plugin
 	*/
	function wgabf_init(){
		 
	 
	 		add_action('admin_menu', 'wgabf_plugin_setup_menu');
	 		add_action( 'admin_init', 'wgabf_register_mysettings' );  
	 		add_action('media_buttons', 'wgabf_add_my_media_button');
	 	 
	 
	 	
 
	 		add_action( 'wp_enqueue_scripts', 'wgabf_theme_name_scripts' ); 
	 		add_filter( 'the_content', 'wgabf_my_the_post_action' );
			add_shortcode('wgabf', 'wgabf_createMap');
		 
	}	
		
		
	/**
 * Function for creation map
 */	
	function wgabf_createMap($atts) {
			$atts = shortcode_atts( array(
				'title' => '',
				'lat' => '',
				'lng' => '',
				'location' => '',
				'searchform' => true
			), $atts, 'wmhf' );			
			$dir = plugin_dir_path( __FILE__ );
			
			$cnt = file_get_contents($dir."template/map.html");
			$cnt = str_replace('{URL_PLUGIN}', plugin_dir_url( __FILE__ ),$cnt);
			$cnt = str_replace('{TITLE}',strip_tags($atts["title"]),$cnt);
			$cnt = str_replace('{LAT}',$atts["lat"],$cnt);
			$cnt = str_replace('{LNG}',$atts["lng"],$cnt);
			$cnt = str_replace('{LOCATION}',strip_tags($atts["location"]),$cnt);
			$cnt = str_replace('{IMGDEFAULT}',plugins_url('img/pin.png', __FILE__),$cnt);
			$cnt = str_replace('{IMG1}',plugins_url('img/bgh.png', __FILE__),$cnt);
			$cnt = str_replace('{SEARCH}',get_option('wgabf_autocomplete_search'),$cnt);	
			$typesb = '';
			$arrTypes = get_option('wgabf_place_type',array());
			if (count($arrTypes)>0) {
				 foreach($arrTypes as $k => $t) {
				 	$typesb .= '"'.$t.'",';
				 }
				 $typesb = substr($typesb,0,strlen($typesb)-1);
			}
			$cnt = str_replace('{TYPESB}',$typesb,$cnt);		 
			return $cnt;
	}

	
	 
	 
 
 

	/**
 * Function for register settings
 */
function wgabf_register_mysettings() {
	register_setting( 'wgabf-settings-group', 'wgabf_place_type' );
	register_setting( 'wgabf-settings-group', 'wgabf_autocomplete_search' );
	register_setting( 'wgabf-settings-group', 'wgabf_view_on_cat' );
	register_setting( 'wgabf-settings-group', 'wgabf_view_on_tag' );
	register_setting( 'wgabf-settings-group', 'wgabf_lat' );
	register_setting( 'wgabf-settings-group', 'wgabf_default_title' );
	register_setting( 'wgabf-settings-group', 'wgabf_lng' );
	register_setting( 'wgabf-settings-group', 'wgabf_location' );
	register_setting( 'wgabf-settings-group', 'wgabf_start_html_tag' );
	register_setting( 'wgabf-settings-group', 'wgabf_end_html_tag' );
	register_setting( 'wgabf-settings-group', 'wgabf_title' );
	register_setting( 'wgabf-settings-group', 'wgabf_exclude_from_title' );
}

	/**
 * Function for view settings page 
 */
function wgabf_settings_page() {

 
?>
<div class="wrap">
<h2>WP Google Auto Business Finder</h2>

<form method="post" action="options.php">
    <?php 
    	settings_fields( 'wgabf-settings-group' );  
    	do_settings_sections( 'wgabf-settings-group' ); 
 
    ?>
    <table class="form-table" style="width:70%">
 
    	 <tr valign="top">
        <th scope="row">Select place types (multiple choises)</th>
        <td>
        <select name="wgabf_place_type[]" id="wgabf_place_type" size="20"   multiple>
        <?php
        	$arrTypes = get_option('wgabf_place_type',array());
        	if (count($arrTypes)>0) {
        		foreach($arrTypes as $k => $item) {
        			$item = trim($item);
        		  	if ($item!="") { echo '<option value="'.$item.'" selected="selected">'.$item.'<option>'; }
        		}
        	}
        ?><option>accounting</option>
<option>airport</option>
<option>amusement_park</option>
<option>aquarium</option>
<option>art_gallery</option>
<option>atm</option>
<option>bakery</option>
<option>bank</option>
<option>bar</option>
<option>beauty_salon</option>
<option>bicycle_store</option>
<option>book_store</option>
<option>bowling_alley</option>
<option>bus_station</option>
<option>cafe</option>
<option>campground</option>
<option>car_dealer</option>
<option>car_rental</option>
<option>car_repair</option>
<option>car_wash</option>
<option>casino</option>
<option>cemetery</option>
<option>church</option>
<option>city_hall</option>
<option>clothing_store</option>
<option>convenience_store</option>
<option>courthouse</option>
<option>dentist</option>
<option>department_store</option>
<option>doctor</option>
<option>electrician</option>
<option>electronics_store</option>
<option>embassy</option>
<option>establishment</option>
<option>finance</option>
<option>fire_station</option>
<option>florist</option>
<option>food</option>
<option>funeral_home</option>
<option>furniture_store</option>
<option>gas_station</option>
<option>general_contractor</option>
<option>grocery_or_supermarket</option>
<option>gym</option>
<option>hair_care</option>
<option>hardware_store</option>
<option>health</option>
<option>hindu_temple</option>
<option>home_goods_store</option>
<option>hospital</option>
<option>insurance_agency</option>
<option>jewelry_store</option>
<option>laundry</option>
<option>lawyer</option>
<option>library</option>
<option>liquor_store</option>
<option>local_government_office</option>
<option>locksmith</option>
<option>lodging</option>
<option>meal_delivery</option>
<option>meal_takeaway</option>
<option>mosque</option>
<option>movie_rental</option>
<option>movie_theater</option>
<option>moving_company</option>
<option>museum</option>
<option>night_club</option>
<option>painter</option>
<option>park</option>
<option>parking</option>
<option>pet_store</option>
<option>pharmacy</option>
<option>physiotherapist</option>
<option>place_of_worship</option>
<option>plumber</option>
<option>police</option>
<option>post_office</option>
<option>real_estate_agency</option>
<option>restaurant</option>
<option>roofing_contractor</option>
<option>rv_park</option>
<option>school</option>
<option>shoe_store</option>
<option>shopping_mall</option>
<option>spa</option>
<option>stadium</option>
<option>storage</option>
<option>store</option>
<option>subway_station</option>
<option>synagogue</option>
<option>taxi_stand</option>
<option>train_station</option>
<option>travel_agency</option>
<option>university</option>
<option>veterinary_care</option>
<option>zoo</option>
        </select>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row">Autocomplete searching input?</th>
        <td><input type="checkbox" name="wgabf_autocomplete_search" <?php checked( '1', get_option('wgabf_autocomplete_search')) ; ?> value="1" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">View on post of categories</th>
        <td><input type="text" name="wgabf_view_on_cat" value="<?php echo esc_attr( get_option('wgabf_view_on_cat') ); ?>" /> (es. cat1,cat2,...)</td>
        </tr>
        
        <tr valign="top">
        <th scope="row">View on post of tags</th>
        <td><input type="text" name="wgabf_view_on_tag" value="<?php echo esc_attr( get_option('wgabf_view_on_tag') ); ?>" /> (es. tag1,tag2,...)</td>
        </tr>
          <tr valign="top">
        <th scope="row">Default title for map</th>
        <td><input type="text" name="wgabf_default_title" value="<?php echo esc_attr( get_option('wgabf_default_title') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Default lat</th>
        <td><input type="text" name="wgabf_lat" value="<?php echo esc_attr( get_option('wgabf_lat') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Default long</th>
        <td><input type="text" name="wgabf_lng" value="<?php echo esc_attr( get_option('wgabf_lng') ); ?>" /></td>
        </tr>
         <tr valign="top">
        <th scope="row">Default location</th>
        <td><input type="text" name="wgabf_location" value="<?php echo esc_attr( get_option('wgabf_location') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Get location from html tag inside content</th>
        <td>start tag <input type="text" name="wgabf_start_html_tag" value="<?php echo esc_attr( get_option('wgabf_start_html_tag') ); ?>" />
        end tag <input type="text" name="wgabf_end_html_tag" value="<?php echo esc_attr( get_option('wgabf_end_html_tag') ); ?>" />
        
        </td>
        </tr>
        <tr valign="top">
        <th scope="row">Get location from title</th>
        <td><input type="checkbox" name="wgabf_title" <?php checked( '1', get_option('wgabf_title')) ; ?> value="1" /></td>
        </tr>
         <tr valign="top">
        <th scope="row">Exclude word from title</th>
        <td><input type="text" name="wgabf_exclude_from_title" value="<?php echo esc_attr( get_option('wgabf_exclude_from_title') ); ?>" /></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php
}


	/**
 * Function for add map on post 
 */
function wgabf_my_the_post_action( $content ) {
 
	 
	if (wgabf_is_shortcode()) {
			global $post, $wp_query;
    		$post_id = $post->ID;
    		 
    		$atts = array();
    		$atts["title"] = strip_tags(get_option('wgabf_default_title')); 
    		$lat = get_option('wgabf_lat');
    		$lng = get_option('wgabf_lng');
    		$loc =  strip_tags(get_option('wgabf_location'));
    		if ($lat=="" || $lng=="") {
    			if ($loc=="") {
    			 
    				$start_html_tag =  get_option('wgabf_start_html_tag');
    				$end_html_tag =  get_option('wgabf_end_html_tag');
    				if ($start_html_tag!="") {
    					$loc = getRow($post->post_content, $start_html_tag, $end_html_tag); 
    				}  
    				
    				if ($loc=="" && get_option('wgabf_title')==true) {
    				 		 $loc = strtolower($post->post_title);
    				}
    				$exclude = get_option('wgabf_exclude_from_title');
    				$exclude = str_replace(" ",",",$exclude);
    				$arrD = split(",",$exclude);
    				foreach($arrD as $k => $w) {
    					$w = strtolower(trim($w));
    					$loc = str_replace($w,"",$loc);
    				}	 
    			}
    		
    		}
    		$loc = str_replace("+"," ",$loc);
    		$loc = str_replace("-",",",$loc);
    		 
    		$atts["lat"] = $lat; 
    		$atts["lng"] = $lng;
    		$atts["location"] = $loc; 
			$cnt = wgabf_createMap($atts);
			$content.=$cnt;
	 
}	
	return $content;
 

	 
    
}

 function wgabf_add_my_media_button() {
    echo '<a href="javascript:wp.media.editor.insert(\'[wgabf location=&quot;&quot; lat=&quot;&quot; lng=&quot;&quot;]\');" id="insert-my-media" class="button">Add business map</a>';
}
 
 
?>
