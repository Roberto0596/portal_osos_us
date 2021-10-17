<?php 

use App\Models\AdminUsers\AdminUser;
use App\Models\Alumns\Notify;
use App\Models\Alumns\DebitType;
use App\Models\Alumns\HighAverages;
use App\Models\PeriodModel;
use App\Models\ConfigModel;
use App\Models\Alumns\Document;
use App\Models\Alumns\DocumentType;
use App\Models\Alumns\User;
use App\Models\Alumns\Debit;
use App\Models\Alumns\FailedRegister;
use App\Models\Alumns\Ticket;
use Carbon\Carbon;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Storage;
use App\Library\Inscription;
use App\Models\Sicoes\Alumno;
use App\Models\Sicoes\Estado;
use App\Models\Sicoes\Municipio;
use App\Models\Sicoes\PlanEstudio;
use App\Models\Sicoes\Escuela;
use App\Models\Sicoes\Periodo;
use App\Models\Sicoes\EncGrupo;
use App\Library\Sicoes;
use App\Library\Ticket as TicketLibrary;


/**
 * selecciona la configuracion.
 *
 * @return periodModel $instance
 */
function getConfig() {
    return ConfigModel::first();
}

/**
 * selecciona el periodo actual.
 *
 * @return periodModel $instance
 */
function selectCurrentPeriod()
{
    return PeriodModel::find(getConfig()->period_id);
}

/**
 * Obtiene toos los periodos de la base de datos de sicoes.
 *
 * @return Periodo $instance[]
 */
function getPeriodos()
{
    return Periodo::all();
}

/**
 * genera el formato correcto para crear la orden de conecta, anexa totales y motivos de pago.
 *
 * @param  $debits[]| array de adeudos
 *
 * @param  $type| metodo de pago
 *
 * @return array
 */
function getArrayItem($debits, $type) {
    $item_array = [];
    $total = $debits->sum("amount");
    foreach ($debits as $key => $value) {
        $items = array('name' => $value->debitType->concept,
                        "unit_price" => $value->amount*100,
                        "quantity" => 1);
        array_push($item_array, $items);
    }

    //agregamos la comision bancaria correspondiente.
    $commission = array('name' => 'comision bancaria',
                      'unit_price' => floatval((getTotalWithComission($total,$type,false)*100)),
                      'quantity'=>1);
    
    array_push($item_array, $commission);
    return $item_array;
}

/**
 * agrega un fallo de inscripcion.
 *
 * @param  $id|required
 *
 * @param  $message|required
 *
 * @return void
 */
function addFailedRegister($id,$message) {
    $instance = new FailedRegister();
    $instance->alumn_id = $id;
    $instance->period_id = selectCurrentPeriod()->id;
    $instance->message = $message;
    $instance->status = 0;
    $instance->save();
}

/**
 * inserta el adeudo correpondiente de inscription.
 *
 * @param  App\Models\Alumns\User $user
 *
 * @return $array
 */
function insertInscriptionDebit(User $user)
{
    $message = [
        "type" => 0, 
        "message" => "Termino la validación de tu información"
    ];

    $alumnData = Alumno::find($user->id_alumno);

    $debit_array = [
        'debit_type_id' => 1,
        'description' => 'Aportacion a la calidad estudiantil',
        'amount' => getConfig()->price_inscription,
        'admin_id'=> 2,
        'id_alumno' => $user->id_alumno,
        'status' => 0,
        'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
        'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        'period_id' => getConfig()->period_id,
        'enrollment' => $alumnData->Matricula,
        'alumn_name' => $alumnData->Nombre,
        'alumn_last_name' => $alumnData->ApellidoPrimero,
        'alumn_second_last_name' => (isset($alumnData->ApellidoSegundo) ? $alumnData->ApellidoSegundo : ''),
        'career' => $alumnData->PlanEstudio->Carrera->Nombre,
        'location' => $alumnData->Localidad,
        'state' => $alumnData->Estado->Nombre,
    ];

    $validate = HighAverages::where("enrollment", $alumnData->Matricula)->where("status", 0)->first();

    if($validate) {
        $debit_array["status"] = Debit::getStatus(DebitStatus::paid());
        $debit_array["amount"] = 0;
        $inscription = Inscription::makeRegister($user);
        $message["message"] = "Se procederá al siguiente paso de inscripción";
        $message["type"] = 1;
        $validate->status = 1;
        $validate->save();
    } else {
        $user->nextStep();
    }

    $create_debit = insertIntoPortal("debit", $debit_array);

    return $message;
}

