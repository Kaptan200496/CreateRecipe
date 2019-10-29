<?php 
// class-response-provider.php
// Класс для работы с ответом клиенту от сервера

class ResponseProvider {
	// Результат запроса
	private static $result;
	// Массив событий (журнал)
	private static $messages = array();

	// Метод для добавления события в журнал
	// $message (object:SystemMessage) - сообщение для добавления в лог
	public static function appendToLog($message) {
		// Добавляем в массив наше сообщение
		array_push(self::$messages, $message);
	}

	// Метод для задания результата запроса
	// $value (mixed) - результат выполнения запроса
	public static function setResult($value) {
		self::$result = $value;
	}

	// Метод для вывода информации клиенту
	public static function output() {
		// Ассоциативный массив для вывода
		$outputArray = array();

		// Если задан результат, добавляем его к выводу
		if(isset(self::$result)) {
			$outputArray["result"] = self::$result;
		}

		// Если есть сообщения, добавляем их к выводу
		if(!empty(self::$messages)) {
			$outputArray["messages"] = self::$messages;
		}

		// Преобразовываем в JSON, если массив не пустой
		$outputString = empty($outputArray) ? "{}" : json_encode($outputArray, JSON_PRETTY_PRINT);
		// Отсылаем заголовки для обозначения, что это JSON
		header("Content-Type", "application/json");
		// Выводим ответ
		print $outputString;
	}
}

?>
