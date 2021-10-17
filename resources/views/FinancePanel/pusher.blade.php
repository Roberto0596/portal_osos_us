<script>

	$(document).ready(function() {
		loadNotify();
	});
	
	var pusherId = 'faffc77d32024c9b7072';

	Pusher.logToConsole = true;

	var pusher = new Pusher(pusherId, {
	  cluster: 'us2'
	});

	var channel = pusher.subscribe('finance-channel');
	channel.bind('finance-event', function(data) {
		console.log(data);
		if (data.target == "finance") {
			loadNotify();
		}		
	});

	var itemTemplate = "<a href='#url' class='dropdown-item'><i class='fas fa-th mr-2'></i><span>#text</span></a>";

	function loadNotify() {
		$.get("{{route('finance.notify.show')}}", function(response) {
			$("#content-notify").empty();        
	        if (response.length == 0) {
	            $("#content-notify").append("<p style='text-align: center; margin: 5%;'>No hay notificaciones</p>");
	        } else {
	            $(".count-notify").text(response.length);  
	            for (var i = 0; i < response.length; i++) {
	              $("#content-notify").append("<a href='/finance/notify/"+response[i]["route"]+"' class='dropdown-item'>"+
	                "<i class='fas fa-th mr-2'></i>"+response[i]['text']+
	                "<span class='float-right text-muted text-sm'>Reciente</span>"+
	              "</a>");
	            }
	         }
		});
	}

</script>