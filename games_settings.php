<?php

define('GAMES_SETTINGS', array(
    'lucky_number' => array('name'=>'Glückszahl', 'routing'=>'game_lucky_number', 'wait_minutes'=>30),
    'head_or_tail' => array('name'=>'Kopf oder Zahl', 'routing'=>'game_head_or_tail', 'wait_minutes'=>false)
));

/* 
 * English:
 * ======== 
 * Add your self-created games here. 
 * 
 * First give your game a unique key - like lucky_number or head_or_tail, thats the name of the game
 * like it will be refered to in the database. IMPORTANT!! Do not change it later on.
 * 
 * name => name of the game how it will be displayed on the website
 * routing => put your previously created routing key here (/routing.php)
 * wait_minutes => waiting time in minutes inbetween two game rounds - if is set to false, the game is playabel only once a day
 * ______________________
 * 
 * Deutsch:
 * ========
 * Hier ggf. weitere selbst erstellte Spiele einfügen.
 *
 * Zuerst gibt dem Spiel einen eindeutigen Schlüssel - wie lucky_number oder head_or_tail, das ist der
 * Name des Spiels wie es in der "games" Tabelle der Datenbank geführt wird. WICHTIG!! Bitte nicht nachträglich ändern.
 *
 * name => Name des Spiels wie es auf der Seite dargestellt wird.
 * routing => Gib die festgelegte Kennung für die Route des Spiels an. (/routing.php)
 * wait_minutes => Wartezeit in Minuten zwischen zwei Spielrunden - wenn false gesetzt wird, dann ist das Spiel nur einmal täglich spielbar
 */
?>