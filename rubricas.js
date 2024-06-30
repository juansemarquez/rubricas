document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector('#form-puntajes');
    //form.addEventListener('submit', function(event){
    document.querySelector('#btn_generar').addEventListener('click', function(event) {
        event.preventDefault();
        document.querySelector('#reporte').value = "Confeccionando reporte...";
        const datosFormulario = new FormData(form);
        const url = "procesar_rubrica.php";
        fetch(url, {method: 'POST', body: datosFormulario})
            .then(respuesta => respuesta.json())
            .then( datos => {
                mostrarReporte(datos);
            })
            .catch( error => {console.log(error);});
    });
});


function mostrarReporte(datos)
{
    if (datos.error.length > 0) {
        document.querySelector('#reporte').value = datos.error;
        document.querySelector('#' + datos.error_faltante).focus();
        document.querySelector('#btn-archivos').disabled = true;
    } else {
        document.querySelector('#reporte').value = datos.reporte;
        document.querySelector('#aprobado').value = datos.aprobado ? "APROBADO" : "NO APROBADO";
        document.querySelector('#calificacion').value = datos.calificacion;
        document.querySelector('#btn-archivos').disabled = false;
    }
}

function copiar()
{
    const texto = document.querySelector('#reporte').innerHTML;
    navigator.clipboard.writeText(texto);
}
