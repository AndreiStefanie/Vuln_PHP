<?php
include_once 'inc/util.php';

/**
 * Store the given value at the given key
 *
 * @return void
 */
function store_in_session($key, $value)
{
	if (isset($_SESSION)){
		$_SESSION[$key] = $value;
	}
}

/**
 * Remove the tupple with the given key
 *
 * @return void
 */
function unset_session($key)
{
	$_SESSION[$key] = ' ';
	unset($_SESSION[$key]);
}

/**
 * Retrieve the value of the given key from session
 *
 * @return mixed
 */
function get_from_session($key)
{
	if (isset($_SESSION[$key])) {
		return $_SESSION[$key];
	} else { 
        return false;
    }
}

/**
 * Generate a random token for the given form
 *
 * @return string
 */
function csrfguard_generate_token($unique_form_name)
{
	$token = sanitizeString(random_bytes(64));
	store_in_session($unique_form_name, $token);
	return $token;
}

/**
 * Validates the given token for the given form
 *
 * @return boolean
 */
function csrfguard_validate_token($unique_form_name, $token_value)
{
	$token = get_from_session($unique_form_name);
	if (!is_string($token_value)) {
        return false;
    }

    $result = hash_equals($token, $token_value);
    unset_session($unique_form_name);
    
	return $result;
}

/**
 * Receives a portion of html data, finds all <form> occurrences and
 * adds two hidden fields to them: CSRFName and CSRFToken.
 * @return void
 */
function csrfguard_replace_forms($form_data_html)
{
	$count = preg_match_all(
        "/<form(.*?)>(.*?)<\\/form>/is", 
        $form_data_html, 
        $matches, 
        PREG_SET_ORDER);

	if (is_array($matches)) {
		foreach ($matches as $m) {
			if (strpos($m[1], "nocsrf") !== false) { 
                continue; 
            }
			$name = "CSRFGuard_" . mt_rand(0, mt_getrandmax());
			$token = csrfguard_generate_token($name);
			$form_data_html = str_replace(
                $m[0],
				"<form{$m[1]}>" . 
                "<input type='hidden' name='CSRFName' value='{$name}' />" . 
                "<input type='hidden' name='CSRFToken' value='{$token}' />{$m[2]}" .
                "</form>", 
                $form_data_html);
		}
	}
	return $form_data_html;
}

/**
 * Inject the anti-csrf data into form data
 *
 * @return void
 */
function csrfguard_inject()
{
	$data = ob_get_clean();
	$data = csrfguard_replace_forms($data);
	echo $data;
}

/**
 * Start the anti-csrf form handling
 *
 * @return void
 */
function csrfguard_start()
{
	if (count($_POST)) {
		if (!isset($_POST['CSRFName']) or !isset($_POST['CSRFToken'])) {
			trigger_error("No CSRFName found, probable invalid request.", E_USER_ERROR);		
		} 
		$name = $_POST['CSRFName'];
		$token = $_POST['CSRFToken'];
		if (!csrfguard_validate_token($name, $token)) { 
			throw new Exception("Invalid CSRF token.");
		}
	}
	ob_start();
	
	register_shutdown_function("csrfguard_inject");	
}

/**
 * Decide if the given route is allowed based on the session
 *
 * @return boolean
 */
function is_route_allowed($route)
{
	if ($route === 'login' || $route === 'welcome') {
		return true;
	}
	
	return !empty(get_from_session('username'));
}

/**
 * Redirect the user to login page if he is not authenticated.
 *
 * @return void
 */
function protect_route($route)
{
	if (!is_route_allowed($route)) {
		header('location: login.php');
	}
}
