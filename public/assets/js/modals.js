var modal = (status = true) => {

	const modalContainer = document.getElementById('modalcontainer');
	modalContainer.children.modal.innerHTML = ''

	
	if(status == false){
		modalContainer.classList.add('modal__hide')
		return false;
	}


	modalContainer.classList.remove('modal__hide')
	
	return modalContainer.children.modal
}

document.getElementById('modalclose').addEventListener('click', (e)=>{
	e.target.parentNode.classList.add('modal__hide')
})






function confirmation(message, fn) {
	const confirmationBox = document.createElement("div");
	confirmationBox.innerHTML = `<div class='confirmation-modal'>
			<div class='confirmation_container'>
				<span class='confirmation_txt'>${message}</span>
				<button id='confirmationCancel' class='btn st-natural'>Cancelar</button>
				<button id='confirmationOk' class='btn st-warning'>Ok</button>
			</div>
		</div>`;
	document.body.appendChild(confirmationBox);

	document.getElementById('confirmationCancel').addEventListener("click", () => { document.body.removeChild(confirmationBox); });
	document.getElementById('confirmationOk').addEventListener("click", () => { fn(); document.body.removeChild(confirmationBox); });
}