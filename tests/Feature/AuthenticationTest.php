<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;
    /**
    *   @test
    */
    public function testAuthenticateUserThenFailOnceUserDoesntExist()
    {
        $response = $this->post(route('login'), [
            'username' => 'sample',
            'password' => 'sample'
        ]);
        $response->assertJson(['username' => 'The provided credentials are incorrect']);
        $response->assertStatus(422);
    }

    public function testReturnJsonDataOnceUserIsAuthenticated()
    {
        $user = User::factory()->create();
        $response = $this->post(route('login'), [
            'username' => $user->username,
            'password' => '1234'
        ]);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(['token', 'user'])
        );
    }

    public function testReturnJsonErrorMessageOnceUsernameFieldDoesntExistInRequest()
    {
        $response = $this->post(route('login'),[
            'password' => '1234'
        ]);

        $response->assertJsonMissingValidationErrors(['username']);
    }

    public function testReturnJsonErrorMessageOncePasswordFieldDoesntExistInRequest()
    {
        $response = $this->post(route('login'),[
            'username' => 'sample'
        ]);

        $response->assertJsonMissingValidationErrors(['password']);
    }


}
