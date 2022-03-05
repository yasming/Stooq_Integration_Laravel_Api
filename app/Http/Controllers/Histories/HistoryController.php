<?php

namespace App\Http\Controllers\Histories;

use App\Http\Controllers\Controller;
use App\Models\UserRequestsHistory;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        return response()->json(UserRequestsHistory::whereUserId(auth()->user()->id)->paginate(20));
    }
}
