<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PeriodModel;

class ReportController extends Controller
{
	public function index()
	{
		return view('AdminPanel.report.index');
    }
}