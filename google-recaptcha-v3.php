/**
 * HTML
 */
<input type="hidden" name="recaptcha-response" id="recaptcha-response" />

// Include the reCAPTCHA branding
This site is protected by reCAPTCHA and the Google <a href="https://policies.google.com/privacy" target="_blank">Privacy Policy</a> and <a href="https://policies.google.com/terms" target="_blank">Terms of Service</a> apply.

/**
 * CSS
 */
/* Hide the reCAPTCHA badge */
.grecaptcha-badge { display: none; }

/**
 * JavaScript
 */
<script src="https://www.google.com/recaptcha/api.js?render=RECAPTCHA_SITE_KEY"></script>
<script>
grecaptcha.ready(function() {
  grecaptcha.execute('RECAPTCHA_SITE_KEY', {action: 'submit'}).then(function(token) {
  	var recaptcha_response = document.getElementById('recaptcha-response');
  	recaptcha_response.value = token;
  });
});
</script>

/**
 * PHP
 */
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha-response'])) {
	$recaptcha_verify_url = 'https://www.google.com/recaptcha/api/siteverify';
	$recaptcha_secret_key = 'RECAPTCHA_SECRET_KEY';
	$recaptcha_response = $_POST['recaptcha-response'];

	$recaptcha_json = file_get_contents($recaptcha_verify_url . '?secret=' . $recaptcha_secret_key . '&response=' . $recaptcha_response);

	// header('content-type: application/json');
	// echo $recaptcha_json;

	$recaptcha_object = json_decode($recaptcha_json);
	
	if (!$recaptcha_object->success) {
	    return;
	}
}
