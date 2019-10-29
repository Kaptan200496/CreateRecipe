<?php 
// class-debugger.php
// Класс для отладки

class Debugger {
	public static function registerCall() {
		// Проверяем, включена ли отладка
		if(!SettingsProvider::getSetting("debug/enabled")) {
			return;
		}

		// Получаем весь стек вызовов
		$callsArray = debug_backtrace();
		// Получаем вызов функции, откуда вызван registerCall
		$targetCall = $callsArray[1];
		// Формируем строку с сообщением
		$functionString = "Вызов функции " . $targetCall["class"] . ":" . $targetCall["function"];
		// Создаём объект сообщения
		$message = new SystemMessage($functionString, $targetCall, SystemMessage::DEBUG_LEVEL);
		// Регистрируем сообщение с логе
		ResponseProvider::appendToLog($message);
	}

	public static function debugMessage($message, $details, $level = SystemMessage::DEBUG_LEVEL) {
		// Проверяем, включена ли отладка
		if(!SettingsProvider::getSetting("debug/enabled")) {
			return;
		}

		// Создаём объект сообщения
		$messageObject = new SystemMessage($message, $details, $level);
		// Регистрируем сообщение с логе
		ResponseProvider::appendToLog($messageObject);
	}
}

?>
