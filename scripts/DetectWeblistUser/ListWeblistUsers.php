<?php
/* Include the planetteamspeak php framework */
include('ts3phpframework-1.1.32/libraries/TeamSpeak3/TeamSpeak3.php');

/* TS3 Query login */
$ts3login['username'] = "serveradmin";
$ts3login['password'] = "password";
$ts3login['address'] = "127.0.0.1";
$ts3login['queryport'] = '10011';
$ts3login['voiceport'] = '9987';
$ts3login['nickname'] = "Weblist-Tracker";

/* Header for command line */
$header[1] = "╔══════════════════════════════════════════════════";
$header[2] = "║ ╔════════════════════════════════════════════════════╗";
$header[3] = "║ ║                    Declarations                    ║";
$header[4] = "║ ║    Real -> Real connection User's without Proxy    ║";
$header[5] = "║ ║        Weblist -> Weblist User's with Proxy        ║";
$header[6] = "║ ╚════════════════════════════════════════════════════╝";
$header[7] = "╚══════════════════════════════════════════════════";

/* IP list for checking in Array */
$ipListToCheck = array( "54.36.133.96" , "54.36.135.226", "51.255.133.2" );

/* Color set */
$color['green'] = "\033[32m";
$color['cyan'] = "\033[36m";
$color['yellow'] = "\033[33m";
$color['purple'] = "\033[35";
$color['red'] = "\033[31m";
$color['grey'] = "\033[1;32m";
$color['lightblue'] = "\033[1;34m";
$color['lightcyan'] = "\033[1;36m";

/* Other */
$newline = "\r\n";
$set['first'] = "║ >";
$set['end'] = "\033[0m";
$e = 0;
$ii = 0;
$footerLine = "════════════════════════════";

$ts3_VirtualServer = TeamSpeak3::factory("serverquery://" . $ts3login['username'] . ":" . $ts3login['password'] . "@" . $ts3login['address'] . ":" . $ts3login['queryport'] . "/?server_port=" . $ts3login['voiceport'] . "&nickname=" . $ts3login['nickname']);	

function runChecker() {
	global $ts3_VirtualServer;
	global $header;
	global $ipListToCheck;
	global $color;
	global $newline;
	global $set;
	global $e;
	global $ii;
	try {
		echo $header[1] . $newline;
		echo $header[2] . $newline;
		echo $header[3] . $newline;
		echo $header[4] . $newline;
		echo $header[5] . $newline;
		echo $header[6] . $newline;
		foreach ($ts3_VirtualServer->clientList() as $client) {
			if($client->client_type == "0"){
				if(in_array($client['connection_client_ip'], $ipListToCheck)) {
					try {
						$e++;
						echo $set['first'] . $color['lightblue'] . "  Weblist   -> " . $client['client_nickname'] . $set['end'] . $newline;
					} catch(Exception $e) {
						print_r($color['red'] . "[ERROR] " . $e->getMessage() . $set['end'] . $newline);
						continue;
					}
				} else {
					try {
						echo "║ > " . $color['grey'] . " Real      -> " . $client['client_nickname'] . $set['end'] . $newline;
					} catch(Exception $e) {
						print_r($color['red'] . "[ERROR] " . $e->getMessage() . $set['end'] . $newline);
						continue;
					}
				}
				
			}
		}
		echo $header[7] . $newline;
	} catch(Exception $e) {
		print_r($color['red'] . "[ERROR] " . $e->getMessage() . $set['end'] . $newline);
	}
	CheckResults();
}

function CheckResults() {
	global $ts3_VirtualServer;
	global $e;
	global $ii;
	global $color;
	global $newline;
	global $set;
	$countoftotalweb = $e + $ii;
	$useronline = $ts3_VirtualServer->getProperty("virtualserver_clientsonline") - $ts3_VirtualServer->getProperty("virtualserver_queryclientsonline");
	$useronlinemweb = $useronline - $countoftotalweb;
	echo ">" . $color['cyan'] . " The Weblist Online User count is " . $e . $set['end'] . $newline;
	echo ">" . $color['cyan'] . " The Real Online User count is " . $useronlinemweb . $set['end'] . $newline;
	echo ">" . $color['cyan'] . " " . $useronline . " Total User Online!" . $set['end'] . $newline;
	CloseScript();
}
function CloseScript() {
	global $ts3_VirtualServer;
	global $footerLine;
	try {
		$ts3_VirtualServer->logout();
	} catch(Exception $e) {
		echo $footerLine . $newline;
		echo "> " . $color['red'] . "Some errors to disconnect from the TeamSpeak 3 Server!" . $set['end'] . $newline;
		echo "> ERROR LOG: " . $color['red'] . $e . $set['end'] . $newline;
		echo $footerLine . $newline;
	}
}

runChecker();

?>
