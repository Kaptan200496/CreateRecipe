<?php 
/* Файл подключает все классы и файлы для работы с базой , получает данные, обрабатывает их и в зависимости от поступивших данных выполняет действие(Создание и сохранение рецепта изменение рецепта и вывод его)
*/
// Массив подключаемых файлов
$includes = [
	"class-ingredients.php",
	"class-recipe.php",
	"class-database.php",
	"class-settings-provider.php",
	"class-response-provider.php",
	"class-debugger.php",
	"class-system-message.php"
];
// Цикл для подлючения файлов
foreach($includes as $fileToInclude) {
	require_once("includes/" . $fileToInclude);
}
// Файл настроек
require_once("settings.php");
// Подключение к базе
Database::connect();
// Переменная для данных которые приходят на сервер
$requestData = file_get_contents('php://input');
// Декодируем json который пришел
$requestObject = json_decode($requestData);
// Предзаготавливаем переменные с данными для упрощения работы
$action = $requestObject->action;
$recipeName = $requestObject->name;
$ingredientsNames = $requestObject->ingredients;
$amounts = isset($requestObject->amount) ? $requestObject->amount : "NULL" ;
$description = isset($requestObject->description) ? $requestObject->description : "NULL" ;
$ingForChange = isset($requestObject->ingForChange) ? $requestObject->ingForChange : "NULL";
$amountForChange = isset($requestObject->amountForChange) ? $requestObject->amountForChange : "NULL";

// Выполняем скрипт в зависимости от переданного $action
if($action === "Создать рецепт") {
	// Создаем объект рецепта
	$recipe = new Recipe($requestObject);
	// Вызываем метод saveToDB для сохранения в базе
	$recipe->saveToDB();
	// Достаем объект рецепта по имени для того что бы узнать id
	$recipeObj = Recipe::getByName($recipeName);
	// Пока ингредиенты есть в массиве ингредиентов перебираем их  в цикле и создаем объект ингредиентов ,занося его в базу
	for($i = 0; $i < count($ingredientsNames); $i++) {
		$object = (object)[
			"recipe" => $recipeObj->id,
			"name" => $ingredientsNames[$i],
			"amount" => $amounts[$i]
		];
		$ingredients = new Ingredients($object);
		$ingredients->saveToDB();
	}
	print "Рецепт создан.";
}

else if($action === "Изменить рецепт") {
	// Достаем оббъект рецепта по названию
	$recipe = Recipe::getByName($recipeName);
	// Циклом перебираем ингредиенты пока они есть, так же перебираем ингредиенты для замены и их количество
	for($i = 0; $i < count($ingredientsNames); $i++) {
		$ingredients = $ingredientsNames[$i];
		$ingredientChange = $ingForChange[$i];
		$amountChange = $amountForChange[$i];
		// Методу ingChange передаем навзание ингредиента, айди рецепта и изменяемые данные
		Ingredients::ingChange($ingredients, $recipe->id, $ingredientChange, $amountChange);
	}
	print "Рецепт изменен.";
}
else if ($action === "Вывести рецепт") {
	// ПОлучаем обхект рецепта по иимени заносим в переменную его айди
	$recipeObject = Recipe::getByName($recipeName);
	$recipeId = $recipeObject->id;
	// Получаем ингредиенты по айди рецепта
	$getIng = Ingredients::getIngByRecipe($recipeId);
	// Создаем объект ответа 
	$response = (object)[
		"recipe" => $recipeObject,
		"ingredient" => $getIng
	];
	// Отвечаем
	print json_encode($response);

}
?>