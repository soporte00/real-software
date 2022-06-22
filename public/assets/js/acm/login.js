const formLogin = document.getElementById('formLogin');

formLogin.addEventListener('submit',(e)=>{
	e.preventDefault();

	let user = e.target.user.value.trim()
	let password = e.target.password.value.trim()


	if(user.length < 4 || password.length < 4) return mssg('Los campos deben contener al menos 4 dígitos. No uses espacios en blanco.', 'e')
	

	postRequest('/acm/login/', `user=${user}&password=${password}`)
	.then(r=>{

		if(!r.response) return mssg(r.message)

		mssg(`Bienvenido/a ${r.response}`)	
		sStorage('userName', r.response)
		
		setTimeout(()=>{document.location.href=formLogin.dataset.onlogin;} , 600);		

	});

});