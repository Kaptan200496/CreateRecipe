<?php 
// class-system-message.php
// Класс для системных сообщений

class SystemMessage {
	// Константы уровней
	// Константы принято писать заглавными буквами
	// Чтобы не запоминать номер уровня, а обращаться SystamMessage::WARNING_LEVEL
	const EMERGENCY_LEVEL = 0;
	const ALERT_LEVEL = 1;
	const CRITICAL_LEVEL = 2;
	const ERROR_LEVEL = 3;
	const WARNING_LEVEL = 4;
	const NOTICE_LEVEL = 5;
	const INFORMATIONAL_LEVEL = 6;
	const DEBUG_LEVEL = 7;

	// Общее количество сообщений
	private static $messagesAmount = 0;
	// Уровни сообщений
	private static $messageLevelStrings = array(
		0 => "emergency",
		1 => "alert",
		2 => "critical",
		3 => "error",
		4 => "warning",
		5 => "notice",
		6 => "informational",
		7 => "debug"
	);

	// Время события (integer), unix timestamp
	public $timestamp;
	// Номер события (integer)
	public $number;
	// Уровень события (string)
	public $level;
	// Сообщение (string)
	public $message;
	// Детали (mixed)
	public $details;

	// Создание нового сообщения
	public function __construct($message, $details = "", $level = 6) {
		// Задаём номер сообщения
		// ++x - прединкремент. Увеличивает значение переменной на 1, потом возвращает значение
		$this->number = ++self::$messagesAmount;
		// Задаём timestamp
		$this->timestamp = time();
		// Задаём текст сообщения
		$this->message = $message;
		// Задаём уровень сообщения
		$this->level = self::$messageLevelStrings[$level];
		// Задаём детали
		$this->details = $details;
	}
}
?>
