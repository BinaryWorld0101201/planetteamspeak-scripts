<?php
/*
 * Author: Michael aka SossenSystems
 * Version: TeamSpeak Verify to Special IP
 * Info: for questens, issues and pull requests visit https://github.com/SossenSystems/planetteamspeak-scripts/tree/master/scripts/verifyIP
 */

// This function get the event from the bot.php
function getEvent($event, $host){
    if ($event["client_type"] == 0) {
        $client = $host->serverGetSelected()->clientGetById($event["clid"]);
        $clientInfo = $client->getInfo();
    } else {
        $clientInfo = false;
    }
    return $clientInfo;
}

// This function is the heart of this script :D
function checkVerify($clientInfo, $host) {
    global $personaldmsg, $settings, $kickoption, $client;
    if($clientInfo != false) {
        $srvgroup = explode(',', $clientInfo['client_servergroups']);
        $client = $host->serverGetSelected()->clientGetById($clientInfo["clid"]);
        if(array_search($clientInfo['connection_client_ip'], $settings['ip_addresses'])){
            if(array_search($settings['verified_groupID'], $srvgroup)){
                $client->remServerGroup($settings['verified_groupID']);
                $client->addServerGroup($settings['verified_cheat_groupID']);
                $client->message($settings['pmsg_veri_cheat']);
            }else{
                $client->message(str_replace('%username%', $clientInfo["client_nickname"], $settings['pmsg_unveri']));
            }
        }else{
            if(array_search($settings['verified_cheat_groupID'], $srvgroup)){
                $client->remServerGroup($settings['verified_cheat_groupID']);
                $client->addServerGroup($settings['verified_groupID']);
                $client->message($settings['pmsg_rem_cheat']);
            }else if(!array_search($settings['verified_groupID'], $srvgroup)){
                $client->addServerGroup($settings['verified_groupID']);
                $client->message(str_replace('%username%', $clientInfo["client_nickname"], $settings['pmsg_veri_success']));
            }
        }
    } else {
        echo $personaldmsg['invalidclientid'] . "\n";
    }
}
