<?php
/*
WOOCOMMERCE IMPORTER FOR DANEA | FUNCTIONS
*/


//NO DIRECT ACCESS
if ( !defined( 'ABSPATH' ) ) exit;


//IMPORT DANEA CONTACTS AS WORDPRESS USERS 
function wcifd_users($type) {

	if($_POST[$type . '-import'] && wp_verify_nonce( $_POST['wcifd-' . $type . '-nonce'], 'wcifd-' . $type . '-import' )) {

		if(isset($_POST['wcifd-users-' . $type])) {
			$role = sanitize_text_field($_POST['wcifd-users-' . $type]);
			update_option('wcifd-' . $type . '-role', $role);
		}
	
		$file = $_FILES[$type . '-list']['tmp_name'];
		$rows = array_map('str_getcsv', file($file));
		$header = array_shift($rows);
		$users = array();
		foreach ($rows as $row) {
			// var_dump($row);
		    $users[] = array_combine($header, $row);
		}
		
		$i = 0;
		foreach ($users as $user) {
			$user_name = strtolower(str_replace(' ', '-', $user['Denominazione']));
			$name = explode(' ', $user['Denominazione']);
			$address = $user['Indirizzo'];
			$cap = $user['Cap'];
			$city = $user['Città'];
			$state = $user['Prov.'];
			$country = $user['Nazione'];
			$tel = $user['Tel.'];
			$email = $user['e-mail'];
			$fiscal_code = $user['Codice fiscale'];
			$p_iva = $user['Partita Iva'];
			$description = $user['Note'];

			$user_id = username_exists( $user_name );
			if ( !$user_id and email_exists($email) == false ) {
				$i++;
				$random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );
				$userdata = array(
					'role' => $role,
					'user_login'   => $user_name,
					'first_name'   => $name[0],
					'last_name'    => $name[1],
					'display_name' => $user['Denominazione'],
					'user_email'   => $email,
					'description'  => $description
				);

				$user_id = wp_insert_user($userdata);

				//USER META
				add_user_meta($user_id, 'billing_first_name', $name[0]);
				add_user_meta($user_id, 'billing_last_name', $name[1]);
				add_user_meta($user_id, 'billing_address_1', $address);
				add_user_meta($user_id, 'billing_city', $city);
				add_user_meta($user_id, 'billing_postcode', $cap);
				add_user_meta($user_id, 'billing_state', $state);
				add_user_meta($user_id, 'billing_country', $country);
				add_user_meta($user_id, 'billing_phone', $tel);
				add_user_meta($user_id, 'billing_email', $email);
				add_user_meta($user_id, 'wcifd_fiscal_code', $fiscal_code);
				add_user_meta($user_id, 'wcifd_p_iva', $p_iva);
			} 
		}

		$output  = '<div id="message" class="updated"><p>';
		$output .= '<strong>Woocommerce Importer for Danea</strong><br>';
		$output .= sprintf( __( 'Imported %d of %d contacts', 'wcifd' ), $i, count($users) );
	    $output .= '</p></div>';
	    echo $output;
	}

}


