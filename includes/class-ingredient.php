<?php 
/* Файл является классом для Ингредиентов , создание и занесение в базу вытягивание из базы по айди рецепта, изменение рецепта.
*/
class Ingredients {
	// id Ингридиента в базе
	// integer
	public $id;
	// id рецепта в базе, к которому относится ингридиент
	// integer
	public $recipe;
	// Название ингридиента
	// string
	public $name;
	// Количество данного ингридиента
	// string
	public $amount;

	function __construct($object) {
		// Если передается объект с полем id , то записываем его 
		if(isset($object->id)) {
			$this->id = intval($object->id);
		}
		// Записываем название рецепта в поле name, предварительно проверяя его через real_escape_string
		$this->name = Database::sanitizeString($object->name);
		// Получаем из переданного объекта id  и по нему, с помощью класса Recipe достаем объект рецепта
		$this->recipe = Recipe::getById($object->recipe);
		// Получаем из переданного объекта количество ингридиента
		$this->amount = Database::sanitizeString($object->amount);

	}
	// Метод для занесения в базу объекта ингредиентов
	public function saveToDB() {
		// Выражение дл проверки нахождения в базе этого ингредиента
		$selectEx = "SELECT * FROM ingredients WHERE name = '{$this->name}' and recipe = {$this->recipe->id}";
		$responseDB = Database::query($selectEx);
		// При отсутствии в базе таког оингредиента записываем его 
		if($responseDB->num_rows == 0) {
			$insertEx = "INSERT INTO ingredients (
				recipe,
				name,
				amount
			) VALUES (
				{$this->recipe->id},
				'{$this->name}',
				'{$this->amount}'
			)";
			Database::query($insertEx);
		}
		else {
			print "Ингридиент уже добавлен.";
		}
	}
	// Метод для вытягивания ингредиентов по айди 
	public static function getIngByRecipe($recipeId) {
		// Проверяем переданый нам айди перед внесением запросов в базу
		$r_id = intval($recipeId);
		$selectEx = "SELECT * FROM ingredients WHERE recipe = {$r_id}";
		$responseDB = Database::query($selectEx);
		if($responseDB->num_rows > 1) {
			// СОздаем пустой массив для заполнения из цикла
			$arrayOfIngredients = [];
			for($i = 0; $i < $responseDB->num_rows; $i++) {
				$responseRow = $responseDB->fetch_assoc();
				$id = intval($responseRow['id']);
				$name = $responseRow['name'];
				$recipe = intval($responseRow['recipe']);
				$amount = $responseRow['amount'];
				//СОздаем объект для заполниня массива
				$object = (object)[
					'id' => $id,
					'name' => $name,
					'recipe' => $recipe,
					'amount' => $amount
				];
				array_push($arrayOfIngredients, $object);
			}
			// Возвращаем заполненый массив
			return $arrayOfIngredients;
		}
		else {
			print "Ингредиенты не найдены.";
		}
	}
	// Метод для изменения рецепта 
	// $ingFdb = string  Ингридиент из базы
	// $recipe = int id Рецепта
	// $ingFupd = string Ингредиент для апдейта
	// $amount = string количество дял замены
	public static function ingChange($ingFdb, $recipe, $ingFupd, $amount) {
		// Проверяем перед подачей запроса поданый айди
		$recipe_id = intval($recipe);
		// Проверяем данные перед подачей их в запрос
		$updtName = Database::sanitizeString($ingFupd);
		$updtAmount = Database::sanitizeString($amount);
		$ing_name = Database::sanitizeString($ingFdb);
		// Запрос для получения строки с ингридиентом
		$selectEx = "SELECT * FROM ingredients 
		WHERE recipe = {$recipe_id} and name = '{$ing_name}'";
		$responseDB = Database::query($selectEx);
		if($responseDB->num_rows == 1) {
			$responseRow = $responseDB->fetch_assoc();
			$id_row = intval($responseRow['id']);
			// Запрос для перезаписи ингридиента с новыми значениями в рецепт
			$updateEx = "UPDATE ingredients SET name = '{$updtName}', 
			amount = '{$updtAmount}' WHERE id = {$id_row}";
			Database::query($updateEx);
		}
		else {
			print "Ингредиент не найден.";
		}
	}
}
?>