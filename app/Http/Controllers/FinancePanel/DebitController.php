<?php

namespace App\Http\Controllers\FinancePanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DebitController extends Controller
{
    public function index()
	{
		return view('FinancePanel.debit.index');
    }
    
}
