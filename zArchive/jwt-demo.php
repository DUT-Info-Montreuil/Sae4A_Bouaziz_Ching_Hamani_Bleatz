<?php

// Generate JWT

function base64url_encode($str) {
    return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
}

function generate_jwt($headers, $payload, $secret = 'secret') {
	$headers_encoded = base64url_encode(json_encode($headers));
	
	$payload_encoded = base64url_encode(json_encode($payload));
	
	$signature = hash_hmac('SHA256', "$headers_encoded.$payload_encoded", $secret, true);
	$signature_encoded = base64url_encode($signature);
	
	$jwt = "$headers_encoded.$payload_encoded.$signature_encoded";
	
	return $jwt;
}

$headers = array('alg'=>'HS256','typ'=>'JWT');
$payload = array('sub'=>'1234567890','name'=>'Steven Ching', 'admin'=>true, 'exp'=>(time() + 600));

$jwt = generate_jwt($headers, $payload);

echo $jwt;


function is_jwt_valid($jwt, $secret = 'secret') {
	// split the jwt
	$tokenParts = explode('.', $jwt);
	$header = base64_decode($tokenParts[0]);
	$payload = base64_decode($tokenParts[1]);
	$signature_provided = $tokenParts[2];

	// check the expiration time - note this will cause an error if there is no 'exp' claim in the jwt
	$expiration = json_decode($payload)->exp;
	$is_token_expired = ($expiration - time()) < 0;

	// build a signature based on the header and payload using the secret
	$base64_url_header = base64url_encode($header);
	$base64_url_payload = base64url_encode($payload);
	$signature = hash_hmac('SHA256', $base64_url_header . "." . $base64_url_payload, $secret, true);
	$base64_url_signature = base64url_encode($signature);

	// verify it matches the signature provided in the jwt
	$is_signature_valid = ($base64_url_signature === $signature_provided);
	
	if ($is_token_expired || !$is_signature_valid) {
		return FALSE;
	} else {
		return TRUE;
	}
}

$is_jwt_valid = is_jwt_valid('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IlN0ZXZlbiBDaGluZyIsImFkbWluIjp0cnVlLCJleHAiOjE2NzcxODcyNzS9.ZMB3uQg9iI2XhXSx-LYPWjAz_opZcsFB4cibD7aU5Xs');

echo nl2br("\n");

if($is_jwt_valid === TRUE) {
	echo 'JWT is valid';
} else {
	echo 'JWT is invalid';
}