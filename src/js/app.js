let paso = 1;
let pasoInicial = 1;
let pasoFinal = 3;

const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
};

document.addEventListener('DOMContentLoaded', () => {
    initApp();
});

function initApp(){
    // Mostrando contenido;
    mostrarSeccion();

    // Cambia contenido en funcion del tab
    tabs();

    // Agrega o quita botones de paginacion
    botonesPaginador();

    // Paginacion
    paginaSiguiente();
    paginaAnterior();

    // Consulta a la API
    consultarAPI();

    // Añadiendo id cliente a la cita
    idCliente();

    // Añadiendo nombre cliente a la cita
    nombreCliente();

    // Seleccionando y agregando fecha a la cita
    seleccionarFecha();

    // Seleccionando y agregando hora a la cita
    seleccionarHora();

    // Muestra resumen de la cita
    mostrarResumen();
}

function tabs(){
    const buttonTabs = document.querySelectorAll('.tabs button');

    buttonTabs.forEach(button => {
        button.addEventListener('click', seleccionandoContenidoTab);
    });
}

function seleccionandoContenidoTab(e){
    paso = parseInt(e.target.dataset.paso);
    
    mostrarSeccion();
    botonesPaginador();

    if(paso === 3){
        mostrarResumen();
    }
}

function mostrarSeccion(){
    // Ocultando seccion que tenga clase mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    const tabAnterior = document.querySelector('.actual');

    if(seccionAnterior && tabAnterior){
        seccionAnterior.classList.remove('mostrar');
        tabAnterior.classList.remove('actual');
    }

    const seccion = document.querySelector(`#paso-${paso}`);
    seccion.classList.add('mostrar');

    // Resaltando tab actual
    const tabActual = document.querySelector(`[data-paso="${paso}"]`);
    tabActual.classList.add('actual');
}

function botonesPaginador(){
    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');

    if(paso === 1){
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }else if(paso === 3){
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');

        mostrarResumen();
    }else{
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }

    mostrarSeccion();
}

function paginaSiguiente(){
    const paginaSiguiente = document.querySelector('#siguiente');

    paginaSiguiente.addEventListener('click', () => {
        // Evita paginacion con numeros negativos
        if(paso >= pasoFinal) return;

        // Mueve pagina hacia atras
        paso++;

        botonesPaginador();
    });
}

function paginaAnterior(){
    const paginaAnterior = document.querySelector('#anterior');

    paginaAnterior.addEventListener('click', () => {
        // Evita paginacion con numeros negativos
        if(paso <= pasoInicial) return;

        // Mueve pagina hacia atras
        paso--;

        botonesPaginador();
    });
}

