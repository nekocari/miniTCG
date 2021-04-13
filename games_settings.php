<?php
/* Hier ggf. weitere selbst erstellte Spiele einfügen
 * 
 * Zuerst gibt dem Spiel einen eindeutigen Schlüssel - wie lucky_number oder head_or_tail, das ist der
 * Name des Spiels wie es in der "games" Tabelle der Datenbank geführt wird. WICHTIG!! Bitte nicht nachträglich ändern.
 * 
 * name => Name des Spiels wie es auf der Seite dargestellt wird.
 * routing => Gib die in der Routen Klasse zuvor festgelegte Kennung für die URL des Spiels an.
 * wait_minutes => Wartezeit in Minuten zwischen zwei Spielrunden - wenn false gesetzt wird, dann ist das Spiel nur einmal täglich spielbar
 */
define('GAMES_SETTINGS', array(
    'lucky_number' => array('name'=>'Glückszahl', 'routing'=>'game_lucky_number', 'wait_minutes'=>30),
    'head_or_tail' => array('name'=>'Kopf oder Zahl', 'routing'=>'game_head_or_tail', 'wait_minutes'=>false)
));

?>