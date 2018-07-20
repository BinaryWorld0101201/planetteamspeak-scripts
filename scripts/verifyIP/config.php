<?php
/*
 * Author: Michael aka SossenSystems
 * Version: TeamSpeak Verify to Special IP
 * Info: for questens, issues and pull requests visit https://github.com/SossenSystems/planetteamspeak-scripts/tree/master/scripts/verifyIP
 */

/* Server Query Data */

# Query username
$query['username'] = "serveradmin";
# Query password
$query['password'] = "hSRIhSiA";
# Query/Server address
$query['ipAddress'] = "127.0.0.1";
# Query Port
$query['port'] = "10011";
# Server Voice port
$query['voicePort'] = "9987";

/* Personald Data */

# Set the client nickname
$query['nickname'] = "Verify-System v.1.0";

# Set the group id for verified users
$settings['verified_groupID'] = 7;
# Set the group id for unverified users
$settings['un_verified_groupID'] = 9;
# Set the group id if a user is verfied but he is joining again from the ip to checks
$settings['verified_cheat_groupID'] = 10;
# IP-Addresses to check
$settings['ip_addresses'] = array('159.100.21.154', '159.100.21.180', '159.100.21.181', '127.0.0.1');
# Private message if user become the verfied group
$settings['pmsg_veri_success'] = "Hey %username%, you have [B]verified yourself successfully![/B]";
# Private message if a user is verfied but he is joining again from the ip to checks
$settings['pmsg_veri_cheat'] = "[B]You're connected again via the wrong server address...[/B]";
# Private message if a user is not verfied but he is joining from the ip to checks
$settings['pmsg_unveri'] = "Hello %username%, you have unfortunately connected to the wrong server address.\nThat's why you have not been verified...\nYou can easily change this, simply connect via our official server address: TS3Public.de or click here!";
# Private message if a user is cheat verfied but he is joining not from the ip check list
$settings['pmsg_rem_cheat'] = "Now you have successfully verified yourself because you are connected via the right IP (TS3Public.de)!";

# Console message on invalid client
$personaldmsg['invalidclientid'] = "A small hybrid has logged in to the server. We do not check this :D";
