<?php

namespace App\Http\Controllers;

use App\Models\Email;
use Illuminate\Contracts\Support\Renderable;

class EmailController extends Controller
{
    public function index(): Renderable
    {
        $emails = Email::all();

        return view('emails.emails', compact('emails'));
    }
}
