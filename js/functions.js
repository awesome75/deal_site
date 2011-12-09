function clearInput(name) {
	// clear a specified input box of it's defualt value when a user clicks the field
	input = document.getElementsByName(name)[0];
	input.value = ''; // blank out the input value
}

function setInputVal(name, val) {
	// set the input box to display specified text as it's 'value'
	input = document.getElementsByName(name)[0];
	input.value = val;
}