/**
 * inserta los documentos de inscripcion.
 *
 * @param  int $id
 *
 * @return bool
 */
function insertInscriptionDocuments($id)
{
    $currentPeriod = selectCurrentPeriod();
    $array =[ 
      [
          'description' => 'constancia de no adeudo', 
          'route' => 'alumn.constancia', 
          'PeriodoId' => $currentPeriod->id, 
          'alumn_id' => $id,
          'document_type_id' => 6
      ], [
          'description' => 'cédula de reinscripción', 
          'route' => 'alumn.cedula', 
          'PeriodoId' => $currentPeriod->id, 
          'alumn_id' => $id,
          'document_type_id'=> 7
    ]];
    
    $insertDocument = insertIntoPortal("document",$array);
    return $insertDocument;
}

/**
 * obtiene los periodos ordenados por el id.
 *
 * @return PeriodModel instance
 */
function periodsById() {
    return PeriodModel::select()->orderBy("id", "desc")->get();
}

/**
 * obtiene los adedudos.
 *
 * @return DebitType instance
 */
function getUnAdminDebitType() {
   $query = DebitType::where("id","<>",1)->where("id","<>",5)->get();
    return $query;
}

/**
 * obtiene el total de los adeudos del alumno, por concepto de pago diferente a inscripcion.
 *
 * @return int instance
 */
function getTotalDebitWithOtherConcept() {
    $debit = Debit::where([["id_alumno","=",current_user()->id_alumno],["debit_type_id", "<>", 1]])->get();
    return $debit->sum("amount");
}

/**
 * obtiene los tipos de documentos.
 *
 * @return DebitType instance
 */
function getOfficialDocuments() {
    return DocumentType::where("type", "=", 1)->get();
}

/**
 * obtiene los estados.
 *
 * @return Estado instance
 */
function getEstados($id = null) {
    if (!$id) {
        return Estado::all();
    } else {
        return Estado::find($id);
    }
}

/**
 * obtiene los municipios.
 *
 * @return Municipio instance
 */
function getMunicipios($id = null) {
    if (!$id) {
        return Municipio::all();
    } else {
        return Municipio::find($id);
    }
}

/**
 * obtiene los planes de estudio.
 *
 * @return PlanEstudio instance
 */
function getPlanesEstudio($id = null) {
    if (!$id) {
        return PlanEstudio::all();
    } else {
        return PlanEstudio::find($id);
    }
}


/**
 * obtiene los grupos.
 *
 * @return Municipio instance
 */
function getGrupos($key = null, $value = null) {
    if (!$key) {
        return EncGrupo::all();
    } else {
        return EncGrupo::where($key, $value)->get();
    }
}

/**
 * obtiene las escuelas de procedencia.
 *
 * @return Escuela instance
 */
function getEscuela($id = null) {
    if (!$id) {
        return Escuela::all();
    } else {
        return Escuela::find($id);
    }
}

/**
 * obtiene las notificacioness.
 *
 * @return Collection instances
 */
function getCurrentNotify() {
    $notifys = Notify::where("alumn_id",current_user()->id)->where("status", "0")->get();
    return $notifys;
}

/**
 * consulta la tabla de debitType, recibe un parametro, si está nulo traerá todas las isntancias.
 *
 * @param id
 *
 * @return Collection instances
 */
function getDebitType($id = null)
{
    if ($id == null) {
        return DebitType::all();
    } else {
        return DebitType::find($id);
    }
}

function selectUsersWithSicoes() {
    return DB::table("users")->where("id_alumno","<>",null)->get();
}

function selectTable($tableName, $item=null,$value=null,$limit=null)
{
    if ($item == null) {
        return DB::table($tableName)->get();
    } else {

        if ($limit==null) {
            return DB::table($tableName)->where($item,"=",$value)->get();
        } else {
            return DB::table($tableName)->where($item,"=",$value)->first();
        }
    }
}

