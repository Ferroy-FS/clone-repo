<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    public function track(Request $request)
    {
        return response()->json(['status' => true, 'message' => 'Tracked']);
    }
}
