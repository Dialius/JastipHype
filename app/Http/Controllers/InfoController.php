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

    public function terms()
    {
        return view('info.terms');
    }

    public function privacy()
    {
        return view('info.privacy');
    }
}
