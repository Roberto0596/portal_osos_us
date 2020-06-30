var step = 0;

$(document).ready(function(){
	$("#hidden-2").css("display","none");
})

$("#next").click(function()
{
	$("#extra").fadeOut(500);
	$(this).fadeOut(500);
	$("#hidden-1").fadeOut(500,function(){
		$("#hidden-2").fadeIn(500);
		$("#back").fadeIn(500);
	});
	// $(this).attr("data-toggle","modal");
	// $(this).attr("data-target","#modalPago");
	step = 1;
});

$("#back").click(function()
{
	$(this).fadeOut(500,function(){
		$("#hidden-2").fadeOut(500,function(){
			$("#next").fadeIn(500);
			$("#extra").fadeIn(500);
			$("#hidden-1").fadeIn(500);
		});
	});
});

$("#payment-card").click(function(){
	console.log("hola");
	$("#modalCard").modal("show");
});