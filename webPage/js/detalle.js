const params = new URLSearchParams(window.location.search);
const id = params.get('id');
const cardPersonaje = document.getElementById("cardPersonaje");
const sinDatos = document.getElementById("alert");
const cargando = document.getElementById("cargando");

let httpRequest = new XMLHttpRequest;
httpRequest.open("GET", "php/servidorJSONPersonaje.php?id=" + id, true); 
httpRequest.setRequestHeader("Content-type", "application/json");
httpRequest.onreadystatechange = function (){
    if (httpRequest.readyState == 4 && httpRequest.status == 200) {
        let datosJSON = JSON.parse(httpRequest.responseText);
        if (datosJSON[0].name != null){
            cargando.className = "d-none";
            sinDatos.className = "d-none";
            cardPersonaje.className = "d-block";
            cargarDatos(datosJSON[0]);
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

function cargarDatos(datos) {
    document.querySelector("img").src = datos.image;
    document.querySelector("img").alt = datos.name;
    document.querySelector("#nombre").textContent = datos.name;
    document.querySelector("#estado").textContent = datos.status;
    document.querySelector("#especie").textContent = datos.species;
    document.querySelector("#origen").textContent = datos.origin.name;
    document.querySelector("#localizacion").textContent = datos.location.name;
    const episodios = datos.episodios.join(", ");
    document.querySelector("#episodios").textContent = episodios;

}