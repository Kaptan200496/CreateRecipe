<?php
class Recipe {
	// id в базе данных
	// integer
	public $id;

	// Название рецепта
	// string
	public $name;

	// Описание рецепта
	// Optional. String.
	public $description;

	// Конструктор объекта 
	function __construct($object) {
		// Если передается объект с полем id , то записываем его 
		if(isset($object->id)) {
			$this->id = intval($object->id);
		}
		// Записываем название рецепта в поле name, предварительно проверяя его через real_escape_string
		$this->name = Database::sanitizeString($object->name);
		// Если передаваемый объект содержит поле описания , то записываем его
		if(isset($object->description)) {
			$this->description = Database::sanitizeString($object->description);
		}
	}

	public  function saveToDB() {
		// записываем в переменную выражение для проверки сохраняемого рецепта в базе
		$selectEx = "SELECT * FROM recipes WHERE name = '{$this->name}'";
		$responseDB = Database::query($selectEx);
		// Если в базе нет записей об этом рецепте, то записываем его в базу.
		if($responseDB->num_rows == 0) {
			$insertEx = "INSERT INTO recipes (
				name,
				description
			) VALUES (
				'{$this->name}',
				'{$this->description}'
			)";
			Database::query($insertEx);
			return Database::$connection->insert_id;
		}
		// Если в базе есть такой рецепт, то отвечаем что рецепт уже есть
		else {
			print "Рецепт уже записан";
		}
	}

	public static function getByName($recipe) {
		// Очищаем поступившие данные для безопасности нашей БД
		$clean_name = Database::sanitizeString($recipe);
		// Создаем выражение для вытягивания из базы рецепта
		$selectEx = "SELECT * FROM recipes WHERE name = '{$clean_name}'";
		$response = Database::query($selectEx);
		// Если рецепт найден , то отдаем его , если нет , то отвечаем что рецепта в базе нет
		if($response->num_rows == 1) {
			$responseRow = $response->fetch_assoc();
			$id = $responseRow['id'];
			$name = $responseRow['name'];
			$description = $responseRow['description'];
			$recipeObject = (object)[
				"id" => $id,
				"name" => $name,
				"description" => $description
			];
			$returnObject = new Recipe($recipeObject);
			return $returnObject;
		}
		else {
			print "Рецепт не найден.";
		}
	}

		public static function getById($id) {
		// Очищаем поступившие данные для безопасности нашей БД
		$clean_id = intval($id);
		// Создаем выражение для вытягивания из базы рецепта
		$selectEx = "SELECT * FROM recipes WHERE id = {$clean_id}";
		$response = Database::query($selectEx);
		// Если рецепт найден , то отдаем его , если нет , то отвечаем что рецепта в базе нет
		if($response->num_rows == 1) {
			$responseRow = $response->fetch_assoc();
			$id = $responseRow['id'];
			$name = $responseRow['name'];
			$description = $responseRow['description'];
			$recipeObject = (object)[
				"id" => $id,
				"name" => $name,
				"description" => $description
			];
			$returnObject = new Recipe($recipeObject);
			return $returnObject;
		}
		else {
			print "Рецепт не найден.";
		}
	}
}
?>