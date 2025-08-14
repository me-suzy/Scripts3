<?php


error_reporting(7);

if (!defined('directory')) {
	define('directory', '');
}

class http {
	function get($url) {
		$this->url = $url;
		$url = str_replace("http://","",$this->url);
		$slash = strpos($url, "/");
		if (!empty($slash)) {
			$this->uri = substr ($url, $slash);
		} else {
			$this->uri = "/";
		}
		list ($this->host, $this->port) = explode(":", substr($url, 0, $slash));
		if (empty($this->host)) {
			$this->host = $url;
		}
		if (empty($this->port)) {
			$this->port = "80";
		}
		$this->sock = fsockopen($this->host, $this->port, $this->errno, $this->errstr, 30);
		if (!$this->sock) {
			error("Could not connect to $this->host: $this->errstr ($this->errno)");
		}
		fputs ($this->sock, "GET $this->uri HTTP/1.0\r\nHost: $this->host\r\nConnection: close\r\n\r\n");
		unset ($this->html);
		$this->html = fgets ($this->sock, 256);
		if (eregi("[4][0-9][0-9]", $this->html)) {
			$space = strpos($this->html, " ");
			error(substr($this->html, $space+1));
		}
		while ($line = fgets ($this->sock, 4096)) {
			if (ereg("^[[:space:]][[:space:]]$", $line)) {
				break;
			}
		}
		$this->html = fread ($this->sock, 262144);
		if ($this->sock) {
			fclose ($this->sock);
		}
		return $this->html;
	}
	function check($url) {
		$this->url = $url;
		$url = str_replace("http://","",$this->url);
		$slash = strpos($url, "/");
		if (!empty($slash)) {
			$this->uri = substr ($url, $slash);
		} else {
			$this->uri = "/";
		}
		list ($this->host, $this->port) = explode(":", substr($url, 0, $slash));
		if (empty($this->host)) {
			$this->host = $url;
		}
		if (empty($this->port)) {
			$this->port = "80";
		}
		$this->sock = fsockopen($this->host, $this->port, $this->errno, $this->errstr, 30);
		if (!$this->sock) {
			$this->html = " 404 Not Found";
		} else {
			fputs ($this->sock, "GET $this->uri HTTP/1.0\r\nHost: $this->host\r\nConnection: close\r\n\r\n");
			$this->html = fgets ($this->sock, 256);
			fclose ($this->sock);
		}
		return $this->html;
	}
}

?>