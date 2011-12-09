/*
** Simple Form Evaluation Framework 0.1 by Chase Higgins
** chasehx@gmail.com, http://twitter.com/tzDev
*********************************************************
** Usage is simple. All you have to do is include this script in your page, 
** then create a 'type' array. Right now, the types supported are 'alpha'<alphabetic>,
** 'alphanum'<alphanumeric>, 'numeric'<integer>, and the special types 'email'<email address>,
** and 'phone'<phone number>. Next you would pass the form name specified by the name attribute
** in the form tag, along with the 'types' of the text fields. A simple form consisting of name,
** email, and phone could easily be checked like so:
	<script type="text/javascript" src="path_to_validator.js">
	function checkForm() {
		types = new Array();
		types[0] = 'alpha';
		types[1] = 'email';
		types[3] = 'phone';
		formChecker = new verifyForm('form_name', types);
		return formChecker.autoVerify();
	</script>
***********************************************************
** assuming your form has an onSubmit event to the checkForm function ie "return checkForm();"
** then you will have error checking enabled. The form will be stopped from submission and the 
** incorrect field will be highlighted and your user prompted to correct the information. It is 
** that easy. The script is easy to modify, please make it better :)
**********************************************************
** You can also easily use the script as a framework to evaluate input fields not necessarily 
** linked to a form. You may have:
	<script type="text/javascript" src="path_to_validator.js">
		formChecker = new verifyForm('form_name', '');
	</script>
	<input type="text" name="first_name" onFocus="formChecker.verifyAlpha(this.value);" />
	<input type="text" name="email" onFocus="formChecker.verifyEmail(this.value);" />
** one thing to note with this is you would typically have a function around the formChecker
** method to do something when it returns true/false. However from within that function you can 
** use the various verifyForm methods like verifyAlpha(), verifyAlphaNum(), verifyNum(),
** verifyPhone(), verifyEmail()
*********************************************************
TODO:
	** There are some funky quircks with the JS regex that makes some fields allow 
	 ** improper data,l ike a numeric string of '2343e'
	** No benchmarks have been performed, I am sure there are some speed improvements to be made
	** Although I am security conscience, this script was written just in one day, so I still
	 ** need to make some security improvements to ensure no XSS or SQLi can happen here
	** The script supports Chrome, Safari, Opera, Firefox, IE 8+9 and all tested smart phone browsers
	 ** One issue is IE7. There is an issue that allows the form to submit even though the script
	 ** told the browser not to.
*/

function verifyForm(form_name, data_types) {
	// form verification is derived from the Simple Form Validation Framework By Chase Higgins
	parent_form = document.getElementsByName(form_name)[0];
	// now we have our parent form, let us get a count of the input fields
	inputs = parent_form.getElementsByTagName('input');
	text_fields = new Array();
	// iterate them and only save the text type inputs
	ctrl = 0;
	for (i = 0; i < inputs.length; i++) {
		type = inputs[i].getAttribute('type');
		classval = inputs[i].getAttribute('class');
		if (type == 'text' && classval != 'noreq') {
			// this is a text field
			text_fields[ctrl] = inputs[i];
			ctrl++;
		}
	}
	// we should have a good list of fields now, make sure they match the types count
	if (text_fields.length != data_types.length) {
		// this would typically mean the data types array passed here was misaligned
		alert('Potentially misaligned types array passed, can not parse and verify fields');
		return 0;
	}
	// if that checks out we should be good to go
	// define how to parse various fields
	var regexParse = function(regex, field) {
		m = regex.exec(field);
		if (m != null) {
			return true;
		}
		else {
			return false;
		}
	}
	// regexParse will work as a base for creating other parsing methods
	this.verifyAlpha = function(field) {
		regex = /[A-Za-z]+/;
		return regexParse(regex, field);
	}
	this.verifyAlphaNum = function(field) {
		regex = /[\w]+/;
		return regexParse(regex, field);
	}
	this.verifyNum = function(field) {
		regex = /[\d]+/;
		return regexParse(regex, field);
	}
	// those are our generic parsing methods, now let us define special ones for phone numbers etc
	this.verifyPhone = function(field) {
		if (field.length < 7) {
			// well we can rule that out right away can't we?
			return false;
		}
		regex = /1?\(?([\d]{3})\)?.?([\d]{3}).?([\d]{4})/; // ha phone numbers..
		return regexParse(regex, field);
	}
	this.verifyEmail = function(field) {
		regex = /([\w.-]+)@([\w.-]+)\.([\w]{2,3})/;
		return regexParse(regex, field);	
	}
	// I believe that should cover the needed parsing methods for the data types our form verification lib supports
	// now we need to make a method that will put it all together
	this.autoVerify = function() {
		for (i = 0; i < text_fields.length; i++) {
			// first we determine the data type of this field
			type = data_types[i];
			switch(type) {
				case 'alpha':
					clean = this.verifyAlpha(text_fields[i].value);
					break;
				case 'alphanum':
					clean = this.verifyAlphaNum(text_fields[i].value);
					break;
				case 'numeric':
					clean = this.verifyNum(text_fields[i].value);
					break;
				case 'phone':
					clean = this.verifyPhone(text_fields[i].value);
					break;
				case 'email':
					clean = this.verifyEmail(text_fields[i].value);		
					break;
				default:
					return("Unsupported type passed to data_types array");
			}
			// now we check to make sure this item verified
			if (clean == false) {
				// oh no, the data did not clear, handle the problem
				if (text_fields[i].getAttribute('error') == '1') {
					// we have already highlighted this field etc, for some reason the user doesn't get it :)
					return false;
				}
				name = text_fields[i].getAttribute('name');
				// set the border of failed box to red, let the user know it is required
				text_fields[i].setAttribute('style', 'border: 1px solid red;');
				//span = document.createElement('span');
				//span.innerHTML = "<b>Required</b>";
				//span.setAttribute('style', 'color: red; padding-left: 5px;');
				//text_fields[i].getParent('td').appendChild(span);
				//text_fields[i].parentNode.appendChild(span);
				// set the action for when the user clicks on the box to correct the issues
				text_fields[i].onclick = function() {
					text_fields[i].setAttribute('style', 'border: 1px inset;');
					//text_fields[i].getParent('td').removeChild(span);
					//text_fields[i].parentNode.removeChild(span);
					text_fields[i].setAttribute('error', 0);
				}
				// finally mark the field as error flagged
				text_fields[i].setAttribute('error', 1);
				return false;
			}
		}
	}
}	
