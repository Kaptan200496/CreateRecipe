document.getElementById("addRecipe").onclick = function() {
	var nameRecipe = document.getElementById('nameRecipe').value; 
	var description = document.getElementById('description').value;
	var amount = document.getElementById('amount').value;
	var ingredients = document.getElementById('ingredients').value;

	var stringIng = ingredients.split(" ");

	var dataObject = {
		action: "Создать рецепт",
		name: nameRecipe,
		ingredients: stringIng,
		amount: amount,
		description: description
	};
	var requestJSON = JSON.stringify(dataObject);

	
	var request = new XMLHttpRequest();
	request.open("POST", "index.php", true);
	request.send(requestJSON);
}

document.getElementById("edit").onclick = function() {
	var nameRecipe = document.getElementById('nameRecipe').value; 
	var description = document.getElementById('description').value;
	var amount = document.getElementById('amount').value;
	var ingredients = document.getElementById('ingredients').value;
	var changeIng = document.getElementById('changeIng').value;
	var changeAmount = document.getElementById('changeAmount').value;

	var arrayIng = ingredients.split(" ");
	var arrayIngForChange = changeIng.split(" ");
	var arrayAmountForChange = changeAmount.split(" ");
	var dataObject = {
		action: "Изменить рецепт",
		name: nameRecipe,
		ingredients: arrayIng,
		ingForChange: arrayIngForChange,
		amount: amount,
		amountForChange: arrayAmountForChange,
		description: description
	};
	var requestJSON = JSON.stringify(dataObject);

	
	var request = new XMLHttpRequest();
	request.open("POST", "index.php", true);
	request.send(requestJSON);
}

document.getElementById("getRecipe").onclick = function() {
	var nameRecipe = document.getElementById('nameRecipe').value; 
	var dataObject = {
		action: "Вывести рецепт",
		name: nameRecipe
	};
	var requestJSON = JSON.stringify(dataObject);

	
	var request = new XMLHttpRequest();
	request.open("POST", "index.php", true);
	request.send(requestJSON);
}