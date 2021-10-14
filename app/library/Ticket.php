<?php namespace App\Library;

use App\Models\Alumns\Debit;
use App\Models\Alumns\User;
use App\Models\Alumns\Ticket as TicketModel;
use Carbon\Carbon;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Storage;
use App\Models\Sicoes\Alumno;
use App\Models\PeriodModel;
use NumberFormatter;

class Ticket {
	
	/*
	|-------------------------------------------------------------------
	| Metodo para obtener el numero de Ticket
	|-------------------------------------------------------------------
    */
	private static function getTicketNumber()
	{
	    $config = getConfig();
	    $config->debit_ticket_count = $config->debit_ticket_count + 1;
	    $config->save();
	    return $config->ticket_serie." ".sprintf("%'04d", $config->debit_ticket_count);
	}

	/*
	|-------------------------------------------------------------------
	| Metodo para generar un Ticket
	|-------------------------------------------------------------------
	*/
	public static function build(Debit $debit)
	{
		try {

			$alumn = Alumno::find($debit->id_alumno);

		    if ($alumn) {

			     $namefile = uniqid().'.pdf';
			     $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
			     $fontDirs = $defaultConfig['fontDir'];
			     $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
			     $fontData = $defaultFontConfig['fontdata'];
			     $mpdf = new Mpdf([
			        'fontDir' => array_merge($fontDirs, [
			            public_path() . '/fonts',
			        ]),
			         'fontdata' => $fontData + [
			            'arial' => [
			                'R' => 'arial.ttf',
			                'B' => 'arialbd.ttf',
			            ],
			        ],
			        'default_font' => 'arial',
			        "format" => [210,297],
			    ]);
			     
			    $mpdf->SetDisplayMode('fullpage');
			    $mpdf->WriteHTML(self::generateView($alumn, $debit));

			    Storage::disk('ticket_uploads')->makeDirectory($alumn->Matricula);

			    try {     
			        $mpdf->Output("tickets/".$alumn->Matricula."/".$namefile,"F");
			    } catch(\Exception $e) {
			        $mpdf->Output(public_path()."/". "tickets/".$alumn->Matricula."/".$namefile,"F");
			    }

			    self::saveTicket($alumn, $debit, "tickets/".$alumn->Matricula."/".$namefile);	      
		    }
		} catch(\Exception $e) {
	    	return false;
	    }	    
	}

	public static function saveTicket(Alumno $alumn, Debit $debit, $path) {
		$user = User::where("id_alumno", $alumn->AlumnoId)->first();
		$ticket = new TicketModel();
	    $ticket->concept = ucwords($debit->description);
	    $ticket->alumn_id = $user->id;
	    $ticket->debit_id = $debit->id;
	    $ticket->route = $path;
	    $ticket->save();
	}

	public static function generateView(Alumno $alumn, Debit $debit) {
		$date = $debit->payment_date ? $debit->payment_date->format('d/m/Y') : Carbon::now()->format('d/m/Y');

		$state = $alumn->Estado ? $alumn->Estado : "No disponible";

	    $period = PeriodModel::find($debit->period_id);

	    $formatterES = new NumberFormatter("es", NumberFormatter::SPELLOUT);

	    $carrer = $alumn->PlanEstudio->Carrera;

	    $current_group = Sicoes::currentGroup($alumn->AlumnoId);
	    $ticketInfo = [
	        "ticketNum"      => self::getTicketNumber(),
	        "date"           => $date,
	        "enrollment"     => $alumn->Matricula,
	        "name"           => $alumn->FullName,
	        "rfc"            => substr($alumn->Curp, 0, 10),
	        "group"          => $current_group->Nombre,
	        "semester"       => $current_group->Semestre,
	        "career"         => strtolower(normalizeChars($carrer->Nombre)),
	        "location"       => $alumn->Localidad . ", " . $state->Nombre,
	        "order"          => $debit->id_order,
	        "period"         => $period->Clave,
	        "concept"        => $debit->description,
	        "amount"         => number_format($debit->amount, 2),
	        "strAmount"      =>  $formatterES->format($debit->amount),
	        "payment_method" => $debit->payment_method,
	        "secureStr"      => "Pendiente"
	    ];

	    return view('Alumn.ticket.template',['ticketInfo' => $ticketInfo])->render(); 
	}
}