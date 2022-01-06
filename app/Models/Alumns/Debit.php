<?php

namespace App\Models\Alumns;

use Illuminate\Database\Eloquent\Model;
use App\Enum\DebitStatus;
use App\Library\Ticket;
use App\Broadcast\NotifyEvent;
use App\Broadcast\NotifyFinance;

class Debit extends Model
{
    protected $table = "debit";

    protected $fillable = [
    	'id',
        'debit_type_id',
        'description',
        'amount',
        'admin_id',
        'id_alumno',
        'status',
        'created_at',
        'updated_at',
        'enrollment',
        'alumn_name',
        'alumn_last_name',
        'alumn_second_last_name',
        'career',
        'location',
        'payment_date'
    ];
    
    protected $dates = [
        'created_at',
        'updated_at',
        'payment_date'
    ];

    private static $status = [
        "PENDING" => 0,
        "VALIDATE" => 1,
        "PAID" => 3,
    ];

    public static function getStatus($key) {
        return self::$status[$key];
    }

    protected $with = ['admin', 'debitType', 'Alumn'];

    public function admin() {
        return $this->belongsTo('\App\Models\AdminUsers\AdminUser', "admin_id", "id");
    }

    public function debitType() {
        return $this->belongsTo('\App\Models\Alumns\DebitType', "debit_type_id", "id");
    }

    public function Alumn() {
        return $this->belongsTo('\App\Models\Sicoes\Alumno', "id_alumno", "AlumnoId");
    }

    public function getDebit() {
        return User::find($this->id_alumno);
    }

    public function setForeignValues() {
        $this->enrollment = $this->Alumn->Matricula;
        $this->alumn_name = $this->Alumn->Nombre;
        $this->alumn_last_name = $this->Alumn->ApellidoPrimero;
        $this->alumn_second_last_name = (isset($this->Alumn->ApellidoSegundo) ? $this->Alumn->ApellidoSegundo : '');
        $this->career = $this->Alumn->PlanEstudio->Carrera->Nombre;
        $this->location = $this->Alumn->Localidad;
        $this->state = $this->Alumn->Estado->Nombre;
        $this->phone = $this->Alumn->Telefono;
        $this->email = User::where('id_alumno', $this->id_alumno)->first()->email;
        $this->save();
    }

    public static function validateWithOrder($id_order, $status) {

        $debits = self::where("id_order", $id_order)->get();

        foreach ($debits as $value) {
            $value->validate($status);
        }
    }

    public function validate($status, $payment_method = null, $order_id = null) {
        $this->status = $status;

        if ($order_id) {
            $this->id_order = $order_id;
        }

        if ($payment_method) {
            $this->payment_method = $payment_method;
        }

        if ($this->has_file_id != null && $this->status == Debit::getStatus(DebitStatus::paid())) {
            $document = Document::find($this->has_file_id);
            $document->payment = 1;
            $document->save();
            $document->addNotify();
        }

        if ($status == Debit::getStatus(DebitStatus::paid())) {
            $this->enrollment = $this->Alumn->Matricula;
            $this->payment_date = now();
            Ticket::build($this);
        }

        if ($status == 1 || $status == 3) {
            $alumn = User::where("id_alumno", $this->id_alumno)->first();
            $this->addNotify($alumn->id, "users", "alumn.debit");
        }

        $this->save();
    }

    public function addNotify($id, $target, $route) {
        $message = "";

        if ($this->status == 0) {
            $notify = new Notify();
            $notify->addNotify("Hay un nuevo adeudo", null, "finance", "finance.debit");
            event(new NotifyFinance("finance", $message));
        } else {
           if($this->status == 1) {
                $message = "El adeudo fue validado";
            } else if($this->status == 3) {
                $message = "Adeudo pagado y validado por finanzas";
            } 
            $notify = new Notify();
            $notify->addNotify($message, $id, $target, $route);
            event(new NotifyEvent($id, $target, $message));
        } 
    }

    public function generateDocument(User $user, DocumentType $documentType) {
        $document =  new Document();
        $document->description = 'Documento oficial unisierra';
        $document->route = ''; 
        $document->PeriodoId = getConfig()->period_id; 
        $document->alumn_id = $user->id;
        $document->document_type_id = $documentType->id;
        $document->payment = 0;
        $document->id_debit = $this->id;
        $document->save();
        $document->addNotify();
        $this->has_file_id = $document->id;
        $this->save();
    }
}
