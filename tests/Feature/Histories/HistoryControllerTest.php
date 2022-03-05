<?php

namespace Tests\Feature\Histories;

use App\Models\User;
use App\Models\UserRequestsHistory;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use JWTAuth;

class HistoryControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    function setUp(): void
    {
        parent::setUp();
        $this->user = User::create(['email' => 'jane@doe.com', 'password' => 123, 'name' => 'jane']);
        UserRequestsHistory::create([
            'date'    => Carbon::parse('2022-03-04' . ' '. '22:00:07'),
            'name'    => 'APPLE',
            'symbol'  => 'AAPL.US',
            'open'    => '164.49',
            'high'    => '165.55',
            'low'     => '162.1',
            'close'   => '163.17',
            'user_id' => $this->user->id
        ]);
    }

    public function test_it_should_show_histories_for_logged_user()
    {
        $response = $this->get(route('api.histories.index'),$this->headers($this->user))
            ->assertStatus(200);

        $this->assertEquals(
            UserRequestsHistory::whereUserId($this->user->id)->paginate(20)->toArray(),
            $response->json()
        );
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
