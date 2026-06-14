<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    public function index()
    {
        return response()->json(LogAktivitas::with('user')->orderBy('waktu', 'desc')->get());
    }
}
