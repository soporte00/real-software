/*CFG*/
const URL = document.querySelector("meta[name='url']").getAttribute('content') ;


// Post method Request
async function postRequest(req, data ='') {
	
  	// Opciones por defecto estan marcadas con un *
  	const response = await fetch(`${URL}${req}`, {
    	method: 'POST', // *GET, POST, PUT, DELETE, etc.
    	mode: 'no-cors', // no-cors, *cors, same-origin
    	cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
    	credentials: 'same-origin', // include, *same-origin, omit
    	headers: {
    	  'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' // application/json, application/x-www-form-urlencoded, multipart/form-data, or text/plain
    	},
     	redirect: 'follow', // manual, *follow, error
    	referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
    	body: data// body data type must match "Content-Type" header
  	});
  	return response.json(); // parses JSON response into native JavaScript objects
}


// Put method Request
async function putRequest(req, data ='') {
	
  	// Opciones por defecto estan marcadas con un *
  	const response = await fetch(`${URL}${req}`, {
    	method: 'PUT', // *GET, POST, PUT, DELETE, etc.
    	mode: 'cors', // no-cors, *cors, same-origin
    	cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
    	credentials: 'same-origin', // include, *same-origin, omit
    	headers: {
    	  'Content-Type': 'application/json; charset=UTF-8' // application/json, application/x-www-form-urlencoded, multipart/form-data, or text/plain
    	},
     	redirect: 'follow', // manual, *follow, error
    	referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
    	body: JSON.stringify(data)// body data type must match "Content-Type" header
  	});
  	return response.json(); // parses JSON response into native JavaScript objects
}



// Get method Request
async function getRequest(req){
	const response = await fetch(`${URL}${req}`);
	return response.json();
}



// Obtain form inputs values
function formValues(form){

		form = form.querySelectorAll('textarea, select, input:not([type=submit])')

		const r = {}

		for (var i = form.length - 1; i >= 0; i--) {
			r[form[i].id] = form[i].value
		}

		return r	
}





/*storage*/
function sStorage(key,parameter=null){
	if(typeof(Storage) !== 'undefined'){

		if(parameter !== null){
			sessionStorage.setItem(key, JSON.stringify(parameter));
			return
		}

		let data = sessionStorage.getItem(key);

		if(data !== null){
			return JSON.parse(data);
		}


		return false;
	} 

	console.error('el sessionStorage no se encuentra habilitado')
	return false
}

function storage(id,parameter=null){
	if(typeof(Storage) !== 'undefined'){

		if(parameter !== null){
			localStorage.setItem(id, JSON.stringify(parameter));
			return
		}

		let data = localStorage.getItem(id);

		if(data !== null){
			return JSON.parse(data);
		}


		return false;
	} 

	console.error('el localStorage no se encuentra habilitado')
	return false
}


const noEmpty = (i)=>{if(/[a-zA-Z0-9]+/.test(i)){return true;}else{return false;}}
const isEmail = (i)=>{if(/^[\.\-_a-zA-Z0-9]+@\w+(\.\w{2,4})+$/.test(i)){return true;}else{return false;}}
const isDate = (i)=>{if(/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/.test(i)){return true;}else{return false;}}
const moneyFormat = (i)=>{return new Intl.NumberFormat("de-DE").format(i)}