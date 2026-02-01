<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('Inicio/inicio');
    }

    public function enrique(): string
    {
        return view('EnriquePortafolio/iniE');
    }
    public function brandon(): string
    {
        return view('BrandonPortafolio/iniB');
    }

}
