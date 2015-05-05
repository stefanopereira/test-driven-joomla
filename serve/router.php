<?php
/**
 * Router for built-in PHP web server.
 *
 * @copyright Copyright (C) 2015 Andrew Eddie. All rights reserved.
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.html  GPL-2+
 */

if (preg_match('/\.(?:png|jpg|jpeg|gif)$/', $_SERVER["REQUEST_URI"])) {
	// serve the requested resource as-is.
	return false;
}
else {
	require '../public/index.php';
}
