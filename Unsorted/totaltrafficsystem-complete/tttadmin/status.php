<?php

// This single file is licensed under the terms of GPL
// Visit Gnu website for more information. http://www.gnu.org/

// You may use this file for your site monitoring. Currently this file is
// not used, but you will be able to monitor your web-host latter.


//__________________________________________________________________________________________________________________________________

$PLUGIN_VERSION='0.96b';
$TTS_VERSION='2.1.0';
$TTS_RELEASE_DATE='2003-11-15';


// Supported OS: Linux, FreeBSD
// Gathered information: Host name
//                       Host ip
//                       System uptime
//                       Users logged in
//                       Network status
//                       Load average
//                       CPU info
//                       Memory
//                       File systems


//__________________________________________________________________________________________________________________________________

	// Find a system program.  Do path checking
	function find_program($program) {
		$path = array('/bin', '/sbin', '/usr/bin', '/usr/sbin', '/usr/local/bin', '/usr/local/sbin');
		while ($this_path = current($path)) {
			if (is_executable("$this_path/$program"))
				return "$this_path/$program";
			next($path);
		}
		return;
	}


	// Execute a system program. return a trim()'d result.
	// does very crude pipe checking.  you need ' | ' for it to work
	// ie $program = execute_program('netstat', '-anp | grep LIST');
	// NOT $program = execute_program('netstat', '-anp|grep LIST');
	function execute_program($program, $args = '') {
		$buffer = '';
		$program = find_program($program);

		if (!$program)
			return;

		// see if we've gotten a |, if we have we need to do patch checking on the cmd
		if ($args) {
			$args_list = split(' ', $args);
			for ($i = 0; $i < count($args_list); $i++)
				if ($args_list[$i] == '|') {
					$cmd = $args_list[$i + 1];
					$new_cmd = find_program($cmd);
					$args = ereg_replace("\| $cmd", "| $new_cmd", $args);
				}
		}

		// we've finally got a good cmd line.. execute it
		if ($fp = popen("$program $args", 'r')) {
			while (!feof($fp))
				$buffer .= fgets($fp, 4096);
			return trim($buffer);
    }
	}


//__________________________________________________________________________________________________________________________________

	class TCommonSysInfo {

		// get our apache SERVER_NAME
		function hostname() {
			if ( !$result = getenv('SERVER_NAME') )
				$result = 'N.A.';
			return $result;
		}

		// get the IP address of our canonical hostname
		function ip_addr() {
			if ( !$result = getenv('SERVER_ADDR') )
				$result = gethostbyname($this->chostname());
			return $result;
    }

		// check is service is active with grep $service /proc/*/cmdline
		function check_service_is_active($service) {
			// exclude /proc/xxxxx/cmdline and /proc/self/cmdline
			if ( count(split("\n", execute_program('grep', $service.' /proc/'.'*'.'/cmdline')))>2 )
				return 1;

			return 0;
		}

		// check is mysqld is active
		function check_mysqld() {
			// attempt #1 (with default values)
			$res=@mysql_connect();
			@mysql_close($res);
			if ( $res )
				return 1;

			// attempt #2 (with ttt)
			@include("../ttt-mysqlvalues.inc.php");
			$res=@mysql_connect($mysql_host, $mysql_user, $mysql_pass);
			@mysql_close($res);
			if ( $res )
				return 2;

			// attempt #3 (with grep mysqld /proc/*/cmdline)
			if ( $this->check_service_is_active('mysqld') )
				return 3;

			return 0;
		}

	};


