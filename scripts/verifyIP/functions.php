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

function checkGroup($group, $clientInfo){
    $srvgroup = explode(',', $clientInfo['client_servergroups']);
    foreach ($srvgroup as $servergroup){
        if($servergroup == $group){
            return true;
        }
    }
}

// This function is the heart of this script :D
function checkVerify($clientInfo, $host) {
    global $personaldmsg, $settings, $kickoption, $client;
    if($clientInfo != false) {
        $srvgroup = explode(',', $clientInfo['client_servergroups']);
        $client = $host->serverGetSelected()->clientGetById($clientInfo["clid"]);
        if(array_search($clientInfo['connection_client_ip'], $settings['ip_addresses'])){
            if(checkGroup($settings['verified_groupID'], $clientInfo) && !checkGroup($settings['verified_cheat_groupID'], $clientInfo) && !checkGroup($settings['un_verified_groupID'], $clientInfo)){
                echo "999";
                $client->remServerGroup($settings['verified_groupID']);
                $client->addServerGroup($settings['verified_cheat_groupID']);
                $client->message($settings['pmsg_veri_cheat']);
            }else if(!checkGroup($settings['verified_groupID'], $clientInfo) && !checkGroup($settings['un_verified_groupID'], $clientInfo) && checkGroup($settings['verified_cheat_groupID'], $clientInfo)){
                $client->remServerGroup($settings['verified_cheat_groupID']);
                $client->addServerGroup($settings['un_verified_groupID']);
                $client->message(str_replace('%username%', $clientInfo["client_nickname"], $settings['pmsg_unveri']));
            }
        }else{
            if(checkGroup($settings['verified_cheat_groupID'], $clientInfo) && !checkGroup($settings['verified_groupID'], $clientInfo)){
                $client->remServerGroup($settings['verified_cheat_groupID']);
                $client->addServerGroup($settings['verified_groupID']);
                $client->message($settings['pmsg_rem_cheat']);
            }else if(checkGroup($settings['un_verified_groupID'], $clientInfo)){
                $client->remServerGroup($settings['un_verified_groupID']);
                (!checkGroup($settings['verified_groupID'], $clientInfo) ? $client->addServerGroup($settings['verified_groupID']) : '');
                $client->message(str_replace('%username%', $clientInfo["client_nickname"], $settings['pmsg_veri_success']));
            }else if(!checkGroup($settings['verified_groupID'], $clientInfo) && !checkGroup($settings['un_verified_groupID'], $clientInfo) && !checkGroup($settings['verified_cheat_groupID'], $clientInfo)){
                $client->addServerGroup($settings['verified_groupID']);
                $client->message(str_replace('%username%', $clientInfo["client_nickname"], $settings['pmsg_veri_success']));
            }
        }
    } else {
        echo $personaldmsg['invalidclientid'] . "\n";
    }
}

function isCommand($command, $eventText, $client){
    $lenth = strlen($command);
    $command_sub = substr($eventText, 0, $lenth);
    if($command_sub == $command){
        return true;
    }else{
        $client->message("Command [B]" . $eventText . "[/B] not found!");
        return false;
    }
}

function privateMessageEvent($event, $host){
    global $settings;
    $client = $host->serverGetSelected()->clientGetById($event["invokerid"]);
    $clientInfo = $client->getInfo();
    if($clientInfo['client_unique_identifier'] == $settings['masterUID']) {
        if(isCommand("!get Info", $event["msg"], $client)) {
            $client->message("Info...");
        }
    } else {
        $client->message("You are not my Master!");
    }
}
