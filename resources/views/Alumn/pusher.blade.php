<input type="hidden" id="pusher_local_id" value="{{ current_user()->id }}">

<script>

	$(document).ready(function() {
		loadNotify();
	});

	var pusherId = 'faffc77d32024c9b7072';

	Pusher.logToConsole = true;

	var pusher = new Pusher(pusherId, {
	  cluster: 'us2'
	});

	var channel = pusher.subscribe('my-channel');
	channel.bind('my-event', function(data) {
		if (data.target == "users" && data.id == $("#pusher_local_id").val()) {
			loadNotify();
		}		
	});

	var itemTemplate = "<a href='#url' class='dropdown-item'><i class='fas fa-th mr-2'></i><span>#text</span></a>";

	function loadNotify() {
		$.get("/alumn/notify/show", function(response) {
			$("#content-notify").empty();        
	        if (response.length == 0) {
	            $("#content-notify").append("<p style='text-align: center; margin: 5%;'>No hay notificaciones</p>");
	        } else {
	            $(".count-notify").text(response.length);  
	            for (var i = 0; i < response.length; i++) {
	              $("#content-notify").append("<a href='/alumn/notify/"+response[i]["route"]+"/"+response[i]["alumn_id"]+"' class='dropdown-item'>"+
	                "<i class='fas fa-th mr-2'></i>"+response[i]['text']+
	                "<span class='float-right text-muted text-sm'>Reciente</span>"+
	              "</a>");
	            }
	         }
		});
	}

</script>
