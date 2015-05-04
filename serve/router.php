<?php
if (preg_match('/\.(?:png|jpg|jpeg|gif)$/', $_SERVER["REQUEST_URI"])) {
	// serve the requested resource as-is.
	return false;
}
else {
	require '../public/index.php';
}
