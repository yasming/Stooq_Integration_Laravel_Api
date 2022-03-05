<?php

namespace App\Http\Controllers\Stocks;

use App\Http\Controllers\Controller;
use App\Models\UserRequestsHistory;
use App\Services\Gateways\Stooq\Stooq;
use Carbon\Carbon;
use Illuminate\Http\Response;

class StockController extends Controller
{
    public function show()
    {
        if (!request()->exists('q')) {
            return response()->json(['message' => 'Query string q is required !'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $stooqResults = (new Stooq(request()->get('q')))->getResults();
        if ($stooqResults == false) {
            return response()->json(['message' => 'Was not possible to get the results !'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $this->logResults($stooqResults);
        return response()->json($this->formatResults($stooqResults));
    }

    private function logResults($stooqResults)
    {
        UserRequestsHistory::create([
            'date'    => Carbon::parse($stooqResults[1] . ' '.$stooqResults[2]),
            'name'    => $stooqResults[8],
            'symbol'  => $stooqResults[0],
            'open'    => $stooqResults[3],
            'high'    => $stooqResults[4],
            'low'     => $stooqResults[5],
            'close'   => $stooqResults[6],
            'user_id' => auth()->user()->id
        ]);
    }

    private function formatResults($results)
    {
        return [
            'symbol' => $results[0],
            'date'   => $results[1],
            'time'   => $results[2],
            'open'   => $results[3],
            'high'   => $results[4],
            'low'    => $results[5],
            'close'  => $results[6],
            'volume' => $results[7],
            'name'   => $results[8]
        ];
    }
}
