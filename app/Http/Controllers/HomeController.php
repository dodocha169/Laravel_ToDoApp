<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        try {
            $user = Auth::user();
            $folder = $user->folders()->first();

            if (is_null($folder)) {
                return view("home");
            }

            return redirect()->route
            ('tasks.index', [
                    'folder' => $folder->id,
                ]);
        } catch (\Throwable $e) {
            Log::error
            ('Error HomeController in index: ' . $e->getMessage());
        }
    }


}
