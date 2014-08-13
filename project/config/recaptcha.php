<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------
// Recaptcha class config
// ------------------------------------------------------------------------

// The reCaptcha server keys and API locations
// Obtain your own keys from: http://www.recaptcha.net

$config['recaptcha'] = array(
  							'public'						=> '6LeiFtoSAAAAAEKvL4qS9G0Xo5mI4vs6yvfytM3q',
  							'private'						=> '6LeiFtoSAAAAAFz17MrQUC8a4nytJUJYXiAKP4Xi',
  							'RECAPTCHA_API_SERVER' 			=> 'http://www.google.com/recaptcha/api',
  							'RECAPTCHA_API_SECURE_SERVER'	=> 'https://www.google.com/recaptcha/api',
  							'RECAPTCHA_VERIFY_SERVER' 		=> 'www.google.com',
  							'RECAPTCHA_SIGNUP_URL' 			=> 'https://www.google.com/recaptcha/admin/create',
  							'theme' 						=> 'red'
							);
