// logout function 
document.getElementById('logoutbtn').addEventListener('click',()=>{
    confirmation('Seguro que quieres cerrar tu sesión?', ()=>{ 
        document.location.href=`${URL}/acm/logout/`
    })
})