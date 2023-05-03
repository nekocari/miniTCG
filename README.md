# miniTCG

An automated Trading Card Game Application written in PHP, covering most of the basics.
This type of TCG is not meant to play with the cards, but to collect and trade them.
miniTCG is using Bootstrap 4 for styling and FontAwesome for icons.

(Because of the DSGVO in Germany you should consider hosting Bootstrap and the FontAwesome iconfont yourself!)

*Note, that this startet as an all german project, so you might stumble upon some german text elements in the code! 
But it should be mostly english by now and the views are being translated as well, with options to add in other languages in the future.*


---

## Table of contents
[How to Setup](#how-to-setup)  
[Current Features](#current-features)    
[Features TBA](#features-tba)  
[Used Libs & Co.](#used-libs--co)    
[Authors](#authors)  
[Special Thanks](#special-thanks)  

---

## How to Setup
* open config/constants.php and update it with your information
* upload all files via ftp to your server
* open browser and go to your miniTCG installation /setup/setup.php
* follow the instructions
* delete the setup folder
* login and head to the administration -> settings first to customize the applikation
* look through all the other options and customize the app even more. :)



## Current Features

* public functions
  * responsive basic design
  * language switch automatic
  	* according to browser settings if translation exists
  * homepage
  * view news
  * deck list
  * member list
  * display online members
* login system
  * sign up
  * sign in
  * sign out
  * activation codes via mail
  * reset password
* administration
  * manage news
    * connect updates to them
  * manage members
    * search
    * manage rights 
    * gift random cards
    * gift money
    * edit profil data
    * reset passwords
    * delete accounts
  * manage decks
    * create (with file upload)
    * edit data
    * manage card updates
  * manage games
    * change game settings
    * manage lucky games (options + results)
    * add new lucky games (no coding required)
    * add new game settings for custom games
  * manage application
    * main categories and subcategories
    * level
    * deck types
      * create, edit, delete individual types
      * change deck sizes
      * change image sizes
      * change filler type and image paths
      * create and edit html templates withrom within the app
    * card manager categories
      * rename, create or delete them
      * set if visible in member profil
      * etc.
    * general settings 
      * card folder path
      * currency  
      * shop 
      * etc.
  	* manage routing
  	* SQL import option for later updates
* member functions
  * view decks (may also be made public)
    * put deck on wishlist
  * vote for upcoming decks
  * view member profils (may also be made public)
  	* make trade offers
  		* info for each offerable card if it's usefull
  * manage cards
  	* pre selection keep/collect
  	* highlighting keep/collect/wish/mastered
  	* master decks
  	* search link for missing collect cards
  * manage trade offers
  	* reply to recieved offers
  	* withdraw made offers
  * messages
  	* write 
  	* inbox
  	* outbox
  * view master cards
  	* sort by date or deck name
  * take cards from updates
  * play games
  	* 4 simple games included
  * buy cards from a shop
  * search for cards
  * tradelog
  * automated level ups
  * edit own data
  	* profil data
  	* language and timezone settings
  	* change password
  * delete account

   
## Features TBA
* tagging on decks
* allow comments on news
* add decks without uploading files
* sort & search options for lists
* add reset option for shop
* add individualization for handing out update cards
* add simple pages without the need to mess the controller files


## Used Libs & Co.
[Parsedown](https://github.com/erusev/parsedown) by erusev on GitHub  
Bootstrap 4.1.3 (CDN)  
jQuery 3.2.1 (CDN)  
FontAwesome 5.15.3 (CDN)  

## Authors

* **Carina Patzina** - *Initial work* - [NekoCari](https://github.com/nekocari)


## Special Thanks
Goes to **Maron** for first testing!  
And **Kasuna** who was planning on using it
