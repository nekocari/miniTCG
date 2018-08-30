<?php 
// Get online members
require_once 'helper/members_online.php';
$members_online = new MembersOnline(Db::getInstance());

// Get template
$template = file_get_contents(PATH.'views/templates/member_online_temp.php');

foreach($members_online->getOnlineMembers() as $online_member){
    echo str_replace(array('[NAME]','[URL]'), array($online_member->getName(), $online_member->getProfilLink()), $template);
}

?>