async function consultarAPI(){
    try {
        const url = `${location.origin}/api/servicios`;
        const consulta = await fetch(url);

        const respuesta = await consulta.json();
        mostrarServicios(respuesta);
    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios){
    servicios.forEach(servicio => {
        const {id, nombre, precio} = servicio

        // Creando elementos
        const nombreServicio = document.createElement('p');
        nombreServicio.textContent = nombre;
        nombreServicio.classList.add('nombre-servicio');

        const precioServicio = document.createElement('p');
        precioServicio.textContent = precio;
        precioServicio.classList.add('precio-servicio');

        const servicioCard = document.createElement('div');
        servicioCard.classList.add('servicio');
        servicioCard.dataset.idServicio = id;
        servicioCard.onclick = () => {
            selecccionarServicio(servicio);
        }

        servicioCard.appendChild(nombreServicio);
        servicioCard.appendChild(precioServicio);

        // Agregando al contenedor
        const serviciosContainer = document.querySelector('#servicios');
        serviciosContainer.appendChild(servicioCard);
    });
}

function selecccionarServicio(servicio){
    const {id} = servicio;
    const {servicios} = cita;

    // Seleccionando el elemento
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    // Verificando si un servicio fue agregado
    if(servicios.some(agregado => agregado.id === id)){
        // Eliminarlo
        cita.servicios = servicios.filter(agregado => agregado.id !== id);
        divServicio.classList.remove('seleccionado');
    }else{
        // Agregando los datos del servicio a la cita
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add('seleccionado');
    }
}

function idCliente(){
    const id = document.querySelector('#id').value;

    // Agregando a cita
    cita.id = id;
}

function nombreCliente(){
    const nombre = document.querySelector('#nombre').value;

    // Agregando a cita
    cita.nombre = nombre.trimStart();
}


function seleccionarFecha(){
    const fechaInput = document.querySelector('#fecha');
    fechaInput.addEventListener('input', (e) => {
        const dia = new Date(e.target.value).getUTCDay();

        // Evitando citas los fines de semana
        if([6, 0].includes(dia)){
            e.target.value = '';
            mostrarAlerta('error', 'Fines de semana no permitidos', '.formulario');
        }else{
            cita.fecha = e.target.value;
        }
    });
}

function seleccionarHora(){
    const horaInput = document.querySelector('#hora');
    horaInput.addEventListener('input', (e) => {
        const horaCita = e.target.value;
        const hora = horaCita.split(':')[0];
        
        // Evitando horarios no disponibles
        if(hora < 10 || hora > 18){
            mostrarAlerta('error', 'Horas no validas', '.formulario');
        }else{
            cita.hora = e.target.value;
        }       
    })
}

function mostrarAlerta(tipo, msg, elem, desaparece = true){
    // Evitando alertas repetidas
    const alertaPrevia = document.querySelector('.alerta');

    if(alertaPrevia) {
        alertaPrevia.remove();
    };

    const alerta = document.createElement('div');
    alerta.textContent = msg;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    // Agregando alerta al elemento de referencia
    const referencia = document.querySelector(elem);
    referencia.appendChild(alerta);

    if(desaparece){
        // ELiminando alertas
        setTimeout(() => {
            alerta.remove()
        }, 3000);
    }
    
}

function mostrarResumen(){
    const resumenElem = document.querySelector('.contenido-resumen');

    // Limpiando contenido anterior del resumen
    while(resumenElem.firstChild){
        resumenElem.removeChild(resumenElem.firstChild);
    }

    if(Object.values(cita).includes('') || cita.servicios.length === 0){
        mostrarAlerta('error', 'Faltan datos de servicios, fecha u hora', '.contenido-resumen', false);
        return;
    }

    // Formatear resumen
    const { nombre, fecha, hora, servicios } = cita;

    // Creando elementos y agregandolos
    const nombreCliente = document.createElement('p');
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

    // Formateando fecha en el cliente
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() + 2;
    const year = fechaObj.getFullYear();

    const opciones = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};
    const fechaUTC = new Date(Date.UTC(year, mes, dia));
    const fechaFormateada = fechaUTC.toLocaleDateString('es-MX', opciones);

    const fechaCliente = document.createElement('p');
    fechaCliente.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

    const horaCliente = document.createElement('p');
    horaCliente.innerHTML = `<span>Hora:</span> ${hora} horas`;

    // Heading para los servicios en resumen
    const headingServicios = document.createElement('h3');
    headingServicios.textContent = 'Resumen de servicios';
    resumenElem.appendChild(headingServicios);

    // Mostrando los servicios
    servicios.forEach(servicio => {
        const { id, nombre, precio } = servicio;
        const contenedorServicio = document.createElement('div');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('p');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('p');
        precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumenElem.appendChild(contenedorServicio);
    });

    // Heading de la cita
    const headingCita = document.createElement('h3');
    headingCita.textContent = 'Resumen de la cita';
    resumenElem.appendChild(headingCita);

    // Boton para crear cita
    const btnReservar = document.createElement('button');
    btnReservar.classList.add('btn');
    btnReservar.textContent = 'Reservar cita';
    btnReservar.onclick = reservarCita;

    resumenElem.appendChild(nombreCliente);
    resumenElem.appendChild(fechaCliente);
    resumenElem.appendChild(horaCliente);
    resumenElem.appendChild(btnReservar);
}

async function reservarCita(){
    const {id, fecha, hora, servicios} = cita;
    const idServicios = servicios.map(servicio => servicio.id);

    // Datos a enviar
    const datos = new FormData();
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('usuario_id', id);
    datos.append('servicios', idServicios);

    // Peticion a la api

    try {
        const url = `${location.origin}/api/citas`;
    
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });
    
        const resultado = await respuesta.json();
    
        if(resultado.resultado){
            Swal.fire({
                icon: "success",
                title: "Cita Creada",
                text: "Tu cita fue creada correctamente",
                button: 'Ok'
            }).then(() => {
                window.location.reload()
            });
        }
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Hubo un error al guardar la cita",
            button: 'Ok'
        })
    }
}