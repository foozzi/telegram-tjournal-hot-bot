<?php


ini_set('display_errors',1);
error_reporting(E_ALL);

//use Telegram\Types\InputFile;

class Fresh implements \Telegram\Commands\ICommand 
{ 
    public function call($name, $arguments, $caller)
    {
    			
		$Api = new Telegram\Api('telegram token');

		$Api->sendChatAction($caller->getChat()->id, 'typing');

		$news = json_decode(file_get_contents('https://api.tjournal.ru/2/news?interval=fresh&count=5'), 1);

		$caller->reply('Последние 5 новостей:'.PHP_EOL);    	


		if(count($news) > 0)
		{
			foreach($news[0]['news'] as $what)
			{
				$caller->reply($what['title'].' - '.$what['url']);    	
			}
		}			
    }

    public function getDescription()
    {
        return 'Свежие новости из топа';
    }
} 

$bot = new Telegram\Bot('telegram token');

$bot->addCommand('свежее', new Fresh());
$bot->work();

