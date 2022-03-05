<?php

namespace Tests\Feature\Stocks;

use App\Models\User;
use App\Models\UserRequestsHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use JWTAuth;

class StockControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    function setUp(): void
    {
        parent::setUp();
        $this->user = User::create(['email' => 'jane@doe.com', 'password' => 123, 'name' => 'jane']);
    }

    public function test_it_should_validate_query_string_presence()
    {
        $this->get(route('api.stocks.show'),$this->headers($this->user))
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Query string q is required !',
            ]);
    }

    public function test_it_should_return_values_from_stooq_api()
    {
        $this->assertEquals(0, UserRequestsHistory::count());
        Http::fake([
            'stooq.com/*' => Http::response(
                "Symbol,Date,Time,Open,High,Low,Close,Volume,Name
AAPL.US,2022-03-04,22:00:07,164.49,165.55,162.1,163.17,83819592,APPLE"
            , 200, ['Headers']),
        ]);
        $this->get(route('api.stocks.show', ['q' => 'AAPL.US']),$this->headers($this->user))
            ->assertStatus(200)
            ->assertJson([
                'symbol' => 'AAPL.US',
                'date'   => '2022-03-04',
                'time'   => '22:00:07',
                'open'   => '164.49',
                'high'   => '165.55',
                'low'    => '162.1',
                'close'  => '163.17',
                'volume' => '83819592',
                'name'   => 'APPLE'
            ]);
        $userRequestHistory = UserRequestsHistory::first();
        $this->assertEquals(1, UserRequestsHistory::count());
        $this->assertEquals('2022-03-04 22:00:07', $userRequestHistory->date);
        $this->assertEquals('APPLE', $userRequestHistory->name);
        $this->assertEquals('AAPL.US', $userRequestHistory->symbol);
        $this->assertEquals('164.49', $userRequestHistory->open);
        $this->assertEquals('165.55', $userRequestHistory->high);
        $this->assertEquals('162.1', $userRequestHistory->low);
        $this->assertEquals('163.17', $userRequestHistory->close);
        $this->assertEquals($this->user->id, $userRequestHistory->user_id);
    }

    public function test_it_should_not_return_values_from_stooq_api()
    {
        Http::fake([
            'stooq.com/*' => Http::response([], 200, ['Headers']),
        ]);
        $this->get(route('api.stocks.show', ['q' => 'AAPL.US']),$this->headers($this->user))
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Was not possible to get the results !'
            ]);
        $this->assertEquals(0, UserRequestsHistory::count());
    }

    protected function headers($user = null)
    {
        $headers = ['Accept' => 'application/json'];

        if (!is_null($user)) {
            $token = JWTAuth::fromUser($user);
            JWTAuth::setToken($token);
            $headers['Authorization'] = 'Bearer '.$token;
        }

        return $headers;
    }
}
