<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Company;

class MachineController extends Controller
{
	public function index() {
    	$companies = Company::orderBy('name')->get();

		return response()->json(compact('companies'));
	}
}
