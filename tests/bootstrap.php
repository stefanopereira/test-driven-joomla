<?php
/**
 * @copyright Copyright (C) 2015 Andrew Eddie. All rights reserved.
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.html  GPL-2+
 * @link      https://github.com/vgno/tech.vg.no-1252/blob/master/features/bootstrap/FeatureContext.php
 */

namespace TDJ\Tests;

/**
 * Bootstrap PHPUnit tests.
 */
class HttpTestHelper
{
	public static function serve()
	{
		// Try to start the web server
		$pid = self::startHttpd(
			WEB_SERVER_HOST,
			WEB_SERVER_PORT,
			WEB_SERVER_DOCROOT
		);

		if (!$pid) {
			throw new RuntimeException('Could not start the web server');
		}

		$start = microtime(true);
		$connected = false;
		$delta = 0;

		// Try to connect until the time spent exceeds the timeout specified in the configuration
		do {
			if (self::canConnectToHttpd(WEB_SERVER_HOST, WEB_SERVER_PORT)) {
				$connected = true;
				break;
			}

			$delta = microtime(true) - $start;
		} while ($delta <= (int)WEB_SERVER_TIMEOUT);

		if (!$connected) {
			self::killProcess($pid);
			throw new RuntimeException(
				sprintf(
					'Could not connect to the web server within the given timeframe (%d second(s))',
					WEB_SERVER_TIMEOUT
				)
			);
		}

		echo sprintf(
			"%s - Web server started on %s:%d with PID %d in %.3fs\n",
			date('r'),
			WEB_SERVER_HOST,
			WEB_SERVER_PORT,
			$pid,
			$delta
		);

		return $pid;
	}

	/**
	 * Start the built in httpd
	 *
	 * @param string $host         The hostname to use
	 * @param int    $port         The port to use
	 * @param string $documentRoot The document root
	 *
	 * @return int Returns the PID of the httpd
	 */
	private static function startHttpd($host, $port, $documentRoot)
	{
		// Build the command
		$command = sprintf(
			'php -S %s:%d -t %s >/dev/null 2>&1 & echo $!',
			$host,
			$port,
			$documentRoot);
		$output = array();
		exec($command, $output);

		return (int)$output[0];
	}

	/**
	 * See if we can connect to the httpd
	 *
	 * @param string $host The hostname to connect to
	 * @param int    $port The port to use
	 *
	 * @return boolean
	 */
	private static function canConnectToHttpd($host, $port)
	{
		// Disable error handler for now
		set_error_handler(function () {
			return true;
		});

		// Try to open a connection
		$sp = fsockopen($host, $port);

		// Restore the handler
		restore_error_handler();

		if ($sp === false) {
			return false;
		}

		fclose($sp);

		return true;
	}

	public static function killProcess($pid)
	{
		echo sprintf("%s - Killing process with ID %d\n", date('r'), $pid);
		exec('kill ' . (int)$pid);
	}
}

$pid = HttpTestHelper::serve();

// Kill the web server when the process ends
register_shutdown_function(function () use ($pid) {
	HttpTestHelper::killProcess($pid);
});

