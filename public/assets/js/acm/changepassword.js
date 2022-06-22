const newpassword = document.getElementById('newpassword')
const renewpassword = document.getElementById('renewpassword')
 
    const minlength = 5
    let validpass = false
    
   const checksame = ()=>{

        if(renewpassword.value == newpassword.value && newpassword.value.length >= minlength){
            renewpassword.classList.remove('st-danger')
            renewpassword.classList.add('st-fine')
            validpass = true
          }else{
            renewpassword.classList.add('st-danger')
            validpass = false
          }
    }

    renewpassword.addEventListener('keyup', checksame)
    newpassword.addEventListener('keyup', checksame)
    document.getElementById('chPassForm').addEventListener('submit',(e)=>{

        e.preventDefault()

        checksame()

        if(password.value.length > 0){

            if(validpass){
                
                // Send Data
                postRequest('/acm/changemypassword/',`password=${password.value}&newpassword=${newpassword.value}`)
                .then(r=>{
                    if(!r.response){
                        mssg(r.message)
                        return false;
                    }

                    mssg(r.message)

                    setTimeout(()=>{document.location.href=URL;} , 600);	
                })

            }else{
                mssg('La nueva clave no coincide o está vacía','e')
            }

        }else{
            mssg('El campo de clave actual no puede estar vacío','e')
        }



    })