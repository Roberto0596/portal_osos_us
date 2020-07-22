$(".next").click(function()
{
	$(".step_one").fadeOut(500,function()
	{
		$(".step_two").fadeIn(500);
	});

	$(".next").html("!Listo!");
	$(".next").attr("disabled","disabled");
	$(".next").removeClass("next");
});

$(document).ready(function(){
$("#password").keyup(function(){

	var passwordTwo = $(this).val();
	if (passwordTwo.length>0)
	{
		var passwordOne = $("#first").val();
		console.log(passwordTwo);
		if (passwordOne == passwordTwo)
		{
			$("#validate").empty();
			$("#validate").append("<div class='alert alert-success' role='alert'>"+
							  "Las contraseñas coinciden"+
							"</div>");
			$(".sent").removeAttr("disabled");
			$(".sent").attr("type","submit");
			$(".sent").focus();
		}
		else
		{
			if (passwordTwo.length >= passwordOne.length)
			{
				$("#validate").empty();
				$("#validate").append("<div class='alert alert-danger' role='alert'>"+
							  "Las contraseñas no coinciden"+
							"</div>");
				$(".sent").attr("type","button");
			}
			$(".sent").attr("disabled","disabled");
		}
	}
});	
})

 $('#matricula').mask('00-00-0000');