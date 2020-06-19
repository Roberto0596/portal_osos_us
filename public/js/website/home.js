var passwordFlag = true;

$("#password").focus(function()
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
