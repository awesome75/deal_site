function clearInput(name) {
  // clear a specified input box of it's defualt value when a user clicks the field
	input = document.getElementsByName(name);
	input.value = ''; // blank out the input value
}