<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function contact()
    {
        return view('info.contact');
    }

    public function shipping()
    {
        return view('info.shipping');
    }

    public function returns()
    {
        return view('info.returns');
    }

    public function faq()
    {
        return view('info.faq');
    }
}
