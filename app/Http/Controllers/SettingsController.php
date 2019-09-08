<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actions;

class SettingsController extends Controller
{
    
    public function index(Actions $actions){
        $actions->addAction('new', 'new', 'whatever');
        echo 'sup';
    }
}