//__________________________________________________________________________________________________________________________________

	class TLinuxSysInfo extends TCommonSysInfo {

		function network() {
			$results = array();
			if ($fd = fopen('/proc/net/dev', 'r')) {
				while ($buf = fgets($fd, 4096)) {
					if (preg_match('/:/', $buf)) {
						list($dev_name, $stats_list) = preg_split('/:/', $buf, 2);
						$dev_name=trim($dev_name);
						$stats = preg_split('/\s+/', trim($stats_list));
						$results[$dev_name] = array();

						$results[$dev_name]['rx_bytes']   = $stats[0];
						$results[$dev_name]['rx_packets'] = $stats[1];
						$results[$dev_name]['rx_errs']    = $stats[2];
						$results[$dev_name]['rx_drop']    = $stats[3];

						$results[$dev_name]['tx_bytes']   = $stats[8];
						$results[$dev_name]['tx_packets'] = $stats[9];
						$results[$dev_name]['tx_errs']    = $stats[10];
						$results[$dev_name]['tx_drop']    = $stats[11];
					}
				}
			}
			return $results;
		}

		function uptime() {
			$fd = fopen('/proc/uptime', 'r');
			$ar_buf = split(' ', fgets($fd, 4096));
			fclose($fd);

			$sys_ticks = trim($ar_buf[0]);

			return $sys_ticks;
    }

		function users () {
			$res=execute_program('who', '-q');
			if ( !strlen($res) )
				return "";
			$who = split('=', $res);
			$result = $who[1];
			return $result;
    }

		function loadavg() {
			if ($fd = fopen('/proc/loadavg', 'r')) {
				$_results = split(' ', fgets($fd, 4096));
				$_results[count($_results)-1]=trim($_results[count($_results)-1]);
				fclose($fd);
			} else
				$_results = array(0, 0, 0);

			$results=array();
			for ($q=0; $q<3; $q++)
				$results["val$q"]=$_results[$q];
				
			return $results;
    }

		function cpu_info() {
			$results = array();
			$ar_buf = array();
			$assoc = "undefined";
			$cpus = -1;

			if ($fd = fopen('/proc/cpuinfo', 'r')) {
				while ($buf = fgets($fd, 4096)) {
					@list($key, $value) = preg_split('/\s+:\s+/', trim($buf), 2);

					switch ($key) {
						case 'processor':
							$cpus+=1;
							$assoc="cpu$cpus";
							break;
						case 'model name':
							$results[$assoc]['model'] = $value;
							break;
						case 'cpu MHz':
							$results[$assoc]['mhz'] = sprintf('%.2f', $value);
							break;
						case 'cache size':
							$results[$assoc]['cache'] = $value;
							break;
						case 'bogomips':
							@$results[$assoc]['bogomips'] += $value;
							break;
					}
				}
				fclose($fd);
			}

			return $results;
		}

		function memory() {
			$results['ram'] = array();
			$results['swap'] = array();
			$results['devswap'] = array();

			if ($fd = fopen('/proc/meminfo', 'r')) {
				while ($buf = fgets($fd, 4096)) {
					if (preg_match('/Mem:\s+(.*)$/', $buf, $ar_buf)) {
						$ar_buf = preg_split('/\s+/', $ar_buf[1], 6);

						$results['ram']['total']   = $ar_buf[0] / 1024;
						$results['ram']['used']    = $ar_buf[1] / 1024;
						$results['ram']['shared']  = $ar_buf[3] / 1024;
						$results['ram']['buffers'] = $ar_buf[4] / 1024;
						$results['ram']['cached']  = $ar_buf[5] / 1024;
					}

					if (preg_match('/Swap:\s+(.*)$/', $buf, $ar_buf)) {
						$ar_buf = preg_split('/\s+/', $ar_buf[1], 3);

						$results['swap']['total']   = $ar_buf[0] / 1024;
						$results['swap']['used']    = $ar_buf[1] / 1024;

						break;
					}
				}
				fclose($fd);
			}

			if ( !count($results['ram']) && !count($results['swap']) ) 
				if ($fd = fopen('/proc/meminfo', 'r')) {
					while ($buf = fgets($fd, 4096)) {
						if ( preg_match('/MemTotal:\s+\d{1,}\s+kb/i', $buf) )
							list(, $results['ram']['total'])=preg_split('/\s{1,}/', $buf);
						if ( preg_match('/MemFree:\s+\d{1,}\s+kb/i', $buf) )
							list(, $ram_free)=preg_split('/\s{1,}/', $buf);
						if ( preg_match('/Buffers:\s+\d{1,}\s+kb/i', $buf) )
							list(, $results['ram']['buffers'])=preg_split('/\s{1,}/', $buf);
						if ( preg_match('/Cached:\s+\d{1,}\s+kb/i', $buf) )
							list(, $results['ram']['cached'])=preg_split('/\s{1,}/', $buf);
						if ( preg_match('/SwapTotal:\s+\d{1,}\s+kb/i', $buf) )
							list(, $results['swap']['total'])=preg_split('/\s{1,}/', $buf);
						if ( preg_match('/SwapFree:\s+\d{1,}\s+kb/i', $buf) )
							list(, $swap_free)=preg_split('/\s{1,}/', $buf);
					}
					fclose($fd);
					@$results['ram']['used']=$results['ram']['total']-$ram_free;
					@$results['swap']['used']=$results['swap']['total']-$swap_free;
				}

			// Get info on individual swap files
			$swaps = implode('', file('/proc/swaps'));
			$swapdevs = split("\n", $swaps);

			for ($i = 1; $i < (sizeof($swapdevs) - 1); $i++) {
				$ar_buf = preg_split('/\s+/', $swapdevs[$i], 6);

				$assoc='swp'.($i-1);
				$results['devswap'][$assoc] = array();
				$results['devswap'][$assoc]['dev']     = htmlspecialchars($ar_buf[0]);
				$results['devswap'][$assoc]['total']   = $ar_buf[2];
				$results['devswap'][$assoc]['used']    = $ar_buf[3];
			}

			return $results;
		}

		function filesystems () {
			$df = execute_program('df', '-kP');
			if ( !strlen($df) )
				return "";
			$mounts = split("\n", $df);
			$fstype = array();

			if ($fd = fopen('/proc/mounts', 'r')) {
				while ($buf = fgets($fd, 4096)) {
					list($dev, $mpoint, $type) = preg_split('/\s+/', trim($buf), 4);
					$fstype[$mpoint] = $type;
					$fsdev[$dev] = $type;
				}
				fclose($fd);
			}

			for ($i = 1; $i < sizeof($mounts); $i++) {
				$ar_buf = preg_split('/\s+/', $mounts[$i], 6);

				$assoc='vol'.($i-1);
				$results[$assoc] = array();

				$results[$assoc]['disk'] = htmlspecialchars($ar_buf[0]);
				$results[$assoc]['size'] = $ar_buf[1];
				$results[$assoc]['used'] = $ar_buf[2];
				$results[$assoc]['mount'] = htmlspecialchars($ar_buf[5]);
				($fstype[$ar_buf[5]]) ? $results[$assoc]['fstype'] = $fstype[$ar_buf[5]] : $results[$assoc]['fstype'] = $fsdev[$ar_buf[0]];
			}
			return $results;
		}
	};


