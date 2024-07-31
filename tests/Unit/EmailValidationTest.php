<?php

use App\Models\User;

test('successful email validation.', function () {
    $user = User::factory()->create(
        ['email' => 'johndoe@gmail.com']
    );

    $this->post(route('email.validate'), [
        'email' => $user->email,
    ])->assertJsonValidationErrors('email');
});

test('unsuccessful email validation.', function () {
    $user = User::factory()->create(
        ['email' => 'johndoe@gmail.com']
    );

    $this->post(route('email.validate'), [
        'email' => $user->email,
    ])->assertJsonValidationErrors('email');
});