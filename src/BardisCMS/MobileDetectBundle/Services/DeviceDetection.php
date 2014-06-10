<?php

/*
 * MobileDetect Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\MobileDetectBundle\Services;

class DeviceDetection {

	private $useragent;
	private $mobile_ua;
	private $mobile_agents;

	public function __construct() {
		$this->useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
		$this->mobile_ua = substr($this->useragent, 0, 4);

		$this->mobile_agents = array(
			'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
			'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
			'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
			'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
			'newt', 'noki', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox',
			'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
			'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
			'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
			'wapr', 'webc', 'winw', 'winw', 'xda ', 'xda-');
	}
	
	// Test if device is mobile
	public function testMobile() {

		// Count successful tests
		$tablet_browser = 0;
		$mobile_browser = 0;

		// Start the testing
		if (in_array($this->mobile_ua, $this->mobile_agents)) {
			$mobile_browser++;
		}

		if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', $this->useragent)) {
			$tablet_browser++;
		}

		if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', $this->useragent)) {
			$mobile_browser++;
		}

		if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') > 0) || ((isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])))) {
			$mobile_browser++;
		}

		if (strpos($this->useragent, 'opera mini') > 0) {
			$mobile_browser++;

			//Check for tablets on opera mini alternative headers
			$stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] : (isset($_SERVER['HTTP_DEVICE_STOCK_UA']) ? $_SERVER['HTTP_DEVICE_STOCK_UA'] : ''));
			if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
				$tablet_browser++;
			}
		}
		// For tablet and mobile detections use the line below
		// if ($tablet_browser > 0 || $mobile_browser > 0) {
		if ($mobile_browser > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Test if device is tablet
	public function testTablet() {

		// Count successful tests
		$tablet_browser = 0;
		
		if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', $this->useragent)) {
			$tablet_browser++;
		}

		if (strpos($this->useragent, 'opera mini') > 0) {
			//Check for tablets on opera mini alternative headers
			$stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] : (isset($_SERVER['HTTP_DEVICE_STOCK_UA']) ? $_SERVER['HTTP_DEVICE_STOCK_UA'] : ''));
			if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
				$tablet_browser++;
			}
		}
		// For tablet and mobile detections use the line below
		if ($tablet_browser > 0) {
			return true;
		} else {
			return false;
		}
	}

}