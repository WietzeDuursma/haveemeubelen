<?php
global $wpdb, $post;
		
$required 		= ( isset( $_REQUEST['_vfb-required-secret'] ) && $_REQUEST['_vfb-required-secret'] == '0' ) ? false : true;
$secret_field 	= ( isset( $_REQUEST['_vfb-secret'] ) ) ? $_REQUEST['_vfb-secret'] : '';
$honeypot 		= ( isset( $_REQUEST['vfb-spam'] ) ) ? $_REQUEST['vfb-spam'] : '';

// If the verification is set to required, run validation check
if ( true == $required && !empty( $secret_field ) ) {
	if ( !empty( $honeypot ) )
		wp_die( __( 'Security check: hidden spam field should be blank.' , 'visual-form-builder'), '', array( 'back_link' => true ) );
	if ( !is_numeric( $_REQUEST[ $secret_field ] ) || strlen( $_REQUEST[ $secret_field ] ) !== 2 )
		wp_die( __( 'Security check: failed secret question. Please try again!' , 'visual-form-builder'), '', array( 'back_link' => true ) );
}
		
// Basic security check before moving any further
if ( isset( $_REQUEST['visual-form-builder-submit'] ) ) :
	
	// Test if it's a known SPAM bot
	if ( $this->isBot() )
		wp_die( __( 'Security check: looks like you are a SPAM bot. If you think this is an error, please email the site owner.' , 'visual-form-builder' ), '', array( 'back_link' => true ) );
	
	// Set submitted action to display success message
	$this->submitted = true;
	
	// Tells us which form to get from the database
	$form_id = absint( $_REQUEST['form_id'] );
	
	// Query to get all forms
	$order = sanitize_sql_orderby( 'form_id DESC' );
	$forms = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $this->form_table_name WHERE form_id = %d ORDER BY $order", $form_id ) );
	
	// Get sender and email details
	foreach ( $forms as $form ) {
		$form_settings = (object) array(
			'form_title' 					=> stripslashes( html_entity_decode( $form->form_title, ENT_QUOTES, 'UTF-8' ) ),
			'form_subject' 					=> stripslashes( html_entity_decode( $form->form_email_subject, ENT_QUOTES, 'UTF-8' ) ),
			'form_to' 						=> ( is_array( unserialize( $form->form_email_to ) ) ) ? unserialize( $form->form_email_to ) : explode( ',', unserialize( $form->form_email_to ) ),
			'form_from' 					=> stripslashes( $form->form_email_from ),
			'form_from_name' 				=> stripslashes( $form->form_email_from_name ),
			'form_notification_setting' 	=> stripslashes( $form->form_notification_setting ),
			'form_notification_email_name' 	=> stripslashes( $form->form_notification_email_name ),
			'form_notification_email_from' 	=> stripslashes( $form->form_notification_email_from ),
			'form_notification_subject' 	=> stripslashes( html_entity_decode( $form->form_notification_subject, ENT_QUOTES, 'UTF-8' ) ),
			'form_notification_message' 	=> stripslashes( $form->form_notification_message ),
			'form_notification_entry' 		=> stripslashes( $form->form_notification_entry )
		);
		// Allow the form settings to be filtered (ex: return $form_settings->'form_title' = 'Hello World';)				
		$form_settings = (object) apply_filters_ref_array( 'vfb_email_form_settings', array( $form_settings, $form_id ) );
	}
	
	// Sender name field ID
	$sender = $wpdb->get_var( $wpdb->prepare( "SELECT form_email_from_name_override FROM $this->form_table_name WHERE form_id = %d", $form_id ) );
	
	// Sender email field ID
	$email = $wpdb->get_var( $wpdb->prepare( "SELECT form_email_from_override FROM $this->form_table_name WHERE form_id = %d", $form_id ) );
	
	// Notifcation email field ID
	$notify = $wpdb->get_var( $wpdb->prepare( "SELECT form_notification_email FROM $this->form_table_name WHERE form_id = %d", $form_id ) );

	$reply_to_name	= $form_settings->form_from_name;
	$reply_to_email	= $form_settings->form_from;
	
	// Use field for sender name
	if ( !empty( $sender ) ) {
		$form_settings->form_from_name = wp_kses_data( $_POST[ 'vfb-' . $sender ] );
		$reply_to_name = $form_settings->form_from_name;
	}
	
	// Use field for sender email	
	if ( !empty( $email ) ) {
		$form_settings->form_from = sanitize_email( $_POST[ 'vfb-' . $email ] );
		$reply_to_email = $form_settings->form_from;
	}
	
	// Use field for copy email	
	$copy_email = ( !empty( $notify ) ) ? sanitize_email( $_POST[ 'vfb-' . $notify ] ) : '';

	// Query to get all forms
	$order = sanitize_sql_orderby( 'field_sequence ASC' );
	$fields = $wpdb->get_results( $wpdb->prepare( "SELECT field_id, field_key, field_name, field_type, field_options, field_parent, field_required FROM $this->field_table_name WHERE form_id = %d ORDER BY $order", $form_id ) );
	
	// Setup counter for alt rows
	$i = $points = 0;
	
	// Setup HTML email vars
	$header = $body = $message = $footer = $html_email = $auto_response_email = $attachments = '';
	
	// Prepare the beginning of the content
	$header = '<html>
				<head>
				<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
				<title>HTML Email</title>
				</head>
				<body><table rules="all" style="border-color: #666;" cellpadding="10">' . "\n";
	
	// Loop through each form field and build the body of the message
	foreach ( $fields as $field ) {
		// Handle attachments
		if ( $field->field_type == 'file-upload' ) {
			$value = ( isset( $_FILES[ 'vfb-' . $field->field_id ] ) ) ? $_FILES[ 'vfb-' . $field->field_id ] : '';
			
			if ( $value['size'] > 0 ) {
				// 25MB is the max size allowed
				$size = apply_filters( 'vfb_max_file_size', 25 );
				$max_attach_size = $size * 1048576;
				
				// Display error if file size has been exceeded
				if ( $value['size'] > $max_attach_size )
					wp_die( sprintf( __( "File size exceeds %dMB. Most email providers will reject emails with attachments larger than %dMB. Please decrease the file size and try again.", 'visual-form-builder' ), $size ), '', array( 'back_link' => true ) );
				
				// Options array for the wp_handle_upload function. 'test_form' => false
				$upload_overrides = array( 'test_form' => false ); 
				
				// We need to include the file that runs the wp_handle_upload function
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				
				// Handle the upload using WP's wp_handle_upload function. Takes the posted file and an options array
				$uploaded_file = wp_handle_upload( $value, $upload_overrides );
				
				// If the wp_handle_upload call returned a local path for the image
				if ( isset( $uploaded_file['file'] ) ) {
					// Retrieve the file type from the file name. Returns an array with extension and mime type
					$wp_filetype = wp_check_filetype( basename( $uploaded_file['file'] ), null );
					
					// Return the current upload directory location
						$wp_upload_dir = wp_upload_dir();
					
					$media_upload = array(
						'guid' 				=> $wp_upload_dir['baseurl'] . _wp_relative_upload_path( $uploaded_file['file'] ), 
						'post_mime_type' 	=> $wp_filetype['type'],
						'post_title' 		=> preg_replace( '/\.[^.]+$/', '', basename( $uploaded_file['file'] ) ),
						'post_content' 		=> '',
						'post_status' 		=> 'inherit'
					);
					
					// Insert attachment into Media Library and get attachment ID
					$attach_id = wp_insert_attachment( $media_upload, $uploaded_file['file'] );
					
					// Include the file that runs wp_generate_attachment_metadata()
					require_once( ABSPATH . 'wp-admin/includes/image.php' );
					
					// Setup attachment metadata
					$attach_data = wp_generate_attachment_metadata( $attach_id, $uploaded_file['file'] );
					
					// Update the attachment metadata
					wp_update_attachment_metadata( $attach_id, $attach_data );
					
					$attachments[ 'vfb-' . $field->field_id ] = $uploaded_file['file'];

					$data[] = array(
						'id' 		=> $field->field_id,
						'slug' 		=> $field->field_key,
						'name' 		=> $field->field_name,
						'type' 		=> $field->field_type,
						'options' 	=> $field->field_options,
						'parent_id' => $field->field_parent,
						'value' 	=> $uploaded_file['url']
					);
					
					$body .= '<tr><td><strong>' . stripslashes( $field->field_name ) . ': </strong></td><td><a href="' . $uploaded_file['url'] . '">' . $uploaded_file['url'] . '</a></td></tr>' . "\n";
				}
			}
			else {
				$value = ( isset( $_POST[ 'vfb-' . $field->field_id ] ) ) ? $_POST[ 'vfb-' . $field->field_id ] : '';
				$body .= '<tr><td><strong>' . stripslashes( $field->field_name ) . ': </strong></td><td>' . $value . '</td></tr>' . "\n";
			}
		}
		// Everything else
		else {
			$value = ( isset( $_POST[ 'vfb-' . $field->field_id ] ) ) ? $_POST[ 'vfb-' . $field->field_id ] : '';
			
			// If time field, build proper output
			if ( is_array( $value ) && $field->field_type == 'time' )
				$value = ( array_key_exists( 'ampm', $value ) ) ? substr_replace( implode( ':', $value ), ' ', 5, 1 ) : implode( ':', $value );
			// If address field, build proper output
			elseif ( is_array( $value ) && $field->field_type == 'address' ) {
				$address = '';
				
				if ( !empty( $value['address'] ) )
					$address .= $value['address'];
				
				if ( !empty( $value['address-2'] ) ) {
					if ( !empty( $address ) )
						$address .= '<br>';
					$address .= $value['address-2'];
				}
				
				if ( !empty( $value['city'] ) ) {
					if ( !empty( $address ) )
						$address .= '<br>';
					$address .= $value['city'];
				}
				if ( !empty( $value['state'] ) ) {
					if ( !empty( $address ) && empty( $value['city'] ) )
						$address .= '<br>';
					elseif ( !empty( $address ) && !empty( $value['city'] ) )
						$address .= ', ';
					$address .= $value['state'];
				}
				if ( !empty( $value['zip'] ) ) {
					if ( !empty( $address ) && ( empty( $value['city'] ) && empty( $value['state'] ) ) )
						$address .= '<br>';
					elseif ( !empty( $address ) && ( !empty( $value['city'] ) || !empty( $value['state'] ) ) )
						$address .= ' ';
					$address .= $value['zip'];
				}
				if ( !empty( $value['country'] ) ) {
					if ( !empty( $address ) )
						$address .= '<br>';
					$address .= $value['country'];
				}
				
				$value = html_entity_decode( stripslashes( esc_html( $address ) ), ENT_QUOTES, 'UTF-8' );
			}
			// If multiple values, build the list
			elseif ( is_array( $value ) )
				$value = esc_html( implode( ', ', $value ) );
			// Lastly, handle single values
			else
				$value = html_entity_decode( stripslashes( esc_html( $value ) ), ENT_QUOTES, 'UTF-8' );
			
			// Setup spam catcher RegEx
			$exploits 	= '/(content-type|bcc:|cc:|document.cookie|onclick|onload|javascript|alert)/i';
			$profanity 	= '/(beastial|bestial|blowjob|clit|cock|cum|cunilingus|cunillingus|cunnilingus|cunt|ejaculate|fag|felatio|fellatio|fuck|fuk|fuks|gangbang|gangbanged|gangbangs|hotsex|jism|jiz|kock|kondum|kum|kunilingus|orgasim|orgasims|orgasm|orgasms|phonesex|phuk|phuq|porn|pussies|pussy|spunk|xxx)/i';
			$spamwords 	= '/(viagra|phentermine|tramadol|adipex|advai|alprazolam|ambien|ambian|amoxicillin|antivert|blackjack|backgammon|texas|holdem|poker|carisoprodol|ciara|ciprofloxacin|debt|dating|porn)/i';
			
			// Add up points for each spam hit
			if ( preg_match( $exploits, $value ) )
				$points += 2;
			elseif ( preg_match( $profanity, $value ) )
				$points += 1;
			elseif ( preg_match( $spamwords, $value ) )
				$points += 1;
			
			//Sanitize input
			$value = $this->sanitize_input( $value, $field->field_type );
			// Validate input
			$this->validate_input( $value, $field->field_name, $field->field_type, $field->field_required );
			
			if ( ! in_array( $field->field_type, array( 'verification', 'secret', 'submit' ) ) ) {
				if ( $field->field_type == 'fieldset' )
					$body .= '<tr style="background-color:#393E40;color:white;font-size:14px;"><td colspan="2">' . stripslashes( $field->field_name ) . '</td></tr>' . "\n";
				elseif ( $field->field_type == 'section' )
					$body .= '<tr style="background-color:#6E7273;color:white;font-size:14px;"><td colspan="2">' . stripslashes( $field->field_name ) . '</td></tr>' . "\n";
				else {
					// Convert new lines to break tags for textarea in html
					$display_value = ( 'textarea' == $field->field_type ) ? nl2br( $value ) : $value;
					
					$body .= '<tr><td><strong>' . stripslashes( $field->field_name ) . ': </strong></td><td>' . $display_value . '</td></tr>' . "\n";
				}
			}
		
			$data[] = array(
				'id' 		=> $field->field_id,
				'slug' 		=> $field->field_key,
				'name' 		=> $field->field_name,
				'type' 		=> $field->field_type,
				'options' 	=> $field->field_options,
				'parent_id' => $field->field_parent,
				'value' 	=> esc_html( $value )
			);
		}
		
		// If the user accumulates more than 4 points, it might be spam
		if ( $points > 4 )
			wp_die( __( 'Your responses look too much like spam and could not be sent at this time.', 'visual-form-builder' ), '', array( 'back_link' => true ) );
	}
	
	// Setup our entries data
	$entry = array(
		'form_id' 			=> $form_id,
		'data' 				=> serialize( $data ),
		'subject' 			=> $form_settings->form_subject,
		'sender_name' 		=> $form_settings->form_from_name,
		'sender_email' 		=> $form_settings->form_from,
		'emails_to' 		=> serialize( $form_settings->form_to ),
		'date_submitted' 	=> date_i18n( 'Y-m-d G:i:s' ),
		'ip_address' 		=> esc_html( $_SERVER['REMOTE_ADDR'] )
	);
	
	// Insert this data into the entries table
	$wpdb->insert( $this->entries_table_name, $entry );

	// Close out the content
	$footer .= '<tr><td class="footer" height="61" align="left" valign="middle" colspan="2"><p style="font-size: 12px; font-weight: normal; margin: 0; line-height: 16px; padding: 0;">This email was built and sent using <a href="http://wordpress.org/extend/plugins/visual-form-builder/" style="font-size: 12px;">Visual Form Builder</a>.</p></td></tr></table></body></html>' . "\n";
	
	// Build complete HTML email
	$message = $header . $body . $footer;
	
	// Wrap lines longer than 70 words to meet email standards
	$message = wordwrap( $message, 70 );
	
	// Decode HTML for message so it outputs properly
	$notify_message = ( $form_settings->form_notification_message !== '' ) ? html_entity_decode( $form_settings->form_notification_message ) : '';
	
	// Initialize header filter vars
	$header_from_name  		= stripslashes( $reply_to_name );
	$header_from       		= $reply_to_email;
	$header_content_type 	= 'text/html';
	
	// Either prepend the notification message to the submitted entry, or send by itself				
	if ( $form_settings->form_notification_entry !== '' )
		$auto_response_email = $header . $notify_message . $body . $footer;
	else
		$auto_response_email = $header . '<table cellspacing="0" border="0" cellpadding="0" width="100%"><tr><td colspan="2" class="mainbar" align="left" valign="top" width="600">' . $notify_message . '</td></tr>' . $footer;
	
	
	// Build email headers			
	$from_name = ( $header_from_name == '' ) ? 'WordPress' : $header_from_name;
	
	// Use the admin_email as the From email
	$from_email = get_site_option( 'admin_email' );
	
	// Get the site domain and get rid of www.
	$sitename = strtolower( $_SERVER['SERVER_NAME'] );
	if ( substr( $sitename, 0, 4 ) == 'www.' )
		$sitename = substr( $sitename, 4 );
	
	// Get the domain from the admin_email
	list( $user, $domain ) = explode( '@', $from_email );
	
	// If site domain and admin_email domain match, use admin_email, otherwise a same domain email must be created
	$from_email = ( $sitename == $domain ) ? $from_email : "wordpress@$sitename";
	
	$reply_to 	= "\"$header_from_name\" <$header_from>";
	$headers = "Sender: $from_email\r\n" . "From: $reply_to\r\n" . "Reply-To: $reply_to\r\n" . "Content-Type: $header_content_type; charset=\"" . get_option('blog_charset') . "\"\r\n";
	
	// Send the mail
	foreach ( $form_settings->form_to as $email ) {
		wp_mail( $email, wp_specialchars_decode( $form_settings->form_subject, ENT_QUOTES ), $message, $headers, $attachments );
	}
	
	// Send auto-responder email
	if ( $form_settings->form_notification_setting !== '' ) :
		
		$attachments = ( $form_settings->form_notification_entry !== '' ) ? $attachments : '';
		
		// Reset headers for notification email
		$reply_name 	= stripslashes( $form_settings->form_notification_email_name );
		$reply_email 	= $form_settings->form_notification_email_from;
		$reply_to 		= "\"$reply_name\" <$reply_email>";
		$headers = "Sender: $from_email\r\n" . "From: $reply_to\r\n" . "Reply-To: $reply_to\r\n" . "Content-Type: $header_content_type; charset=\"" . get_option('blog_charset') . "\"\r\n";
		
		// Send the mail
		wp_mail( $copy_email, wp_specialchars_decode( $form_settings->form_notification_subject, ENT_QUOTES ), $auto_response_email, $headers, $attachments );
		
	endif;
	
endif;
?>