
// Se añaden los listeners a los elementos de la página
const filtrarNombre = document.getElementById("filtrarNombre");
filtrarNombre.addEventListener("input", ajaxJsonRellenarDatos);
const filtrarEpisodio = document.getElementById("filtrarEpisodio");
ajaxJsonRellenarEpisodios();
filtrarEpisodio.addEventListener("change", ajaxJsonRellenarDatos);
const personajesContainer = document.getElementById("personajesContainer");
const cardTemplate = document.getElementById("cardTemplate");
ajaxJsonRellenarDatos();

const quitrarFiltros = document.getElementById("quitarFiltros");
quitrarFiltros.addEventListener("click", function() {
    filtrarNombre.value = "";
    filtrarEpisodio.value = "";
    ajaxJsonRellenarDatos();
})

// Función que se encarga de rellenar los datos de los personajes en la página
function ajaxJsonRellenarDatos() {
	let nombre = filtrarNombre.value;
    let episodio = filtrarEpisodio.value;
	let httpRequest = new XMLHttpRequest;
	httpRequest.open("GET", "php/ServidorJSONRick.php?tipo=personajes&nombre=" + nombre + "&episodio=" + episodio, true); 
	httpRequest.setRequestHeader("Content-type", "application/json");
	httpRequest.onreadystatechange = function (){
        // Si la petición ha terminado y el estado es 200 (OK)
        // Se parsea el JSON y se rellenan los datos en la página
		if (httpRequest.readyState == 4 && httpRequest.status == 200) {
            let datosJSON = JSON.parse(httpRequest.responseText);
            let hijosResultado = personajesContainer.children;
            while (hijosResultado.length > 0){
                hijosResultado[0].parentNode.removeChild(hijosResultado[0]);
            }
            if (datosJSON.length > 0 && datosJSON[0].name != null){
                for (let i = 0; i < datosJSON.length; i++) {
                    let card = crearCard(datosJSON[i]);
                    personajesContainer.appendChild(card);
                }
            } else {
                // Si no se han encontrado personajes, se muestra un mensaje de error
                let h2 = document.createElement("h2");
                h2.className = "text-danger";
                let textoh2 = document.createTextNode("Error: Ha ocurrido un error en la búsqueda")
                h2.appendChild(textoh2);
                personajesContainer.appendChild(h2);
            }
        // Si la petición ha terminado pero el estado no es 200 (OK)
        // Se muestra un mensaje de error
        } else{
            let h2 = document.createElement("h2");
            h2.className = "text-danger";
            let textoh2 = document.createTextNode("Error: No se ha podido acceder a la información")
            h2.appendChild(textoh2);
            personajesContainer.appendChild(h2);
        }
	};
    // Se muestra un mensaje de espera mientras se procesa la petición
	httpRequest.send(null);
	let parrafo = document.createElement("p");
	let textoEspera = document.createTextNode("Espera de procesamiento JSON ...");
	parrafo.appendChild(textoEspera);
	personajesContainer.appendChild(parrafo);
}

// Función que se encarga de crear una card con los datos del personaje
function crearCard(datos) {
    const cardHTML = cardTemplate.content.cloneNode(true);
    cardHTML.querySelector("img").src = datos.image;
    cardHTML.querySelector("img").alt = datos.name;
    cardHTML.querySelector("h5").textContent = datos.name;
    cardHTML.querySelector("a").href = "views/detalle.html?id=" + datos.id;
    cardHTML.querySelector(".card-text").textContent = datos.status + " - " + datos.species;
    return cardHTML.firstElementChild;
}

// Función que se encarga de rellenar los episodios en el select de la página
function ajaxJsonRellenarEpisodios() {
    let httpRequest = new XMLHttpRequest;
    httpRequest.open("GET", "php/ServidorJSONRick.php?tipo=episodios", true); 
    httpRequest.setRequestHeader("Content-type", "application/json");
    httpRequest.onreadystatechange = function (){
        if (httpRequest.readyState == 4 && httpRequest.status == 200) {
            let datosJSON = JSON.parse(httpRequest.responseText);
            for (let i = 0; i < datosJSON.length; i++) {
                let episodio = document.createElement("option");
                episodio.value = datosJSON[i].id;
                episodio.text = datosJSON[i].episode + " - " + datosJSON[i].name;
                filtrarEpisodio.appendChild(episodio);
            }
        } else if (httpRequest.readyState == 4) {
            console.log("Error en la petición JSON");
        }
    };
    httpRequest.send(null);
}