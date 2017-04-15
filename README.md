# Project Title
Facebook-Bot-chat -NearBy-
## Getting Started
NearBy is a Facebook Bot Chat helper.
If you Are looking around for the closest places to your location, and without leaving 
the messenger. We can Do it For you Just Stay in messenger then we will take care of that.
Our Bot chat is very helpful and easy to use, using the latest components and tools developed by FACEBOOK 
and facilitating the user Experience, where we included Menus and Buttons ....
in order to give the user a full understanding to use it.
So to Get started Just share your location and send your interest in the menu,
 If you need Help or if you want to change your location , feel free to send "help".
 
 ### Installing
The preferred installation is via composer. First add the following to your composer.json.

```
{
    "require": {
        "joshtronic/php-googleplaces": "dev-master",
        "pimax/fb-messenger-php": "dev-master",
        "ktamas77/firebase-php": "dev-master"

    }
}
```
### Setting the DataBase
  As we know already, Facebook doesn't keep a session open with the webhook.
  So we can use "session per request" scope TODO.
  For my case i chose to store the users data in a real time database "Firebase". In order
 To avoid asking them every time to define the location, furthermore we have to provide them the choice
 to change it in every moment they want.
 In addition to this, the first interaction with bot, we'll ask the user to give us his location/o a location in general,
 then we store it for the first time.
 For Devloppers Setting the database:
 * After you create your Firebase database, define an Entity "User" with 5 attributes
 * id     : User ID
 * Lat    : For the latitude
 * Lan    : For the longitude
 * time   : to store the last query's time
 * command: to store the last command asked by the user.
 ```
 {
  "users" : {

    "user_id :<Facebook id >" : {
      "command" : "<last Command>",
      "lan" : "longitude",
      "lat" : "latitude",
      "time" : "date"
    }
  }
}

 ```
 ### Setting the config file "config.php".
 the config file require to define your apps' access token and token
 ### Link

Stay in Messenger and do everything :D Dont leave it ;) 
[NearMyLocation](http://m.me/NearMyLocation)
 
