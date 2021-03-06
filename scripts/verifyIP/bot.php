<?php
/*
 * Author: Michael aka SossenSystems
 * Version: TeamSpeak Verify to Special IP
 * Info: for questens, issues and pull requests visit https://github.com/SossenSystems/planetteamspeak-scripts/tree/master/scripts/verifyIP
 */

require_once("ts3phpframework-1.1.32/libraries/TeamSpeak3/TeamSpeak3.php");
require_once("config.php");
require_once("functions.php");

try {
    print_r("Starting bot...\n");
    $ts3_VirtualServer = TeamSpeak3::factory("serverquery://" . $query['username'] . ":" . $query['password'] . "@" . $query['ipAddress'] . ":" . $query['port'] . "/?server_port=" . $query['voicePort'] . "&nickname=" . urlencode($query['nickname']) . "&blocking=0");
    TeamSpeak3_Helper_Signal::getInstance()->subscribe("serverqueryWaitTimeout", "onTimeout");
    TeamSpeak3_Helper_Signal::getInstance()->subscribe("notifyCliententerview", "onJoin");
    TeamSpeak3_Helper_Signal::getInstance()->subscribe("notifyTextmessage", "onTextmessage");
    $ts3_VirtualServer->serverGetSelected()->notifyRegister("server");
    $ts3_VirtualServer->notifyRegister("textprivate");
    print_r("Bot started successfully!\n");
    while (1) $ts3_VirtualServer->getAdapter()->wait();
}
catch(TeamSpeak3_Transport_Exception $e){
    $erromsg = $e->getMessage();
    if(isset($erromsg) && $erromsg == "Connection refused"){
        print_r("[ERROR] Message from Framework: " . $erromsg . "\nThe query is not accessible.\nThe server is probably offline...\n");
    } else {
        print_r("[ERROR]  " . $e->getMessage() . "\n");
    }
}
catch(Exception $e)
{
    print_r("[ERROR2]  " . $e->getMessage() . "\n");
}

function stopBot(){
    echo "Stopping Bot...\n";
    sleep(1);
    exit();
}

function onTimeout($seconds, TeamSpeak3_Adapter_ServerQuery $adapter) {
    $last = $adapter->getQueryLastTimestamp();
    $time = time();
    $newtime = $time-300;
    $update = $last < $newtime;
    if($update)
    {
        $adapter->request("clientupdate");
    }
}

function onJoin(TeamSpeak3_Adapter_ServerQuery_Event $event, TeamSpeak3_Node_Host $host)
{
    checkVerify(getEvent($event, $host), $host);

}

function onTextmessage(TeamSpeak3_Adapter_ServerQuery_Event $event, TeamSpeak3_Node_Host $host)
{
    privateMessageEvent($event, $host);
}
