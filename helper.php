<?php
require_once 'vendor/autoload.php';
use pimax\Messages\MessageElement;
use pimax\Messages\MessageButton;


function getResults($lat,$lan,$type){
    $google_places = new joshtronic\GooglePlaces('');
    $google_places->location = array($lat, $lan);
    $google_places->types    = $type;     // Requires keyword, name or types
    $google_places->rankby   = 'distance';// we chose to search by distance
    $results                 = $google_places->nearbySearch();

    $send_result=$results['results'];
    for($i=1;$i<10;$i++)
    {
        $lat=$send_result[$i]['geometry']['location']['lat'];
        $lan=$send_result[$i]['geometry']['location']['lng'];
        // we are using google api, in order to get a static maps.
        $url="https://maps.googleapis.com/maps/api/staticmap?center=".$lat.",".$lan."&zoom=20&scale=1&size=600x300&maptype=
        roadmap&key=AIzaSyC7q6K4aMdPA-RGGQjDMNoy
        QIMW6Hxhm6k&format&format
        =png&visual_refresh=true";

        $map="https://www.google.com/maps/place/33%C2%B058'21.9%22N+6%C2%B053'31.1%22W
               /@".$lat.",".$lan.",17.75z/data=!4m5!3m4!1s0x0:0x0!8m2!3d$lat!4d$lan";
        $array_list[$i]= new MessageElement($send_result[$i]['name'],"", $url, [
            new MessageButton(MessageButton::TYPE_WEB, 'see the direction',$map)
        ],$map);

    }

    return $array_list;

}
