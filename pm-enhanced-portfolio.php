<?php
/**
 * PM Enhanced Portfolio
 *
 * @package     PM_Enhanced_Portfolio
 * @author      priscillamc
 * @copyright   2018 PM Digital Consulting
 * @license     GPL-2.0+
 * 
 * @todo Add filters for the title and description
 * @todo Add support for changing slug
 * @todo Add support for changing taxonomy attributes and slugs
 *
 * @wordpress-plugin
 * Plugin Name: PM Enhanced Portfolio
 * Plugin URI:  https://priscillachapman.com/
 * Description: Adds enhanced features and allows you to customize Jetpack Portfolio features
 * Version:     1.0.0
 * Author:      Priscilla Chapman (priscillamc)
 * Author URI:  https://priscillachapman.com/
 * Text Domain: pm-enhanced-portfolio
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * BuildDate: 20181226
 */
	 
add_action( 'admin_menu', 'pmep_add_admin_menu' );
add_action( 'admin_init', 'pmep_settings_init' );
add_action( 'registered_post_type', 'pmep_change_post_type', 10, 2 );

function pmep_add_admin_menu(  ) { 

	add_submenu_page( 'options-general.php', 'PM Enhanced Portfolio', 'PM Enhanced Portfolio', 'manage_options', 'pm_enhanced_portfolio', 'pmep_options_page' );

}


function pmep_settings_init(  ) { 

	register_setting( 'pmep_page', 'pmep_settings' );

	add_settings_section(
		'pmep_settings_section', 
		__( 'Portfolio Settings', 'pm-enhanced-portfolio' ), 
		'pmep_settings_section_callback', 
		'pmep_page'
	);

	add_settings_field( 
		'portfolio_title', 
		__( 'Portfolio Title', 'pm-enhanced-portfolio' ), 
		'pmep_portfolio_title_render', 
		'pmep_page', 
		'pmep_settings_section' 
	);

	add_settings_field( 
		'portfolio_description', 
		__( 'Portfolio Description', 'pm-enhanced-portfolio' ), 
		'pmep_portfolio_description_render', 
		'pmep_page', 
		'pmep_settings_section' 
	);


}

/**
 * Display the title field
 * 
 * @todo Add default title if not set
 */
function pmep_portfolio_title_render() { 

	$options = get_option( 'pmep_settings' );
	$default = get_post_type_object('jetpack-portfolio')->labels->name;
	$title = ( isset( $options['portfolio_title'] ) )? $options['portfolio_title'] : $default;
	?>
	<input type="text" name="pmep_settings[portfolio_title]" value="<?php echo $title; ?>">
	<?php

}

/**
 * Display the description field
 * 
 * @todo Add the default description if not set
 * @todo Hook into WordPress text editor
 */
function pmep_portfolio_description_render(  ) { 

	$options = get_option( 'pmep_settings' );
	$default = get_post_type_object('jetpack-portfolio')->description;
	$description = ( isset( $options['portfolio_description'] ) )? $options['portfolio_description'] : $default;
	?>
	<textarea cols="40" rows="5" name="pmep_settings[portfolio_description]"><?php echo $description; ?></textarea>
	<?php

}

/**
 * [[Description]]
 */
function pmep_settings_section_callback(  ) { 

	echo __( '', 'pm-enhanced-portfolio' );

}

/**
 * [[Description]]
 */
function pmep_options_page(  ) { 

	?>
	<form action="options.php" method="post">

		<h2>PM Enhanced Portfolio</h2>
		<p><?php echo __( '', 'pm-enhanced-portfolio' ); ?></p>

		<?php
		settings_fields( 'pmep_page' );
		do_settings_sections( 'pmep_page' );
		submit_button();
		?>
		
	</form>
	<?php
	$options = get_option( 'pmep_settings' );
	var_dump( $options );
	var_dump( get_post_type_object('jetpack-portfolio') );
}

/**
 * Changes the Jetpack Portfolio labels
 * 
 * @param  object $labels [[Description]]
 * @return object [[Description]]
 */
function pmep_change_post_type( $post_type, $post_type_object ){
	$options = get_option( 'pmep_settings' );
	
	if ( 'jetpack-portfolio' == $post_type ){
		// Replace the title
		if ( isset( $options['portfolio_title'] ) ){
			$post_type_object->labels->name = $options['portfolio_title'];
		}

		// Replace the description
		if ( isset( $options['portfolio_description'] ) ){
			$post_type_object->description = $options['portfolio_description'];
		}	
	}
}