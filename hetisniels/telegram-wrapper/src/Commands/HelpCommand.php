<?php
namespace Telegram\Commands;

use Telegram\Types\InputFile;

class HelpCommand implements ICommand
{
	/**
	 * @param string $name
	 * @param mixed[] $arguments
	 * @param CommandCaller $caller
	 */
	public function call($name, $arguments, $caller)
	{
		$response = '';
		$asReply = true;
		$commands = $caller->getBot()->getCommands();

		if (isset($arguments[0])) {
			if (strtolower($arguments[0]) == 'botfather') {
				$asReply = false;

				foreach ($commands as $name => $handler) {
					$response .= sprintf('%s - %s' . PHP_EOL, $name, $handler->getDescription());
				}
			} else if (isset($commands[strtolower($arguments[0])])) {
				$response .= '/' . $arguments[0] . PHP_EOL . PHP_EOL;
				$response .= $commands[strtolower($arguments[0])]->getDescription();
			} else {
				$response .= 'Существующие команды "' . $arguments[0] . '"' . PHP_EOL . PHP_EOL;

				$matches = 0;
				foreach ($commands as $name => $handler) {
					if (strpos($name, $arguments[0]) !== false) {
						$matches++;

						$response .= sprintf('/%s - %s' . PHP_EOL, $name, $handler->getDescription());
					}
				}

				if ($matches == 0)
					$response = 'Не найдено такой команды "' . $arguments[0] . '".';
			}
		} else {
			if ($name != 'help' && $name != 'start')
				$response .= 'Команда "' . $name . '" не найдена.' . PHP_EOL . PHP_EOL;

			$response .= 'Этот бот понимает следующие команды: ' . PHP_EOL . PHP_EOL;

			foreach ($commands as $name => $handler) {
				$response .= sprintf('/%s - %s' . PHP_EOL, $name, $handler->getDescription());
			}
		}		

		$Api = new \Telegram\Api('telegram token');
		
		$replyMarkup = array(
		    'keyboard' => array(
		        array("/свежее")
		    ),
		    'resize_keyboard' => true
		);		

		$encodedMarkup = json_encode($replyMarkup);

		$f = InputFile::fromFile('https://pp.vk.me/c624527/v624527314/e2b6/rx7_4R-ypkY.jpg');	

		$Api->sendSticker($caller->getChat()->id, $f, null, $encodedMarkup);

		$caller->reply($response, $asReply);		
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return 'Описание всех команд';
	}
}