<?php

/* doamin validation */
function my_pmpro_registration_checks_restrict_email_addresses( $value ) {
	$email = $_REQUEST['bemail'];
	if(is_null($email)){
		return $value;
	}
	if( ! my_checkForValidDomain( $email ) ) {
		global $pmpro_msg, $pmpro_msgt;
		$pmpro_msg = "You are not allowed to join";
		$pmpro_msgt = "pmpro_error";
		$value = false;
	}
	return $value;
}
add_filter( 'pmpro_registration_checks','my_pmpro_registration_checks_restrict_email_addresses', 10, 1 );

//Taken from: http://www.bitrepository.com/how-to-extract-domain-name-from-an-e-mail-address-string.html
function my_getDomainFromEmail( $email ) {
    // Get the data after the @ sign
    $domain = substr(strrchr($email, "@"), 1);

    return $domain;
}

function my_checkForValidDomain( $email ) {
	$domain = my_getDomainFromEmail( $email );

// start Repeater fild from here
/*
// i will store Repeater field data in $valid_domains array	*/
//$valid_domains = array( "yahoo.com", "*.gmail.com", "*.domain.uk" );

	$valid_domains = [];
	
	if ( have_rows('domain_list', 'option') ) :
		while( have_rows('domain_list', 'option') ) : the_row();
			$valid_domains[] = get_sub_field('email_domain');
		endwhile;
		
		$valid_domains = array_map( 'trim', $valid_domains );
	endif;

	
	foreach($valid_domains as $valid_domain) {
		$components = explode(".", $valid_domain);
		$domain_to_check = explode(".", $domain);

		if(!empty($components[0]->config == "*") && sizeof($domain_to_check->config > 2)) {
			if($components[1] == $domain_to_check[1] && $components[2] == $domain_to_check[2]) {
				return true;
			}
		} else {
			if( !( strpos($valid_domain, $domain) === false) ) {
				return true;
			}
		}
	}
	return false;
}



?>
