<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_should_create_an_user()
    {
        $this->post(route('api.users.store'),[
            'password' => '123',
            'email'    => 'jane@doe',
            'name'     => 'jane',
        ])->assertStatus(200)->assertJson([
            'message' => 'User created successfully !',
        ]);

        $user = User::first();
        $this->assertEquals(1, User::count());
        $this->assertEquals('jane@doe', $user->email);
        $this->assertEquals('jane', $user->name);
        $this->assertTrue(Hash::check('123', $user->password));
    }

    /**
     * @dataProvider getFields
     *
     * @var string $field
     * @var string $fieldName
    */
    public function test_it_validate_required_fields(string $field, string $fieldName)
    {
        $this->post(route('api.users.store'),
            []
        )->assertSessionHasErrors([
            $field  => __('validation.required', [
                'attribute' => $fieldName,
            ])
        ]);
    }

    /**
     * @dataProvider getFields
     *
     * @var string $field
     * @var string $fieldName
     */
    public function test_it_validate_string_fields(string $field, string $fieldName)
    {
        $this->post(route('api.users.store'),
            [
                'name'     => 123,
                'password' => 123,
                'email'    => 123
            ]
        )->assertSessionHasErrors([
            $field  => __('validation.string', [
                'attribute' => $fieldName,
            ])
        ]);
    }

    public function test_it_validate_email_unique()
    {
        User::create(['email' => 'jane@doe.com', 'password' => 123, 'name' => 'jane']);
        $this->post(route('api.users.store'),
            ['email' => 'jane@doe.com']
        )->assertSessionHasErrors([
            'email'  => __('validation.unique', [
                'attribute' => 'email',
            ])
        ]);
    }

    /**
     * return fields
     *
     * @return array
     */
    public function getFields(): array
    {
        return [
            ['name', 'name'],
            ['email', 'email'],
            ['password', 'password'],
        ];
    }
}
