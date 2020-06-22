var passwordFlag = true;

$("#first").focus(function()
{
	if (passwordFlag==true)
	{
		toastr.info("Intenta que sea una contraseña que nunca olvides");
		passwordFlag = false;
	}
})

var timer = 10000;

var feeds = $(".feed");

feeds.css("display","none");

var i = 0;
var max = feeds.length;

$(feeds[i]).css("display","block");

setInterval(function(){ 

	if (i < max)
	{
		hiddeInterval(i);
		i++;
	}
	else
	{
		i = 0;
		hiddeInterval(i);
	}

}, timer);


function hiddeInterval(position)
{
	for (var i = 0; i < feeds.length; i++) 
	{
		if (i == position)
		{
			$(feeds[i]).css("display","block");
		}
		else
		{
			$(feeds[i]).css("display","none");
		}
	}
}

$(document).ready(function(){
$("#password").keyup(function(){

	var passwordTwo = $(this).val();
	if (passwordTwo.length>0)
	{
		var passwordOne = $("#first").val();
		if (passwordOne == passwordTwo)
		{
			toastr.success("las contraseñas coinciden");
			$(".sent").attr("type","submit");
			$(".sent").focus();
		}
		else
		{
			if (passwordTwo.length >= passwordOne.length)
			{
				toastr.error("las contraseñas no coinciden");
				$(".sent").attr("type","button");
			}
		}
	}
});	
})
