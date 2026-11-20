const boton_alerta = document.getElementById("boton_alerta");

boton_alerta.addEventListener("click", () =>{
   window.open("../ventanas/ventana_aviso.php","Ventana-aviso", "width=500,height=525");
    //Falta poder cerrar la ventana emergente al hacer click en el boton aceptar o cancelar, pero no se como hacerlo
});
