<?php 
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
		file_put_contents("text.txt", json_encode($this->recipe));
		// Получаем из переданного объекта количество ингридиента
		$this->amount = Database::sanitizeString($object->amount);

	}
	public function saveToDB() {
		$selectEx = "SELECT * FROM ingredients WHERE name = '{$this->name}' and recipe = {$this->recipe->id}";
		$responseDB = Database::query($selectEx);
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

	public static function getIngByRecipe($recipeId) {
		$r_id = intval($recipeId);
		$selectEx = "SELECT * FROM ingredients WHERE recipe = {$r_id}";
		$responseDB = Database::query($selectEx);
		if($responseDB->num_rows > 1) {
			$arrayOfIngredients = [];
			for($i = 0; $i < $responseDB->num_rows; $i++) {
				$responseRow = $responseDB[$i]->fetch_assoc();
				$id = intval($responseRow['id']);
				$name = $responseRow['name'];
				$recipe = intval($responseRow['recipe']);
				$amount = $responseRow['amount'];
				$object = (object)[
					'id' => $id,
					'name' => $name,
					'recipe' => $recipe,
					'amount' => $amount
				];
				array_push($arrayOfIngredients, $object);
			}
			return $arrayOfIngredients;
		}
		else {
			print "Ингредиенты не найдены.";
		}
	}

	public static function ingChange($ingFdb, $recipe, $ingFupd, $amount) {
		$recipe_id = intval($recipe);
		$updtName = Database::sanitizeString($ingFupd);
		$updtAmount = Database::sanitizeString($amount);
		$ing_name = Database::sanitizeString($ingFdb);
		$selectEx = "SELECT * FROM ingredients 
		WHERE recipe = {$recipe_id} and name = '{$ing_name}'";
		$responseDB = Database::query($selectEx);
		if($responseDB->num_rows == 1) {
			$responseRow = $responseDB->fetch_assoc();
			$id_row = intval($responseRow['id']);
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