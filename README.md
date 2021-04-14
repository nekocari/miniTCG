# miniTCG

An automated Trading Card Game Application written in PHP, covering most of the basics.
miniTCG is using Bootstrap 4 for styling and FontAwesome for icons.

(Because of the DSGVO you should consider hosting Bootstrap and the FontAwesome iconfont yourself!)

*Note that this is a german project, so files will contain mostly german text elements!*
If you are interested in an english translation, please let me know.

**The Application is still under development!**

## How to setup
* Go to inc/constants.php and set the constants for the App. See comments in the file for details.
* Upload everything to your webspace using a FTP programm.
* Run *yourwebsitehere.com*/setup/setup.php
* Now you should be able to login using the default Admin account.
* Visit the administration area *yourwebsitehere.com*/admin/ (or click the link in the navigation) and change the application settings to your liking.
* **Delete** the *setup* folder!



## Current Features

* login system
* admin panel
  * manage decks incl. file upload
  * manage cardupdates
  * manage level
  * manage news
  * manage categories (and subcategories)
  * manage app settings
  	* size of cardimages
  	* size of deck 
  	* name the currency  
  	* etc.
  * manage members
  	* manage rights 
  	* gift random cards
  	* edit profil data
  	* delete accounts
* public functions
  * homepage
  * display news
  * deck list
  * member list
  * display online members
* member functions
  * view decks
  * messages (inbox)
  * manage cards
  	* pre selection keep/collect cards
  	* highlighting keep/collect cards
  * master decks
  * take cards from updates
  * search cards
  * buy cards in a cardshop
  * tradelog
  * edit member data (+ password change)
  * make trade offers
  * respond to trade offers 
  * delete account
  * automated level ups

   
## Features TBA
* enable messaging between members
* admins can reset passwords
* admins can gift "money"
* allow comments on news
* add activation codes into registration process 


## Used Libs & Co.
[Parsedown](https://github.com/erusev/parsedown) by erusev on GitHub  
Bootstrap 4.1.3 (CDN)  
jQuery 3.2.1 (CDN)  
FontAwesome 5.15.3 (CDN)  

## Authors

* **Carina Patzina** - *Initial work* - [NekoCari](https://github.com/nekocari)


## Special Thanks
Goes to **Maron** for first testing!  
Big shoutout to **Kasu** :3 who's supporting miniTCG by using it for her upcoming tcg!
[Disciples of Art](https://doa.darkcharms.de/)
