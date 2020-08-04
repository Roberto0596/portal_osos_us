  $(".select2").select2({
    width: 'resolve'
});


$("#change-period").click(function(){
    swal.fire({
        title: '¿Esta seguro de cambiar el periodo?',
        text: "¡Algunas cosas podrian no funcionar!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, estoy seguro'
    }).then((result)=>{
      if (result.value)
      {
      	$("#change-period").fadeOut(1000,function(){
      		$("#content-period").fadeToggle(1000);
			$("#button").fadeToggle(1000);
      	});        
      }
    });
});

$("#close-period").click(function(){
	$("#content-period").toggle(1000);
	$("#button").toggle(1000);
	$("#change-period").fadeIn(1000);
})