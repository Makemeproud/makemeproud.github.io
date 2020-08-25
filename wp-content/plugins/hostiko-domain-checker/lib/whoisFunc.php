<?php

class wdcWhoisFunc {

	private  $error_reporting;
	public function __construct($debug = true) {
		if ( $debug ) {
			error_reporting(E_ALL);
			$error_reporting = true;
		} else {
			error_reporting(0);
			$error_reporting = false;
		}

	}
	public $arrContextOptions = array(
			"ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			),
		);

	public function load_whois_file($file){
		$file_dir = plugin_dir_path( __FILE__ ).$file;
		$file_dir_open = fopen($file_dir,'r');
		$file = fread($file_dir_open, filesize($file_dir));
		fclose($file_dir_open);

		return $file;
	}

	public function get_sld_tld($domain) {
		list($sld, $tld) = explode('.', $domain, 2);
		$d = array(
			'domain' => $domain,
			'sld' => $sld,
			'tld' => $tld
		);

		return $d;
	}

	public function parse_domain($domain){
		$whois_servers = json_decode($this->load_whois_file('whois.json'), true);
		$dom = strtolower($domain);
		$port = 43;
		$method = 1; // 1 = whois server based, 2 = http(s)
		$titan = TitanFramework::getInstance('wdc-options');
		$dom = $this->get_sld_tld($dom);
		$domain = $dom['domain'];
		$sld = $dom['sld'];
		$tld = $dom['tld'];

		if (isset($whois_servers[$tld][0])) {
			$whois_server = $whois_servers[$tld][0];
			$available_string = $whois_servers[$tld][1];
		}else{
			return false;
		}

		if ( function_exists( 'parse_ini_string' ) ) {
			$wdc_config = parse_ini_string($titan->getOption('wdc_config'),true);
		}else{
			$wdc_config = wdc_parse_ini_string_m($titan->getOption('wdc_config'),true);
		}
		if($wdc_config){
			$customWhoisServer = $wdc_config['WhoisServer'];

			if($customWhoisServer && array_key_exists($tld, $customWhoisServer)){
				if(array_key_exists('uri', $customWhoisServer[$tld]) && array_key_exists('string', $customWhoisServer[$tld])){
					$whois_server = $customWhoisServer[$tld]['uri'];
					$available_string = $customWhoisServer[$tld]['string'];
				}
			}
		}
		if(strpos($whois_server, "http://") !== false || strpos($whois_server, "https://") !== false){
			$method = 2;
		}

		if($tld === 'ch') $port = 4343;

		$domain = array(
			'domain' => $domain,
			'sld' => $tld,
			'tld' => $sld,
			'whois_server' => $whois_server,
			'port' => $port,
			'method' => $method,
			'available_string' => $available_string
		);

		return $domain;
	}

	public function is_available($domain_name){
		$titan = TitanFramework::getInstance('wdc-options');
		$available_domain_cache_time = $titan->getOption('available_domain_cache');
		$unavailable_domain_cache_time = $titan->getOption('unavailable_domain_cache');

		$d = $this->parse_domain($domain_name);
		if(!$d){
			return json_encode(array('status'=>2, 'protocol'=> '', 'response'=> ''));
		}
		$timeout = 10;
		$domain = $d['domain'];
		$tld = $d['sld'];
		$sld = $d['tld'];
		$whois_server = $d['whois_server'];
		$port = $d['port'];
		$method = $d['method'];
		$available_string = $d['available_string'];

		if($method === 2){
			$check = $this->whois_lookup(2, $whois_server, $port, $domain, $timeout);
			if (preg_match('/'.$available_string.'/',$check)){
				return json_encode(array('status'=>1,'protocol'=>'http', 'response' => $check));
			}else{
				return json_encode(array('status'=>0,'protocol'=>'http', 'response' => $check));
			}
		}


		if($tld == 'co.zw' || $tld == 'org.zw' || $tld == 'ac.zw' || $tld == 'gov.zw' || $tld == 'mil.zw' || $tld == 'zw'){
			$zw_domain = $this->whois_lookup(3, $whois_server, $port, $domain, $timeout);

			if($zw_domain){
				return json_encode(array('status'=>0,'protocol'=>'dns', 'response' => $zw_domain));
			}else{
				return json_encode(array('status'=>1,'protocol'=>'dns', 'response' => $zw_domain));
			}
		}

		$response = $this->whois_lookup(1, $whois_server, $port, $domain, $timeout);

		if(is_object($response)){
			return json_encode(array('status'=>$response->status,'protocol'=> $response->protocol, 'response' => $response->whois));
		}

		if ( ($tld == 'bo' || $tld == 'com.bo' || $tld == 'org.bo' || $tld == 'net.bo' || $tld == 'tv.bo' || $tld == 'web.bo') ){
			 if( !strpos($response, $available_string) ){
				return json_encode(array('status'=>1,'protocol'=>'custom', 'response' => $response));
		}else{
				return json_encode(array('status'=>0,'protocol'=>'custom', 'response' => $response));
			}
		}

	    if (strpos($response, $available_string) !== false){
			//error_log($available_domain_cache_time);
			if(!empty($response) && $available_domain_cache_time && !get_transient( 'wdc_'.$domain_name )) set_transient(  'wdc_'.$domain_name, $response, $available_domain_cache_time );
	    	return json_encode(array('status'=>1,'protocol'=> $port, 'response' => $response));
	    }else{
			//error_log($unavailable_domain_cache_time);
			if(!empty($response) && $unavailable_domain_cache_time && !get_transient( 'wdc_'.$domain_name )) set_transient(  'wdc_'.$domain_name, $response, $unavailable_domain_cache_time );
	    	return json_encode(array('status'=>0,'protocol'=> $port, 'response' => $response));
			}

		}

	public function whois_lookup($method, $whois_server, $port, $domain_name, $timeout){
		$response = "";
		if($method === 1){
			$transient = get_transient( 'wdc_'.$domain_name );

			if( ! empty( $transient ) ) {
				$response = $transient;
			} else {
				$con = fsockopen($whois_server, $port, $errno, $errstr, $timeout);
				//if (!$con) return file_get_contents("http://api.asdqwe.net/api/whois.php?d=$domain_name");
				if(!$con){
					error_log($whois_server." - ".$errstr);
					$response = json_decode(file_get_contents("http://api.asdqwe.net/api/whois.php?d=$domain_name", false, stream_context_create($this->arrContextOptions)));

				}else{
					fputs($con, $domain_name."\r\n");
					while (($buffer = fgets($con, 128)) !== false) {
						$response .= $buffer;
					}
					if(!feof($con)) return false;
					fclose($con);
				}
			}
		}else if ($method === 2){
			$response = file_get_contents($whois_server.$domain_name, false, stream_context_create($this->arrContextOptions));
		}else if ($method === 3){
			$response = dns_get_record($domain_name, DNS_NS);
		}

		return $response;
	}

	public function whois_lookup_details($domain){
		$d = $this->parse_domain($domain);
		$timeout = 10;
		$domain = $d['domain'];
		$whois_server = $d['whois_server'];
		$port = $d['port'];
		$method = $d['method'];
		$response = __("Domain Whois Lookup not available for this domain.","wdc");
		 if($method === 1){
		 	$response = $this->whois_lookup(1, $whois_server, $port, $domain, $timeout);
			if(is_object($response)){
				$response = $response->whois;
			}
		}

		return $response;
	}

}
