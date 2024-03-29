<?php 

namespace App\Models\Sicoes;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model {

	protected $connection = 'sicoes';

	protected $primaryKey = 'AlumnoId';

    protected $table = 'Alumno';

    public $timestamps = false;

    protected $fillable = [
    	"AlumnoId",
    	"Matricula",
    	"Nombre",
        "ApellidoPrimero",
        "ApellidoSegundo",
        "Regular",
        "Tipo",
        "Foto",
        "Firma",
        "Curp",
        "Genero",
        "FechaNacimiento",
        "Edad",
        "MunicipioNac",
        "EstadoNac",
        "EdoCivil",
        "Estatura",
        "Peso",
        "TipoSangre",
        "Alergias",
        "Padecimiento",
        "ServicioMedico",
        "NumAfiliacion",
        "Domicilio",
        "Colonia",
        "Localidad",
        "MunicipioDom",
        "EstadoDom",
        "CodigoPostal",
        "Telefono",
        "Email",
        "EscuelaProcedenciaId",
        "AnioEgreso",
        "PromedioBachiller",
        "ContactoEmergencia",
        "ContactoDomicilio",
        "ContactoTelefono",
        "TutorNombre",
        "TutorDomicilio",
        "TutorTelefono",
        "TutorOcupacion",
        "TutorSueldoMensual",
        "MadreNombre",
        "MadreDomicilio",
        "MadreTelefono",
        "TrabajaActualmente",
        "Puesto",
        "SueldoMensualAlumno",
        "DeportePractica",
        "Deportiva",
        "Cultural",
        "Academica",
        "TransporteUniversidad",
        "Transporte",
        "ActaNacimiento",
        "CertificadoBachillerato",
        "OtroDocumento",
        "Baja",
        "PlanEstudioId",
        "CirugiaMayor",
        "CirugiaMayorDescripcion",
        "CirugiaMenor",
        "CirugiaMenorDescripcion",
        "Hijo",
        "NumeroHijo",
        "Egresado",
    ];

    protected $with = ['PlanEstudio', 'Estado'];

    protected $appends = ['FullName'];

    public function PlanEstudio() {
        return $this->belongsTo("\App\Models\Sicoes\PlanEstudio", "PlanEstudioId", "PlanEstudioId");
    }

    public function Estado() {
        return $this->belongsTo("\App\Models\Sicoes\Estado", "EstadoDom", "EstadoId");
    }

    public function MunicipioDomRelation() {
        return $this->belongsTo("\App\Models\Sicoes\Municipio", "MunicipioDom", "MunicipioId");
    }

    public function MunicipioNacRelation() {
        return $this->belongsTo("\App\Models\Sicoes\Municipio", "MunicipioNac", "MunicipioId");
    }

    public function getFullName() {
        return ucwords(strtolower(normalizeChars(join(' ', [$this->Nombre, $this->ApellidoPrimero, $this->ApellidoSegundo]))));
    }

    public function getFullNameAttribute() {
        return ucwords(strtolower(normalizeChars(join(' ', [$this->Nombre, $this->ApellidoPrimero, $this->ApellidoSegundo]))));
    }

    public function getCarrera() {
        return $this->PlanEstudio->Carrera->Nombre;
    }

    public function getRecidencia() {
        return join(", ", [
            $this->Domicilio, 
            $this->Colonia,
            $this->Localidad.' - '.$this->MunicipioDomRelation->Nombre,
            $this->Estado->Nombre,
            $this->CodigoPostal
        ]);
    }
}

