if(typeof(String.prototype.trim) === "undefined")
{
    String.prototype.trim = function() 
    {
        return String(this).replace(/^\s+|\s+$/g, '');
    };
}

function clearInput(name) {
	// clear a specified input box of it's defualt value when a user clicks the field
    var input;
	input = document.getElementsByName(name)[0];
	input.value = ''; // blank out the input value
}

function setInputVal(name, val) {
	// set the input box to display specified text as it's 'value'
    var input;
	input = document.getElementsByName(name)[0];
	input.value = val;
}






