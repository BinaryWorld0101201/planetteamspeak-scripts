<?php
// Include the ts3phframework
include('ts3phpframework-1.1.32/libraries/TeamSpeak3/TeamSpeak3.php');
$ts3_VirtualServer = TeamSpeak3::factory("serverquery://serveradmin:3243djs2!#12@51.255.133.6:10011/?server_port=9987&nickname=[ATTENTION]%20Server%20Bot");

// CONFIG
$ccode = "DE";
$gmsg = "Your Text..."; // Defined a text the example is Germany \n for a new line
$msg = "Your Text..."; // International text \n for a new line
$cmsg = "is a Weblist-User"; // Write to the console as an example "Username is a Weblist-User"
$ip = "51.255.133.2"; // Enter the IP address, which will be written to if a user owns it.
$sleep = 300; // The delay is 5 minutes in seconds

$newline = "\r\n";

while (1) {
	try{
		foreach ($ts3_VirtualServer->clientList() as $client) {
			if($client->client_type == "0"){
				if($client->connection_client_ip == $ip){
					echo $client->client_nickname . " " . $cmsg . $newline;
					try{
						if($client->client_country == $ccode){
							$client->message($gmsg);
						}else{
							$client->message($emsg);
						}
					}catch(Exception $e){
					    print_r("[ERROR] " . $e->getMessage() . $newline);
					}
				}
			}
		}
	}catch(Exception $e){
	   print_r("[ERROR] " . $e->getMessage() . $newline);
	}
	sleep($sleep);
}
$ts3_VirtualServer->logout;
?>
