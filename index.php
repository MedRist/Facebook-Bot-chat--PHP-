<?php
/**
 * Created by PhpStorm.
 * User: MedRist
 * Date: 4/1/2017
 * Time: 1:28 PM
 */
 require_once(dirname(__FILE__) . '/vendor/autoload.php');
 include "helper.php";
 include "Reader.php";
 use pimax\FbBotApp;
 use pimax\Messages\Message;
 use pimax\Messages\StructuredMessage;
 use \pimax\Messages\QuickReply;


$verify_token = ""; // Verify token
$token = ""; // Page token

if (file_exists(__DIR__.'/config.php')) {
    $config = include __DIR__.'/config.php';
    $verify_token = $config['verify_token'];
    $token = $config['token'];
}




/*
 * Create the instance of the chat app
 */

$bot = new FbBotApp($token);


/*
 * Webhook setup request
*/
$hub_verify_token = null;


if(isset($_REQUEST['hub_challenge'])&&$_REQUEST['hub_mode'] == 'subscribe') {
    $challenge = $_REQUEST['hub_challenge'];
    $hub_verify_token = $_REQUEST['hub_verify_token'];
}



if ($hub_verify_token === $verify_token) {
    echo $challenge;
}else{
    //Get the message
    $input= file_get_contents('php://input');
    //decode the json message
    $json_content=json_decode($input,true);
    // ID
    $id=$json_content['entry'][0]['id'];
    // test if the filed of Messaging is empty or not, in the json object "$json_content.
    if(!empty($json_content['entry'][0]['messaging'])){

        foreach ($json_content['entry'][0]['messaging'] as $message){
            //Here we have to handle the request received of every Type of message.

            // We are interested in "The Get started message" & "the normal requests from the user" & the postbacks messages

            if (!empty($message['message'])) {

                if (!empty($message['message']['text'])){

                     handl_sample_request($message,$bot);

                }
                if (!empty($message['message']['attachments']))
                {
                    __session_init($message,$id,$bot);
                }


                //The get Started Message
            } else if (!empty($message['postback'])) {
                // one time execution, when the use push the button GetStarted
                  if ($message['postback']['payload']=='GET_STARTED_PAYLOAD'){
                        handl_Get_Started($message,$bot);
                  } else {
                      // In the database, we store the location given by the users. in order to not ask them for the location
                      //every time or that will be annoying.
                      //but we have to provide the choice to change it if they want.
                      // else we keep searching by using the last location sent by them.
                      handl_postback_requests($message,$bot);
                  }
            }


      }
    }
}

  function __session_init($message, $id,$bot)

  {
       if ($message['sender']['id'] != $id) {

           $lat=$message['message']['attachments'][0]['payload']['coordinates']['lat'];
           $lan =$message['message']['attachments'][0]['payload']['coordinates']['long'];
           insert_new_user($message['sender']['id'],$lat,$lan,time());
           $bot->send(new Message($message['sender']['id'],"check the menu, to choose your interest"));


       } else return;


  }

   function handl_Get_Started($message,$bot)

   {
       $user = $bot->userProfile($message['sender']['id']);
       $name=$user->getFirstName();

       $bot->send(new Message($message['sender']['id'],"Hello ".$name." Let's get started."));
       $bot->send(new Message($message['sender']['id'],"Are you looking around for the closest places to your location, Got it. Stay in messenger and i'll do it for you. Choose your interest in the menu, and i'll do the rest :D, If you need Help or if you want to change your location , feel free to send \"help\""));
   }


function handl_postback_requests($message,$bot)
{

    $command = $message['postback']['payload'];
    $id      = $message['sender']['id'];

    // before we handle the requests, we have to check if the type of searching has been chosen by the user TODO
    // and we check if user is registred ot not
       if(get_user($id)!="-1")
       {
         //user is registred
           set_last_command($id,$command);
           $array_list=getResults(get_lat($id),get_lang($id),$command);
           $bot->send(new StructuredMessage($message['sender']['id'],
               StructuredMessage::TYPE_GENERIC,
               [
                   'elements' => $array_list
               ]));

       } else//user not in database
       {

           $array=["content_type"=>"location",];
           $bot->send(new QuickReply($message['sender']['id'],"share your location please",Array ($array) ));
       }


}

function handl_sample_request($message,$bot)
{

       $id      = $message['sender']['id'];
       $command = $message['message']['text'];
       switch ($command) {
           // When bot receive "help"
           case 'help':
               $array=["content_type"=>"text","title"=>"Help","payload"=>"help"];
               $arr=["content_type"=>"location","title"=>"Change Location","payload"=>"location"];
               $bot->send(new QuickReply($id,"Check the menu to choose your interest, or if you want to change your location",Array ($array,$arr) ));
               break;
           case 'hello':
               $bot->send(new Message($id, 'Hello, back'));
               break;
           default :
               $bot->send(new Message($message['sender']['id'],"Sorry we could not respond to your request, tap help or check the menu"));

       }

}
