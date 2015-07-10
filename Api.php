<?php
require('autoload.php');

$bot = new Telegram\Api('telegram token');

while(true)
{
	$u = $bot->getUpdates(); // Returns array of Update

	if(count($u) > 0)
	{
		require_once('Bot.php');
	}
}
