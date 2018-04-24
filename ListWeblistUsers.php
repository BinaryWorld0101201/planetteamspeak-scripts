<?php
include('ts3phpframework-1.1.32/libraries/TeamSpeak3/TeamSpeak3.php');
$ts3_VirtualServer = TeamSpeak3::factory("serverquery://serveradmin:2399sdjsj9fj932@127.0.0.1:10011/?server_port=9987&nickname=[12-ATTENTION]%20TS3Public.de");	

class ConsoleQuestion{
    function readline(){
        return rtrim(fgets(STDIN));
    }
}

$line = new ConsoleQuestion();
	
$newline = "\r\n";

$e = 0;
$ii = 0;
$sleepcount = 300;
while (true) {
	try{
		echo "╔══════════════════════════════════════════════════\n";
		echo "║ Declarations list\n";
		echo "║ German Weblist User's ●\n";
		echo "║ International Weblist-User's ○\n";
		echo "║ Real IP User's\n";
		echo "║ -----------------\n";
		foreach ($ts3_VirtualServer->clientList() as $client) {
			if($client->client_type == "0"){
				if($client['connection_client_ip'] == "185.250.251.133" OR $client['connection_client_ip'] == "159.100.21.154" OR $client['connection_client_ip'] == "185.250.251.141"){
					try{
						if($client['client_country'] == "DE"){
							$e++;
							echo "║ > \033[32m ● " . $client['client_nickname'] . " " . $e . "\033[0m\n";
						}else{
							$ii++;
							echo "║ > \033[32m ○ " . $client['client_nickname'] . " " . $ii . "\033[0m\n";
						}
					}catch(Exception $e){
					    print_r("[ERROR] " . $e->getMessage() . $newline);
					    continue;
					}
				}else{
					echo "║ > \033[32m Real -> " . $client['client_nickname'] . "\033[0m\n";
				}
				
			}
		}
		echo "╚══════════════════════════════════════════════════\n";
		$countoftotalweb = $e + $ii;
		$useronline = $ts3_VirtualServer->getProperty("virtualserver_clientsonline") - $ts3_VirtualServer->getProperty("virtualserver_queryclientsonline");
		$useronlinemweb = $useronline - $countoftotalweb;
		echo ">\033[36m There are " . $e . " Weblist User's Online from Germany!\033[0m\n";
		echo ">\033[36m There are " . $ii . " Weblist User's Online from the no country Location!\033[0m\n";
		echo ">\033[36m The total Weblist User count is " . $countoftotalweb . "!\033[0m\n";
		echo ">\033[36m The total not Weblist User count is " . $useronlinemweb . "!\033[0m\n";
		echo ">\033[36m " . $useronline . " Total User Online!\033[0m\n";
		echo "\033[33m Checking again in " . $sleepcount/60 . " minutes...\033[0m\n";
		$e = 0;
		$ii = 0;
		$prompt = "Type \033[35mend\033[0m for close this session: ";
		echo $prompt;
		$answer = $line->readline();
		if($answer == "end"){
			break;
		}
	}catch(Exception $e){
	   print_r("\033[31m[ERROR] " . $e->getMessage() . $newline . "\033[0m\n");
	   continue;
	}
	sleep($sleepcount);
}
echo ">\033[31m Turning off! Bye bye...\033[0m\n";
try{
	$ts3_VirtualServer->logout();
}catch(Exception $e){
	echo ">\033[31m Some errors to disconnect from the TeamSpeak 3 Server!\033[0m\n";
	echo "===========================\n";
	echo "ERROR INFOLINE\n";
	print_r("\033[33m" . $e . "\033[0m\n");
	echo "===========================\n";
}
?>