function getDateCustom()
{
      date_default_timezone_set('America/Hermosillo');
      $date = date('Y-m-d');
      $hour = date('H:i:s');
      return $date.'T'.$hour;
}

function insertIntoPortal($tableName,$array)
{
  try
  {
    $insertar = DB::table($tableName)->insert($array);
    return true;
  }
  catch(\Exception $e)
  {
    return false;
  }
}

function validateDocumentInscription($id_alumno, $document_type_id)
{
  $document = Document::where([["alumn_id","=",$id_alumno],["type","=",1],["document_type_id","=",$document_type_id]])->first();
  if (!$document || $document->status == 0) {
    return "card-danger|No se ha registrado el documento";
  } else if($document->status == 1){
    return "card-warning|Falta validación";
  } else {
    return "card-success|Todo esta en orden";
  }
}

function current_user($guard = null) {
    return \Auth::guard($guard==null?"alumn":$guard)->user();
}



function carrerasActivas($planId = null) {
  return Sicoes::carrerasActivas($planId);
}


//auxiliari methods
function generatePasssword()
{
    $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '1234567890';
    $password = '';
    for($i = 0; $i < 4; $i++){
        $randomIndexLetras = mt_rand(0,strlen($letters) - 1);
        $randomIndexNumbers = mt_rand(0,strlen($numbers) - 1);
        $password = $password.$letters[$randomIndexLetras].$numbers[$randomIndexNumbers];  
    }
    return $password;
}


function upload_image($file,$subfolder, $id) {
  $path = "img/".$subfolder."/".$id."/";
  $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
  $file->move($path, $fileName);
  return $path.$fileName;
}

//validar con servicios escolares, que todos los alumnos del 2014 hacia atras esten en sicoes
function isNoob($id) {
    $user = User::find($id);
    if ($user->id_alumno == null) {
        return "/alumn/inscripcion";
    } else {
        return "/alumn/re-inscripcion";
    }
}

function getTotalWithComission($total, $tipo, $flag = true) {
    if ($tipo == "card") {
        $comission = (1 - (0.029 * 1.16));
        $comission_fixed = 2.5 * 1.16;
        $total_payment = ($total + $comission_fixed)/$comission;
        $total_comission = $total_payment - $total;
    } else if ($tipo == "oxxo") {
        $comission = (1 - (0.039 * 1.16));
        $total_payment = $total/$comission;
        $total_comission = $total_payment - $total;
    } else if ($tipo == "spei") {
        $comission = 12.5 * 1.16;
        $total_payment = $total + $comission;
        $total_comission = $total_payment - $total;
    }

    if ($flag) {
        return ceil($total_payment);
    } else {
        return ceil($total_comission);
    }
}


function getDebitByArray($array) {
    $collection = collect();
    foreach ($array as $key => $value) {
       $debit = Debit::find($value["id"]);
       $collection->push($debit);
    }
    return $collection;
}


function addLog($message) {
    $path = public_path()."/log.txt";
    $data = json_decode(file_get_contents($path),true);
    array_push($data["errors"], ["mensaje" => $message, "fecha" => getDateCustom()]);
    file_put_contents($path, json_encode($data));
}

