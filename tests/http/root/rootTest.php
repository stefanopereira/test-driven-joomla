<?php
/**
 * @copyright Copyright (C) 2015 Andrew Eddie. All rights reserved.
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.html  GPL-2+
 */

namespace TDJ\Tests;

/**
 * Class httpTest
 */
class rootTest extends \PHPUnit_Framework_Testcase
{
	protected function send($method, $route)
	{
		$url = sprintf('http://%s:%d%s', WEB_SERVER_HOST, WEB_SERVER_PORT, $route);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = array(
			'content' => curl_exec($ch),
			'http-code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
			'content-type' => curl_getinfo($ch, CURLINFO_CONTENT_TYPE)
		);

		curl_close($ch);

		return $response;
	}

	/**
	 * @test
	 */
	public function shouldGetTheIndexPage()
	{
		$response = $this->send('GET', '/');

		$this->assertEquals(file_get_contents(__DIR__ . '/expected.html'), $response['content']);
		$this->assertEquals(200, $response['http-code']);
//		$this->assertEquals('text/html', $contentType);
	}
}
