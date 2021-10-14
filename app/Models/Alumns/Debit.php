<?php

namespace App\Models\Alumns;

use Illuminate\Database\Eloquent\Model;
use App\Enum\DebitStatus;
use App\Library\Ticket;

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
        }

        if ($status == Debit::getStatus(DebitStatus::paid())) {
            $this->enrollment = $this->Alumn->Matricula;
            $this->payment_date = now();
            Ticket::build($this);
        }

        $this->save();
    }
}
