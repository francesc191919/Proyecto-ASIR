const params = new URLSearchParams(window.location.search);
const id = params.get('id');
const cardPersonaje = document.getElementById("cardPersonaje");
const sinDatos = document.getElementById("sinDatos");
const cargando = document.getElementById("cargando");

// Se realiza una petición AJAX para obtener los datos del personaje
let httpRequest = new XMLHttpRequest;
httpRequest.open("GET", "../php/ServidorJSONRick.php?tipo=detallePersonaje&id=" + id, true); 
httpRequest.setRequestHeader("Content-type", "application/json");
httpRequest.onreadystatechange = function (){
    if (httpRequest.readyState == 4 && httpRequest.status == 200) {
        let datosJSON = JSON.parse(httpRequest.responseText);
        if (datosJSON.name != null){
            cargando.className = "d-none";
            sinDatos.className = "d-none";
            cardPersonaje.className = "d-block";
            cargarDatos(datosJSON);
        } else {
            cargando.className = "d-none";
            sinDatos.className = "d-block";
            cardPersonaje.className = "d-none";
        }
    } else{
        cargando.className = "d-none";
        sinDatos.className = "d-block";
        cardPersonaje.className = "d-none";
    }
};
httpRequest.send(null);
cargando.className = "d-block";

// Función que se encarga de rellenar los datos del personaje en la card
function cargarDatos(datos) {
    document.querySelector("img").src = datos.image;
    document.querySelector("img").alt = datos.name;
    document.querySelector("#nombre").textContent = datos.name;
    document.querySelector("#estado").textContent = datos.status;
    document.querySelector("#especie").textContent = datos.species;
    document.querySelector("#genero").textContent = datos.gender;
    document.querySelector("#tipo").textContent = datos.type ? datos.type : "No definido";
    document.querySelector("#origen").textContent = datos.origin_location.name + " - " + datos.origin_location.dimension;
    document.querySelector("#localizacion").textContent = datos.current_location.name + " - " + datos.current_location.dimension;
    const episodios = datos.episodes.map(episodio => episodio.episode).join(", ");
    document.querySelector("#episodios").textContent = episodios;

}