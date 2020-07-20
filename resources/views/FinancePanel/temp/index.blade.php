<!DOCTYPE html>
<html>
<head>
	<title>Contraseñas</title>
	<!-- Bootstrap 4 -->
</head>
<body>
	<div style="width: 100%; height: 29cm">

		<table style="width: 100%;">
			<tr>
				<td>
					<img src="{{ asset('img/logo.jpg') }}"  alt="" style="width: 15%">
				</td>
				<td>
					<h1>{{$data[1]["Grupo"]}}</h1>
				</td>
			</tr>
		</table>

		<table style="width: 100%; text-align: left; border: #b2b2b2 1px solid;border-collapse: collapse;">
			@foreach($data as $key2 => $value2)
			<tr>
				<td>{{$value2["Nombre"]}}</td>
				<td>{{$value2["Matricula"]}}</td>
				<td>{{$value2["Grupo"]}}</td>
				<td>{{$value2[3]}}</td>
			</tr>
			@endforeach
		</table>

	<div>

</body>
</html>