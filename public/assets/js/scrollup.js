const scrollupbtn = document.getElementById('scrollupbtn')

window.onscroll = function() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        scrollupbtn.classList.remove('undisplay')
    } else {
        scrollupbtn.classList.add('undisplay')
    }

}


scrollupbtn.addEventListener('click',()=>{
    document.body.scrollTop = 0;
    window.scrollTo({top: 0, behavior: 'smooth'});
})