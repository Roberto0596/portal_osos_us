<?php

namespace App\Http\Controllers\Alumn;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function index(){
        return view('Alumn.form.index');
    }
}
