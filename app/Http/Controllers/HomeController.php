<?php

namespace App\Http\Controllers;

use App\Mail\FeedbackGiven;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Validator;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{

    public function showHome()
    {
        return view('pages.home');
    }

    /* Placeholder Templates */
    public function showProducingTemplate()
    {
        return view('pages.producing.home');
    }

    public function showActingTemplate()
    {
        return view('pages.acting.home');
    }

    public function showDefault()
    {
        return redirect()->route('home');
    }

    public function showBugReport()
    {
        return view('pages.bugreport');
    }

    public function createBugReport(Request $request, Mailer $mailer)
    {
        $validator = Validator::make($request->all(), [
            'onderwerp' => 'required|max:40|min:3',
            'uitleg'    => 'required|max:800|min:5',
        ]);

        if ($validator->fails()) {
            return redirect()->route('bugreport')
                ->withErrors($validator)
                ->withInput();
        }

        $mailer->send(new FeedbackGiven($request, Auth::user()));

        return redirect()->route('home')->with('success', Lang::get('general.bugreport-sent'));
    }
}