//__________________________________________________________________________________________________________________________________

	class TFreeBSDSysInfo extends TCommonSysInfo {
		var $dmesg;

		// grabs a key from sysctl(8)
		function grab_key ($key) {
			return execute_program('sysctl', "-n $key");
		}

		// read /var/run/dmesg.boot, but only if we haven't already.
		function read_dmesg () {
			if ( !$this->dmesg )
				$this->dmesg = file ('/var/run/dmesg.boot');
			return $this->dmesg;
    }
 
		function network() {
			$netstat = execute_program('netstat', '-nbdi | cut -c1-24,42- | grep Link');
			if ( !strlen($netstat) )
				return "";
			$lines = split("\n", $netstat);
			$results = array();
			for ($i = 0; $i < sizeof($lines); $i++) {
				$ar_buf = preg_split("/\s+/", $lines[$i]);
				if (!empty($ar_buf[0]) && !empty($ar_buf[3])) {
					$results[$ar_buf[0]] = array();

					$results[$ar_buf[0]]['rx_bytes'] = $ar_buf[5];
					$results[$ar_buf[0]]['rx_packets'] = $ar_buf[3];
					$results[$ar_buf[0]]['rx_errs'] = $ar_buf[4];
					$results[$ar_buf[0]]['rx_drop'] = $ar_buf[10];

					$results[$ar_buf[0]]['tx_bytes'] = $ar_buf[8];
					$results[$ar_buf[0]]['tx_packets'] = $ar_buf[6];
					$results[$ar_buf[0]]['tx_errs'] = $ar_buf[7];
					$results[$ar_buf[0]]['tx_drop'] = $ar_buf[10];

					$results[$ar_buf[0]]['errs'] = $ar_buf[4] + $ar_buf[7];
					$results[$ar_buf[0]]['drop'] = $ar_buf[10];
				}
			}
			return $results;
		}

		function get_sys_ticks() {
			$s = explode(' ', $this->grab_key('kern.boottime'));
			$a = ereg_replace('{ ', '', $s[3]);
			$sys_ticks = time() - $a;
			return $sys_ticks;
		}

		function uptime() {
			$sys_ticks = $this->get_sys_ticks();

			return $sys_ticks;
    }

		function users() {
			return execute_program('who', '| wc -l');
		}

		function loadavg() {
			$s = $this->grab_key('vm.loadavg');
			$s = ereg_replace('{ ', '', $s);
			$s = ereg_replace(' }', '', $s);
			$_results = explode(' ', $s);

			$results=array();
			for ($q=1; $q<=3; $q++)
				$results["val$q"]=$_results[$q-1];

			return $results;
    }

		function cpu_info() {
			$results = array();
			$ar_buf  = array();

			$results['model'] = $this->grab_key('hw.model');
			$results['cpus']  = $this->grab_key('hw.ncpu');

			for ($i=0; $i < count($this->read_dmesg()); $i++) {
				$buf = $this->dmesg[$i];
				if (preg_match("/CPU: (.*) \((.*)-MHz (.*)\)/", $buf, $ar_buf)) {
					$results['mhz'] = round($ar_buf[2]);
					break;
				}
			}
			return $results;
		}

		function memory() {
			$s = $this->grab_key('hw.physmem');
			if ( !strlen($s) )
				return "";

			if (PHP_OS == 'FreeBSD' || PHP_OS == 'OpenBSD') {
				# vmstat on fbsd 4.4 or greater outputs kbytes not hw.pagesize
				# I should probably add some version checking here, but for now
				# we only support fbsd 4.4
				$pagesize = 1024;
			} else {
				$pagesize = $this->grab_key('hw.pagesize');
			}

			$results['ram'] = array();

			$pstat = execute_program('vmstat');
			$lines = split("\n", $pstat);
			for ($i = 0; $i < sizeof($lines); $i++) {
				$ar_buf = preg_split("/\s+/", $lines[$i], 19);

				if ($i == 2)
					$results['ram']['free'] = $ar_buf[5] * $pagesize / 1024;
			}

			$results['ram']['total'] = $s / 1024;
			$results['ram']['shared'] = 0;
			$results['ram']['buffers'] = 0;
			$results['ram']['used'] = $results['ram']['total'] - $results['ram']['free'];
			$results['ram']['cached'] = 0;

			if (PHP_OS == 'OpenBSD')
				$pstat = execute_program('swapctl', '-l -k');
			else
				$pstat = execute_program('swapinfo', '-k');

			$lines = split("\n", $pstat);

			for ($i = 0; $i < sizeof($lines); $i++) {
				$ar_buf = preg_split("/\s+/", $lines[$i], 6);

				if ($i == 0) {
					$results['swap']['total'] = 0;
					$results['swap']['used'] = 0;
				} else {
					$results['swap']['total'] = $results['swap']['total'] + $ar_buf[1];
					$results['swap']['used'] = $results['swap']['used'] + $ar_buf[2];
				}
			}

			return $results;
		}

		function filesystems() {
			$df = execute_program('df', '-k');
			if ( !strlen($df) )
				return "";
			$mounts = split("\n", $df);
			$fstype = array();

			$s     = execute_program('mount');
			$lines = explode("\n", $s);

			$i = 0;
			while (list(,$line) = each($lines)) {
				ereg('(.*) \((.*)\)', $line, $a);

				$m = explode(' ', $a[0]);
				$fsdev[$m[0]] = $a[2];
			}

			for ($i = 1, $j = 0; $i < sizeof($mounts); $i++) {
				$ar_buf = preg_split("/\s+/", $mounts[$i], 6);

				// skip the proc filesystem
				if ($ar_buf[0] == 'procfs' || $ar_buf[0] == 'linprocfs')
					continue;

				$assoc="vol$j";
				$results[$assoc] = array();

				$results[$assoc]['disk'] = $ar_buf[0];
				$results[$assoc]['size'] = $ar_buf[1];
				$results[$assoc]['used'] = $ar_buf[2];
				$results[$assoc]['mount'] = htmlspecialchars($ar_buf[5]);
				($fstype[$ar_buf[5]]) ? $results[$assoc]['fstype'] = $fstype[$ar_buf[5]] : $results[$assoc]['fstype'] = $fsdev[$ar_buf[0]];
				$j++;
			}
			return $results;
		}
	};


