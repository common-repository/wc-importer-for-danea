<?php
/*
WOOCOMMERCE IMPORTER FOR DANEA | ADMIN FUNCTIONS
*/


add_action( 'admin_init', 'wcifd_register_style' );
add_action( 'admin_menu', 'wcifd_add_menu' );

add_action( 'admin_init', 'wcifd_register_js_menu' );
add_action( 'admin_menu', 'wcifd_js_menu' );


//CREATE WCIFD STYLE
function wcifd_register_style() {
	wp_register_style( 'wcifd-style', plugins_url('css/wc-importer-for-danea.css', 'wc-importer-for-danea/css'));
}

function wcifd_add_style() {
	wp_enqueue_style( 'wcifd-style');
}


//CALL THE MENU NAVIGATION SCRIPT
function wcifd_register_js_menu() {
	wp_register_script('wcifd-admin-nav', plugins_url('js/wcifd-admin-nav.js', 'wc-importer-for-danea/js'), array('jquery'), '1.0', true );
}

function wcifd_js_menu() {
	wp_enqueue_script('wcifd-admin-nav');
}


//MENU
function wcifd_add_menu() {
	$wcifd_page = add_submenu_page( 'woocommerce','WCIFD Options', 'WC Importer for Danea', 'manage_options', 'wc-importer-for-danea', 'wcifd_options');
	
	//Richiamo lo style per wcifd
	add_action( 'admin_print_styles-' . $wcifd_page, 'wcifd_add_style' );
	//Richiamo lo script per wcifd
	add_action( 'admin_print_scripts-' . $wcifd_page, 'wcifd_js_menu');
	
	return $wcifd_page;
}


//OPTIONS PAGE
function wcifd_options() {
	
	//CAN YOU DO THAT?
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'It seems like you don\'t have permission to see this page', 'wcifd' ) );
	}

	//START PAGE TEMPLATE
	echo '<div class="wrap">'; 
	echo '<div class="wrap-left">';
	
	//IS WOOCOMMERCE INSTALLED?
	if ( !class_exists( 'WooCommerce' ) ) { ?>

		<div id="message" class="error"><p><strong>
			<?php echo __('ATTENTION! It seems like Woocommerce is not installed.', 'wcifd' ); ?>
		</strong></p></div>

	<?php exit; 
	} ?>	

	<div id="wcifd-generale">
	<?php
		//HEADER
		echo "<h1 class=\"wcifd main\">" . __( 'Woocommmerce Importer for Danea', 'wcifd' ) . "<span style=\"font-size:60%;\"> 0.9.0</span></h1>";
	?>
	</div>
	        
	<div class="icon32 icon32-woocommerce-settings" id="icon-woocommerce"><br /></div>
	  <h2 id="wcifd-admin-menu" class="nav-tab-wrapper woo-nav-tab-wrapper">
        <a href="#" data-link="wcifd-suppliers" class="nav-tab" onclick="return false;"><?php echo __('Suppliers', 'wcifd'); ?></a>
        <div class="nav-tab not-available" onclick="return false;"><?php echo __('Clients', 'wcifd'); ?></div>    
        <div class="nav-tab not-available" onclick="return false;"><?php echo __('Products', 'wcifd'); ?></div>
        <!-- <a href="#" data-link="wcifd-ordini" class="nav-tab" onclick="return false;"><?php //echo __('Orders', 'wcifd'); ?></a>                                         -->
	  </h2>
      
      
<!-- IMPORT SUPPLIERS AS WORDPRESS USERS -->     
      
    <div id="wcifd-suppliers" class="wcifd-admin">

		<?php 
			global $wp_roles;
			$roles = $wp_roles->get_names();   
			$users_val = (sanitize_text_field($_POST['wcifd-users'])) ? sanitize_text_field($_POST['wcifd-users']) : get_option('wcifd-suppliers-role');
		?>

	    <!--Form Fornitori-->
	    <form name="wcifd-suppliers-import" id="wcifd-suppliers-import" class="wcifd-form"  method="post" enctype="multipart/form-data" action="">

			<table class="form-table">
				<tr>
					<th scope="row"><?php _e("User role", 'wcifd' ); ?></th>
					<td>
					<select class="wcifd-users-suppliers" name="wcifd-users-suppliers">
						<?php
						if($users_val) {
							echo '<option value=" ' .  $users_val . ' " selected="selected"> ' . $users_val . '</option>';	
							foreach ($roles as $key => $value) {
								if($key != $users_val) {
									echo '<option value=" ' .  $key . ' "> ' . $key . '</option>';
								}
							}
						} else {
							echo '<option value="Subscriber" selected="selected">Subscriber</option>';	
							foreach ($roles as $key => $value) {
								if($key != 'Subscriber') {
									echo '<option value=" ' .  $key . ' "> ' . $key . '</option>';
								}
							}
						} 
						?>
					</select>
					<p class="description"><?php _e('Select a Wordpress user role for your suppliers.', 'wcifd'); ?></p>
				</tr>

				<?php wp_nonce_field( 'wcifd-suppliers-import', 'wcifd-suppliers-nonce'); ?>
				<input type="hidden" name="suppliers-import" value="1">

				<tr>
					<th scope="row"><?php _e('Add suppliers', 'wcifd'); ?></th>
					<td>
						<input type="file" name="suppliers-list">
						<p class="description"><?php _e('Select your suppliers list file (.csv)', 'wcifd'); ?></p>
					</td>
				</tr>

			</table>

			<input type="submit" class="button-primary" value="<?php _e('Import Suppliers', 'wcifd'); ?>">

	    </form>

	    <?php wcifd_users('suppliers'); ?>
	 
	</div>

    </div><!--WRAP-LEFT-->
	
	<div class="wrap-right">
		<iframe width="300" height="800" scrolling="no" src="http://www.ilghera.com/images/wcifd-iframe.html"></iframe>
	</div>

	<div class="clear"></div>
    
 </div><!--WRAP-->
	
    
    <?php
    
}