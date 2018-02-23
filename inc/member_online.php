<?php 
// Get online members
require_once 'helper/members_online.php';
$members_online = new MembersOnline(Db::getInstance());

// Get template
$template = file_get_contents(PATH.'views/templates/member_online_temp.php');

foreach($members_online->getOnlineMembers() as $online_member){
    echo str_replace('[NAME]', $online_member->name, $template);
}

?>