function normalizeChars($s) {
    $replace = array(
        'ъ'=>'-', 'Ь'=>'-', 'Ъ'=>'-', 'ь'=>'-',
        'Ă'=>'A', 'Ą'=>'A', 'À'=>'A', 'Ã'=>'A', 'Á'=>'A', 'Æ'=>'A', 'Â'=>'A', 'Å'=>'A', 'Ä'=>'Ae',
        'Þ'=>'B',
        'Ć'=>'C', 'ץ'=>'C', 'Ç'=>'C',
        'È'=>'E', 'Ę'=>'E', 'É'=>'E', 'Ë'=>'E', 'Ê'=>'E',
        'Ğ'=>'G',
        'İ'=>'I', 'Ï'=>'I', 'Î'=>'I', 'Í'=>'I', 'Ì'=>'I',
        'Ł'=>'L',
        'Ñ'=>'N', 'Ń'=>'N',
        'Ø'=>'O', 'Ó'=>'O', 'Ò'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'Oe',
        'Ş'=>'S', 'Ś'=>'S', 'Ș'=>'S', 'Š'=>'S',
        'Ț'=>'T',
        'Ù'=>'U', 'Û'=>'U', 'Ú'=>'U', 'Ü'=>'Ue',
        'Ý'=>'Y',
        'Ź'=>'Z', 'Ž'=>'Z', 'Ż'=>'Z',
        'â'=>'a', 'ǎ'=>'a', 'ą'=>'a', 'á'=>'a', 'ă'=>'a', 'ã'=>'a', 'Ǎ'=>'a', 'а'=>'a', 'А'=>'a', 'å'=>'a', 'à'=>'a', 'א'=>'a', 'Ǻ'=>'a', 'Ā'=>'a', 'ǻ'=>'a', 'ā'=>'a', 'ä'=>'ae', 'æ'=>'ae', 'Ǽ'=>'ae', 'ǽ'=>'ae',
        'б'=>'b', 'ב'=>'b', 'Б'=>'b', 'þ'=>'b',
        'ĉ'=>'c', 'Ĉ'=>'c', 'Ċ'=>'c', 'ć'=>'c', 'ç'=>'c', 'ц'=>'c', 'צ'=>'c', 'ċ'=>'c', 'Ц'=>'c', 'Č'=>'c', 'č'=>'c', 'Ч'=>'ch', 'ч'=>'ch',
        'ד'=>'d', 'ď'=>'d', 'Đ'=>'d', 'Ď'=>'d', 'đ'=>'d', 'д'=>'d', 'Д'=>'D', 'ð'=>'d',
        'є'=>'e', 'ע'=>'e', 'е'=>'e', 'Е'=>'e', 'Ə'=>'e', 'ę'=>'e', 'ĕ'=>'e', 'ē'=>'e', 'Ē'=>'e', 'Ė'=>'e', 'ė'=>'e', 'ě'=>'e', 'Ě'=>'e', 'Є'=>'e', 'Ĕ'=>'e', 'ê'=>'e', 'ə'=>'e', 'è'=>'e', 'ë'=>'e', 'é'=>'e',
        'ф'=>'f', 'ƒ'=>'f', 'Ф'=>'f',
        'ġ'=>'g', 'Ģ'=>'g', 'Ġ'=>'g', 'Ĝ'=>'g', 'Г'=>'g', 'г'=>'g', 'ĝ'=>'g', 'ğ'=>'g', 'ג'=>'g', 'Ґ'=>'g', 'ґ'=>'g', 'ģ'=>'g',
        'ח'=>'h', 'ħ'=>'h', 'Х'=>'h', 'Ħ'=>'h', 'Ĥ'=>'h', 'ĥ'=>'h', 'х'=>'h', 'ה'=>'h',
        'î'=>'i', 'ï'=>'i', 'í'=>'i', 'ì'=>'i', 'į'=>'i', 'ĭ'=>'i', 'ı'=>'i', 'Ĭ'=>'i', 'И'=>'i', 'ĩ'=>'i', 'ǐ'=>'i', 'Ĩ'=>'i', 'Ǐ'=>'i', 'и'=>'i', 'Į'=>'i', 'י'=>'i', 'Ї'=>'i', 'Ī'=>'i', 'І'=>'i', 'ї'=>'i', 'і'=>'i', 'ī'=>'i', 'ĳ'=>'ij', 'Ĳ'=>'ij',
        'й'=>'j', 'Й'=>'j', 'Ĵ'=>'j', 'ĵ'=>'j', 'я'=>'ja', 'Я'=>'ja', 'Э'=>'je', 'э'=>'je', 'ё'=>'jo', 'Ё'=>'jo', 'ю'=>'ju', 'Ю'=>'ju',
        'ĸ'=>'k', 'כ'=>'k', 'Ķ'=>'k', 'К'=>'k', 'к'=>'k', 'ķ'=>'k', 'ך'=>'k',
        'Ŀ'=>'l', 'ŀ'=>'l', 'Л'=>'l', 'ł'=>'l', 'ļ'=>'l', 'ĺ'=>'l', 'Ĺ'=>'l', 'Ļ'=>'l', 'л'=>'l', 'Ľ'=>'l', 'ľ'=>'l', 'ל'=>'l',
        'מ'=>'m', 'М'=>'m', 'ם'=>'m', 'м'=>'m',
        'ñ'=>'n', 'н'=>'n', 'Ņ'=>'n', 'ן'=>'n', 'ŋ'=>'n', 'נ'=>'n', 'Н'=>'n', 'ń'=>'n', 'Ŋ'=>'n', 'ņ'=>'n', 'ŉ'=>'n', 'Ň'=>'n', 'ň'=>'n',
        'о'=>'o', 'О'=>'o', 'ő'=>'o', 'õ'=>'o', 'ô'=>'o', 'Ő'=>'o', 'ŏ'=>'o', 'Ŏ'=>'o', 'Ō'=>'o', 'ō'=>'o', 'ø'=>'o', 'ǿ'=>'o', 'ǒ'=>'o', 'ò'=>'o', 'Ǿ'=>'o', 'Ǒ'=>'o', 'ơ'=>'o', 'ó'=>'o', 'Ơ'=>'o', 'œ'=>'oe', 'Œ'=>'oe', 'ö'=>'oe',
        'פ'=>'p', 'ף'=>'p', 'п'=>'p', 'П'=>'p',
        'ק'=>'q',
        'ŕ'=>'r', 'ř'=>'r', 'Ř'=>'r', 'ŗ'=>'r', 'Ŗ'=>'r', 'ר'=>'r', 'Ŕ'=>'r', 'Р'=>'r', 'р'=>'r',
        'ș'=>'s', 'с'=>'s', 'Ŝ'=>'s', 'š'=>'s', 'ś'=>'s', 'ס'=>'s', 'ş'=>'s', 'С'=>'s', 'ŝ'=>'s', 'Щ'=>'sch', 'щ'=>'sch', 'ш'=>'sh', 'Ш'=>'sh', 'ß'=>'ss',
        'т'=>'t', 'ט'=>'t', 'ŧ'=>'t', 'ת'=>'t', 'ť'=>'t', 'ţ'=>'t', 'Ţ'=>'t', 'Т'=>'t', 'ț'=>'t', 'Ŧ'=>'t', 'Ť'=>'t', '™'=>'tm',
        'ū'=>'u', 'у'=>'u', 'Ũ'=>'u', 'ũ'=>'u', 'Ư'=>'u', 'ư'=>'u', 'Ū'=>'u', 'Ǔ'=>'u', 'ų'=>'u', 'Ų'=>'u', 'ŭ'=>'u', 'Ŭ'=>'u', 'Ů'=>'u', 'ů'=>'u', 'ű'=>'u', 'Ű'=>'u', 'Ǖ'=>'u', 'ǔ'=>'u', 'Ǜ'=>'u', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'У'=>'u', 'ǚ'=>'u', 'ǜ'=>'u', 'Ǚ'=>'u', 'Ǘ'=>'u', 'ǖ'=>'u', 'ǘ'=>'u', 'ü'=>'ue',
        'в'=>'v', 'ו'=>'v', 'В'=>'v',
        'ש'=>'w', 'ŵ'=>'w', 'Ŵ'=>'w',
        'ы'=>'y', 'ŷ'=>'y', 'ý'=>'y', 'ÿ'=>'y', 'Ÿ'=>'y', 'Ŷ'=>'y',
        'Ы'=>'y', 'ž'=>'z', 'З'=>'z', 'з'=>'z', 'ź'=>'z', 'ז'=>'z', 'ż'=>'z', 'ſ'=>'z', 'Ж'=>'zh', 'ж'=>'zh'
    );
    return strtr($s, $replace);
}

function closeAllSessions($session) {
    if (Auth::guard($session)->check()) {
        Auth::guard($session)->logout();
        session()->flush();
    }
}

function getAlumnPeriods($alumn_id) {
    return Sicoes::getAlumnPeriods($alumn_id);
}
