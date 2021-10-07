@extends('layout')

@section('content')

<link rel="stylesheet" href="{{ asset('css/computer_logv2.css') }}">

<link rel="stylesheet" href="{{ asset('css/loader_log.css') }}">

<div class="loader-classroom">
	<div class="lds-circle"><div></div></div>
</div>

<div class="classroom-container">

	<header>

		<nav class="navbar navbar-expand-lg navbar-light navbar-orange">

			<div class="row w-100">

				<div class="col-md-1">

					<a class="close" id="goback" data-dismiss="modal" aria-label="Close">

	                    <i class="fa fa-arrow-left text-white" style="font-size: 22px"></i>

	                </a>

				</div>

				<div class="col-md-11">

					<h4 class="text-white">Sala de computo</h4>	

				</div>

			</div>			  

		</nav>

	</header>

	<div class="body">

		<div class="header-li">

			<span class="float-right" style="font-size: 20px">Disponibles: {{ $classrooms[0]->equipmentStatus()->free }} / {{ $classrooms[0]->equipmentStatus()->total }} </span>

		</div>
		
		<div class="row">

			<div class="col-md-1">

				<div class="fixed">
					@foreach($classrooms as $key => $item)
					<button class="btn btn-default btnSetClass @if($key == 0) active @endif" code="{{$item->code}}">{{ $item->name }}</button> <br>
					@endforeach

					<div class="logo-container">
						<img src="{{asset('img/temple/unisierra.png')}}">
					</div>
				</div>

			</div>

			<div class="col-md-11">

				@foreach($classrooms as $key => $item)

                <div class="rooms" @if($key != 0) style="display: none" @endif id="{{$item->code}}">

					<div class="row">

	                    @foreach($item->getEquipments() as $value)

                      	<div class="col-md-2 col-xs-3">

                          	<div class="card">

	                          	<div class="card-body">

	                          		<div class="card-body-img">
		                          		<img src="{{ $value->image() }}" 
		                          		equipmentId="{{$value->id}}"
		                              	@if($value->status == 0) class="btnSelect" @endif>
	                              	</div>

	                              	<p class="text-center">
	                              		Equipo: {{ $value->num }}
	                              	</p>

	                            </div>

                          	</div>

                      	</div>

	                    @endforeach

                	</div>	                

                </div>

                @endforeach

			</div>

		</div>

	</div>

</div>

<div class="modal fade" id="modal" data-backdrop='static' data-keyboard=false>

	<div class="modal-dialog modal-lg">

	    <div class="modal-content">

		    <form action="{{ route('logs.classroom.save') }}" method="post" id="form-reserva">

		        {{ csrf_field() }}

		        <input type="hidden" id="id_equipment" name="id_equipment">

		        <div class="modal-body padding-cero">

		          	<div class="row margin-cero">

		          		<div class="col-md-6 green text-white padding-top-1 d-none d-sm-block">

		          			<div class="row">

		          				<div class="col-md-2" style="margin-left: -14px !important;">

		          					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					                    <i class="fa fa-arrow-left text-white" style="font-size: 22px"></i>
					                </button>

		          				</div>

		          				<div class="col-md-10">

		          					<h4 class="text-white">Confirmar reserva</h4>

		          				</div>

		          			</div>

		          			<div class="row padding-leftandright"  style="margin-top: 1rem;">

		          				<div class="col-md-12 ">

		          					<p class="text-justify" style="font-size: 13px">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ut faucibus risus, quis aliquam erat. Phasellus et dui urna. Nam a convallis erat, at cursus orci. Quisque tristique, ligula et ultrices tincidunt, tortor libero ultrices nibh, ut maximus turpis nulla scelerisque ex. Mauris suscipit libero felis, id sagittis nibh tristique vel. Donec eget eros felis. Nunc eu cursus quam, eu consectetur enim.</p>

		          				</div>	

		          			</div>

		          			<div class="row padding-leftandright" style="margin-top: 7rem; line-height: 5px;">

		          				<div class="col-md-12">
		          					
		          					<h5>Información del equipo</h5>
		          					<p class="size-letter" style="margin-top: 2rem">Numero de equipo: <span id="num-span">23</span></p>
		          					<p class="size-letter">Sala: <span id="classroom-span">1</span></p>
		          				</div>

		          			</div>

		          		</div>

		          		<div class="col-md-6 padding-top-1">

		          			<div class="row">

		          				<div class="col-md-12">
		          					
		          					<div class="info">

		          						<div class="image-title text-center">
		          							<h3>Equipo</h3>
		          							<span id="equipment-label"></span>
		          						</div>
		          						
		          						<div class="image-equipment">
		          							<img src="{{ asset('img/log/centro.png') }}" alt="">
		          						</div>

		          						<div class="hour-container">

		          							<div class="form-group">

		          								<div class="input-group mb-3">

							                        <div class="input-group-prepend">

							                            <span class="input-group-text">

							                            <i class="fas fa-clock"></i></span>

							                        </div>

							                        <input type="text" class="form-control form-control-sm" readonly id="time" name="time">
		          									
		          								</div>

		          							</div>	

		          						</div>

		          						<div class="row" style="margin-top: 1rem;">

		          							<div class="col-md-6 flex-content">
		          								<img src="{{ asset('img/log/accept.png') }}" class="options-img" id="send-form">
		          							</div>


		          							<div class="col-md-6 flex-content">
		          								<img src="{{ asset('img/log/cancel.png') }}" class="options-img" id="cancel">
		          							</div>

		          							<div class="col-md-6">
		          								<p class="text-center">Confirmar</p>
		          							</div>

		          							<div class="col-md-6">
		          								<p class="text-center">Cancelar</p>
		          							</div>

		          						</div>

		          						<div class="info-footer">
		          							<p>Gracias por utilizar este servicio =)</p>
		          						</div>

			          					<div class="logo-info">
			          						<img src="{{asset('img/temple/unisierra.png')}}" class="img-info">
			          					</div>

		          					</div>

		          				</div>

		          			</div>

		          		</div>

		          	</div>
		              
		        </div>

		    </form>

	    </div>

	</div>

</div>


<script src="{{ asset('js/loaderClassroom.js') }}"></script>

<script>
	
	$("#cancel").click(function(){
		$("#modal").modal("hide");
	});

	$("#send-form").click(function() {
		$(".loader-classroom").css("display", "flex");
		var $form = $("#form-reserva");
		$form.submit();
	});

	$(".btnSetClass").click(function() {
		var code = $(this).attr("code");

     	$(".btnSetClass").removeClass("active");
		$(this).addClass("active");

		$(".rooms").hide();
		$("#"+code).show();
	});

	$(".btnSelect").click(async function() {
	    var id_equipment = $(this).attr("equipmentId");

	    loaderRun();
	    var info = await getInfo(id_equipment);
	    loaderRun(false);

	    $("#equipment-label").text(info.num);
	    $("#id_equipment").val(info.id);
	    $("#num-span").text(info.num);
	    $("#classroom-span").text(info.classroom_id);
	    $("#time").val(moment().format('HH:mm:ss'));
	    $("#modal").modal("show");
	});

	async function getInfo(id_equipment) {
		return new Promise(resolve => {
              $.get("{{ route('logs.classroom.get.equipment') }}/"+id_equipment).then(result => {
                    resolve(result);
              });
        });
	}

	$("#goback").click(function() {
		loaderRun();
		window.location = "{{ route('logs.login') }}";
	});
</script>

@stop