//__________________________________________________________________________________________________________________________________

	function exists_info($res) {
		if (!is_array($res))
			return strlen($res);

		foreach ($res as $key=>$value)
			if (is_array($value))
				return true;
			if (strlen($value))
				return true;

		return false;
	}

	function check_array_presence(&$array) {
		foreach ($array as $key=>$value)
			if (is_array($value))
				return true;
		return false;
	}

	function array2xml($array){
		$res='';
		foreach ($array as $key=>$value) {
			if (is_array($value)) {
				$tree=check_array_presence($value);
				$res.='<'.$key.($tree ? '>' : ' ');
				$res.=array2xml($value);
				$res.=($tree ? '</'.$key : '/').'>';
			} else {
				$res.=$key.'="'.$value.'" ';
			}
		}
		return $res;
	}


//__________________________________________________________________________________________________________________________________

	if ( strtolower(PHP_OS) == 'linux' )
		$os=new TLinuxSysInfo();
	else
	if ( strtolower(PHP_OS) == 'freebsd' )
		$os=new TFreeBSDSysInfo();
	else {
		echo '<?xml version="1.0" ?'.'><root><unsupported_os name="'.PHP_OS.'" /></root>';
		exit();
	}


	@$stat=stat('../ttt-mysqlvalues.inc.php');
	@$TTS_INSTALL_DATE=$stat[10];

	$summary['general']	= array(
												'plugin_version'=>$PLUGIN_VERSION,
                        'tts_version'=>$TTS_VERSION,
                        'tts_release_date'=>$TTS_RELEASE_DATE,
                        'tts_install_date'=>$TTS_INSTALL_DATE,
												'os'=>PHP_OS,
												'hostname'=>$os->hostname(),
												'ip_addr'=>$os->ip_addr(),
												'uptime'=>$os->uptime(),
												'users'=>$os->users(),
												'mysqld'=>$os->check_mysqld()
											);

	$res=$os->network();
	if ( exists_info($res) ) $summary['network'] = $res;

	$res=$os->cpu_info();
	if ( exists_info($res) ) $summary['cpu_info'] = $res;

	$res=$os->loadavg();
	if ( exists_info($res) ) $summary['loadavg'] = $res;

	$res=$os->memory();
	if ( exists_info($res) ) $summary['memory'] = $res;

	$res=$os->filesystems();
	if ( exists_info($res) ) $summary['filesystems'] = $res;

	$xml='<?xml version="1.0" ?'.'><root>'.array2xml($summary).'</root>';

	echo $xml;

?>
