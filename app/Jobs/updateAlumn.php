<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Alumns\User;
use App\Models\Alumns\Debit;

class updateAlumn implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $path = public_path()."/debits.json";
        try {
            $data = json_decode(file_get_contents(public_path()."/data.json"), true);
            $totalInserted = 0;
            $failed = [];
            $success = [];
            foreach ($data as $key => $value) {

                if ($value["status"] == "paid") {

                    $user = User::where("email", "=", $value["email"])->first();

                    if ($user) {
                        $debit = new Debit();

                        //validamos la descripcion y el tipo de adeudo
                        $description = normalizeChars($value["description"]);

                        if (strpos($description, "Documento")) {
                            $debit->debit_type_id = 5;
                            $debit->description = "Documento oficial de la universidad";
                        } else {
                            $debit->debit_type_id = 1;
                            $debit->description = "Aportacion a la calidad educativa";
                        }

                        //capturamos el monto
                        $debit->amount = $value["amount"];

                        //validamos el tipo de pago
                        $method = "";

                        if ($method == "OxxoPayment") {
                            $method = "oxxo_cash";
                        } else if ($method == "CreditCardPayment"){
                            $method = "card";
                        } else {
                            $method = "spei";
                        }

                        $debit->payment_method = $method;
                        $debit->admin_id = 2;
                        $debit->id_alumno = $user->id_alumno;
                        $debit->id_order = $value["id_order"];
                        $debit->status = 1;
                        $debit->cancelled = 0;
                        $debit->created_at = $value["created_at"];
                        $debit->updated_at = $value["updated_at"];
                        $debit->period_id = 4029;
                        $debit->save();
                        $totalInserted = $totalInserted + 1;
                    } else {
                        array_push($failed, ["message" => "No se encontro el usuario", "email" => $value["email"]]);
                    }
                } else {
                    array_push($failed, ["message" => "no se realizo el pago", "email" => $value["email"]]);
                }
            }

            $response = json_encode(["totalInserted" => $totalInserted, "failed" => $failed]);
            file_put_contents($path, $response);
        } catch(\Exeption $e ) {
            file_put_contents($path, ["error" => $e->getMessage()]);
        }
    }
}
