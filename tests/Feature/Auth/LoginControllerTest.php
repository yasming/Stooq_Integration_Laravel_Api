<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_should_authenticate_an_user()
    {
        User::create(['email' => 'jane@doe.com', 'password' => 123, 'name' => 'jane']);
        $this->post(route('api.login'),[
            'password' => '123',
            'email'    => 'jane@doe.com',
        ])->assertStatus(200);
    }

    public function test_it_should_not_authenticate_an_user()
    {
        $this->post(route('api.login'),[
            'password' => '123',
            'email'    => 'jane@doe.com',
        ])->assertStatus(401);
    }
}
