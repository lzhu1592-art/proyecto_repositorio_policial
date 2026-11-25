
const boton_alerta=document.getElementById("boton_alerta");
const volver = document.getElementsByClassName("volver");
if(boton_alerta==null){
    console.log("Un boton alerta esta vacio")
}else{
    boton_alerta.addEventListener("click", () =>{
        window.open("../../ventanas/ventana_aviso.php","Ventana-aviso", "width=200,height=100");
    });
}
volver[0].addEventListener("click", function(event) {
    event.preventDefault();
    history.back();
});
//Esta en matenimiento