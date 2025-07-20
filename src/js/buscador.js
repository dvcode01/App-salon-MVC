

document.addEventListener('DOMContentLoaded', () =>{
    initApp();
});


function initApp(){
    buscarFecha();
}


function buscarFecha(){
    const fechaInput = document.querySelector('#fecha');
    fechaInput.addEventListener('input', seleccionandoFecha);
}

function seleccionandoFecha(e){
    const fechaSeleccionada = e.target.value;
    
    window.location = `?fecha=${fechaSeleccionada}`;
}










