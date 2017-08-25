// JavaScript Document
var duracion = 5;
var contador = 0;
var controlImagen = 2;
var cantidadImagenes = 3;
var usuario = null;
var contrasena = null;
var caracteresEspeciales = new Array();
$(function(){
	$('#iniciarSesion').on('shown.bs.modal', function () {
            $('#txtUsuario').focus()
        });
	//Deshabilito el click derecho.
//	document.oncontextmenu = function(){return false};
	
	cargarCaracteresEspeciales();
	
	$("#pwdContrasenia").bind({
		"keypress":function(e){
			if(e.keyCode == 13){
				if(validarVacios() == false)
					return false;
				$("#btnIngresar").click();
				return false;
			}
		}
	});
	$("#txtUsuario").bind({
		"keypress":function(e){
			if(e.keyCode == 13){
				if(validarVacios() == false)
					return false;
				$("#btnIngresar").click();
				return false;
			}
		}
	});
	$("#btnIngresar").bind({
		"click":function(){
			if(validarVacios() == false)
					return false;
			
			if(validarCaracteresEspeciales($("#txtUsuario").val()) == false)
				return false;
				
			if(validarCaracteresEspeciales($("#pwdContrasenia").val()) == false)
				return false;
				
			obtenDatosEnvio();
			$.ajax({
				url: 'entorno/validarIngreso.php',
				data:data,
				type:'POST',
				dataType:"json",
				success: function(json){
					var exito = json.exito;
					var mensaje = json.mensaje;
					
					if(exito == 0){
						alertaIndex (mensaje);
					}else{
						window.location = 'vista/frmInicio.html';
					}
				},error: function(xhr,opciones,error){
					alerta("Error al ingresar al aplicativo , Contáctese con el administrador");
				}
			});
		}
	});
});
function asignarValores(){
	usuario = $("#txtUsuario").val();
	contrasena = $("#pwdContrasenia").val();
}
function obtenDatosEnvio(){
	asignarValores();
	data = 'Usuario=' + usuario + '&Contrasena=' + contrasena;
}
function alertaIndex(mensaje){
	bootbox.alert(mensaje);
}
function validarVacios(){
	if(document.getElementById("txtUsuario").value == ""){
		alertaIndex("Por favor digite el usuario.");
		return false;
	}
	if(document.getElementById("pwdContrasenia").value == ""){
		alertaIndex("Por favor digite la contraseña.");
		return false;
	}
return true;
}
function validarCaracteresEspeciales(texto){
	for(var i = 0; i < caracteresEspeciales.length; i++) {
		if(texto.indexOf(caracteresEspeciales[i]) != -1){
			alertaIndex("Usuario y/o contraseña inválida");
			return false;
		}
	}
	return true;
}
//Lleno el arreglo con los carácteres que se deben validar
function cargarCaracteresEspeciales(){
	$.ajax({
		url:'/Seguridad/controlador/index.cargarCaracter.php',
		type:'POST',
		dataType:"json",
		data:null,
		success: function(json){
			var exito = json.exito;
			var mensaje = json.mensaje;
			if(exito == 0){
				alertaIndex(mensaje);
				return false;
			}
			$.each(json.caracteres,function(contador, fila){
				caracteresEspeciales[contador] = fila;
			});
		}
	});
}