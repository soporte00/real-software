function navSearch(append=null,id=null,submitOnClean=true){

	let searchForm = document.createElement('FORM')
	searchForm.setAttribute('class','nav__search--container')
	if(id != null) searchForm.setAttribute('id', id)

	const input = document.createElement('input')
	input.setAttribute('type', 'text')
	input.setAttribute('class', 'nav__search--textInput')
	input.setAttribute('placeholder', 'Buscar')
	input.setAttribute('autocomplete', 'off')

	const clear = document.createElement('span')
	clear.setAttribute('class', 'nav__search--clearButton nav__search--hidde')
	clear.setAttribute('title', 'Limpiar el buscador')
	clear.textContent = 'x'

	const subtn = document.createElement('input')
	subtn.setAttribute('type','submit')
	subtn.setAttribute('class','nav__search--hidde')


	searchForm.appendChild(input)
	searchForm.appendChild(clear)
	searchForm.appendChild(subtn)

	if(append != null)append.appendChild(searchForm)


	clear.addEventListener('click',()=>{
		input.value=''
		input.classList.remove('nav__serach--inputFilled')
		clear.classList.add('nav__search--hidde')
		if(submitOnClean) subtn.click()
	})

	searchForm.addEventListener('submit', (e)=>{
		e.preventDefault()
		if( input.value.trim() > ''){
			input.classList.add('nav__serach--inputFilled')
			clear.classList.remove('nav__search--hidde')
		}
	})
	


	return searchForm
}



function navCheckAmongInput(inp){
	if( isDate(inp.value) ){
		inp.type='date';
	}else{
		inp.type='text';
	}
}

const navAmong = (append=null, id=null)=>{

	const nav = document.createElement('form')

	if(id != null) nav.setAttribute('id', id)

	nav.innerHTML = `<div class='navigator'>
					 <input type='text' name='amongDateA' placeholder='Desde:' class='amongdate' onfocus=\"this.type='date';\" onblur=\"navCheckAmongInput(this)\" onchange=\"navCheckAmongInput(this)\">
					 <input type='text' name='amongDateB' placeholder='Hasta:' class='amongdate' onfocus=\"this.type='date';\" onblur=\"navCheckAmongInput(this)\" onchange=\"navCheckAmongInput(this)\">
					 <input type='submit' value='&#128269;'>
					 </div>
					`;

	if(append != null)document.getElementById(append).appendChild(nav)

return nav;
}


const pages = ()=>{
	console.log('pages')
}






console.log('nav')

function listItem(find,form = navSearch ){


	const searchBar = form


	searchBar.addEventListener('submit',(e)=>{
		e.preventDefault()

		
			getRequest(`${find}/search=${e.target.firstChild.value}`)
			.then(r => {
				

				console.log(r)

				// if(r.response.list.length == 0){
				// 	// not found item
				// 	// return false;
				// }
			
		
			})
	
	})


	return searchBar
}