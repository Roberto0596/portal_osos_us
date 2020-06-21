$(".next").click(function(){
	$(".step_one").css("display","none");
	$(".step_two").css("display","block");
	$(this).html("!Listo!");
	$(this).attr("type","submit");
	$(this).removeClass("next");
});