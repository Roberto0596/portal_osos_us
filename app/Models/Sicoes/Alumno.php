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

}

