<?php 
$includes = [
	"class-ingredients.php",
	"class-recipe.php",
	"class-database.php",
	"class-settings-provider.php"
];
foreach($includes as $fileToInclude) {
	require_once($fileToInclude);
}
require_once("settings.php");
// Подключиться к базе
Database::connect();
$requestData = file_get_contents('json_recipe.json');
$requestObject = json_decode($requestData);

$action = $requestObject->action;
$recipeName = $requestObject->name;
$ingredientsNames = $requestObject->ingredients;
$amounts = $requestObject->amount;
$description = $requestObject->description;
$ingForChange = isset($requestObject->ingForChange) ? $requestObject->ingForChange : "NULL";
$amountForChange = isset($requestObject->amountForChange) ? $requestObject->amountForChange : "NULL";


if($action === "Создать рецепт") {
	$recipe = new Recipe($requestObject);
	$recipe_id = $recipe->saveToDB();
	for($i = 0; $i < count($ingredientsNames); $i++) {
		$object = (object)[
			"recipe" => $recipe_id,
			"name" => $ingredientsNames[$i],
			"amount" => $amounts[$i]
		];
		$ingredients = new Ingredients($object);
		$ingredients->saveToDB();
	}
	print "Рецепт создан.";
}
else if($action === "Изменить рецепт") {
	$recipe = Recipe::getByName($recipeName);
	for($i = 0; $i < count($ingredientsNames); $i++) {
		$ingredients = $ingredientsNames[$i],
		$ingredientChange = $ingForChange[$i];
		$amountChange = $amountForChange[$i];
		Ingredients::ingChange($ingredients, $recipe->id, $ingredientChange, $amountChange);
	}
	print "Рецепт изменен.";
}
?>