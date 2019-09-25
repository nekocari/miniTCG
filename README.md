# miniTCG

A very basic automated Trading Card Game Application written in PHP. 
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
* Visit the administration area *yourwebsitehere.com*/admin/ (or click the link on the bottom) and change the Application settings to your liking.
* Delete the *setup* folder!



## Current Features

* Login System
  * Sign Up
  * Sign In
  * Sign Out
* Admin Panel
  * manage Decks incl. file upload
  * manage Cardupdates
  * manage Level
  * manage News
  * manage Categories (and Subcategories)
  * manage TCG Settings (like size of decks)
  * manage Rights for each Member
  * give Random Cards to Members
  * edit Member data
  * delete Accounts
* Public Functions
  * Homepage
  * display News
  * Deck list
  * Member list
  * display online Members
* Member Functions
  * view Decks
  * Messages (Inbox)
  * manage Cards
  * master Decks
  * take Cards from Updates
  * Tradelog
  * edit Member data (+ Password change)
  * make Trade offers
  * respond to Trade offers 
  * delete Account
  * automated Level Ups

   
## Features TBA
* enable messaging between members
* admins can reset passwords
* add activation codes into registration process 


## Authors

* **Carina Patzina** - *Initial work* - [NekoCari](https://github.com/nekocari)
