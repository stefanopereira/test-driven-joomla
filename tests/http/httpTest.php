<?php
/**
 * Class httpTest
 *
 * @link https://github.com/vgno/tech.vg.no-1252/blob/master/features/bootstrap/FeatureContext.php
 */

class httpTest extends PHPUnit_Framework_Testcase
{
	/**
	 * Pid for the web server
	 *
	 * @var int
	 */
	private static $pid;

	public static function setUpBeforeClass()
	{
		// Try to start the web server
		self::$pid = self::startHttpd(
			WEB_SERVER_HOST,
			WEB_SERVER_PORT,
			WEB_SERVER_DOCROOT
		);

		if (!self::$pid) {
			throw new RuntimeException('Could not start the web server');
		}

		$start = microtime(true);
		$connected = false;

		// Try to connect until the time spent exceeds the timeout specified in the configuration
		do {
			if (self::canConnectToHttpd(WEB_SERVER_HOST, WEB_SERVER_PORT)) {
				$connected = true;
				break;
			}

			$delta = microtime(true) - $start;
		} while ($delta <= (int) WEB_SERVER_TIMEOUT);

		if (!$connected) {
			self::killProcess(self::$pid);
			throw new RuntimeException(
				sprintf(
					'Could not connect to the web server within the given timeframe (%d second(s))',
					WEB_SERVER_TIMEOUT
				)
			);
		}

		echo sprintf(
				'%s - Web server started on %s:%d with PID %d in %.3fs',
				date('r'),
				WEB_SERVER_HOST,
				WEB_SERVER_PORT,
				self::$pid,
				$delta
			) . PHP_EOL;

	}

	/**
	 * Kill the httpd process if it has been started when the tests have finished
	 *
	 * @AfterSuite
	 */
	public static function tearDownAfterClass()
	{
		if (self::$pid) {
			self::killProcess(self::$pid);
		}
	}

	/**
	 * @test
	 */
	public function shouldGetTheIndexPage()
	{
		$request = json_decode(file_get_contents(__DIR__ . '/root/request.json'));

		$url = sprintf('http://%s:%d%s', WEB_SERVER_HOST, WEB_SERVER_PORT, $request->route);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
		curl_close($ch);

		$this->assertEquals(file_get_contents(__DIR__ . '/root/output.html'), $response);
		$this->assertEquals(200, $httpCode);
//		$this->assertEquals('text/html', $contentType);
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


	/**
	 * Kill a process
	 *
	 * @param int $pid
	 */
	private static function killProcess($pid)
	{
		exec('kill ' . (int)$pid);
	}
}
