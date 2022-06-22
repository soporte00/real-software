const hamburger = document.getElementById('main__menu--hamburger');
const main__menu = document.getElementById('main__menu');
const main__ul = document.getElementById('main__ul');

hamburger.addEventListener('click', ()=>{
	hamburger.classList.toggle('main__menu--hamburger-active');
	main__menu.classList.toggle('main__menu--active');
});