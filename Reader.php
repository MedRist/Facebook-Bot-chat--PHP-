<?php
require('vendor/autoload.php');
/*
 * As we know already, Facebook doesn't keep a session open with the webhook.
 * So we can use "session per request" scope TODO.
 * For my case i chose to store the users data in a real time database "Firebase". In order
 * To avoid asking them every time to define the location, furthermore we have to provide them the choice
 * to change it every moment they want.
 * In addition to this, the first interaction with bot, we'll ask the user to give us his location/o a location in general,
 * then we store it for the first time.
 For Devloppers :
 * After you create your Firebase database, define an Entity "User" with 4 attributes
 * id: User ID
 * Lat    :For the latitude
 * Lan    :For the longitude
 * time   :to store the last query's time
 * command: to store the last command asked by the user.
 */

 function insert_new_user($id,$lat,$lan,$t)
{

    $url="";
    $firebase = new \Firebase\FirebaseLib($url);
    $result=$firebase->set("/users/$id",["lat"=>$lat,"lan"=>$lan,"time"=>$t,"command"=>""]);
    return true;

}

function set_last_command($id,$command)
{

    $url="";
    $firebase = new \Firebase\FirebaseLib($url);
    $result=$firebase->update("/users/$id",["command"=>$command]);
    return true;

}
function get_user($id)
{

    $url="";
    $firebase = new \Firebase\FirebaseLib($url);
    $result=$firebase->get("/users/$id");
    if ($result != "null")
        return json_decode($result,true)['time'];
    else return "-1";
}
function get_lat($id)
{

    $url="";
    $firebase = new \Firebase\FirebaseLib($url);
    $result=$firebase->get("/users/$id");
    if ($result != "null")
        return json_decode($result,true)['lat'];
    else return "-1";
}

function get_lang($id)
{

    $url="";
    $firebase = new \Firebase\FirebaseLib($url);
    $result=$firebase->get("/users/$id");
    if ($result != "null")
        return json_decode($result,true)['lan'];
    else return "-1";
}
