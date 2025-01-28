<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Affiche la page d'accueil.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home'); // Assurez-vous d'avoir une vue 'home.blade.php'
    }
}
