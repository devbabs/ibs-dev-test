<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

uses(RefreshDatabase::class);

it('returns 401 when incorrect credentials are passed', function() {
    $response = $this->postJson(route('api.auth.login'), [
        'email' => 'something@email.com',
        'password' => '123456'
    ]);
    $response->assertStatus(401);
});

it('returns 422 when only email is passed', function() {
    $response = $this->postJson(route('api.auth.login'), [
        'email' => 'something@email.com',
        'password' => ''
    ]);
    $response->assertStatus(422);
});

it('returns 422 when only password is passed', function() {
    $response = $this->postJson(route('api.auth.login'), [
        'email' => '',
        'password' => '123456'
    ]);
    $response->assertStatus(422);
});

it('returns 422 when nothing is passed', function() {
    $response = $this->postJson(route('api.auth.login'), []);
    $response->assertStatus(422);
});

it('returns 200 with bearer token when correct credentials are passed', function() {
    $email = 'ibs-dev@email.com';
    $password = 'password';

    User::factory()->create([
        'email' => $email,
        'password' => bcrypt($password)
    ]);

    $response = $this->postJson(route('api.auth.login'), [
        'email' => $email,
        'password' => $password,
    ]);

    $response->assertStatus(200)->assertJsonStructure([
        'access_token',
        'token_type'
    ]);
});

it('can log user out and invalidate token', function() {
    $email = 'ibs-dev@email.com';
    $password = 'password';

    $user = User::factory()->create([
        'email' => $email,
        'password' => bcrypt($password)
    ]);

    $response = $this->postJson(route('api.auth.login'), [
        'email' => $email,
        'password' => $password,
    ]);

    $loginToken = json_decode($response->decodeResponseJson()->json)->access_token;

    $this->withHeaders([
        'Authorization' => "Bearer {$loginToken}"
    ])->postJson(route('api.auth.logout'))->assertStatus(200);
});
