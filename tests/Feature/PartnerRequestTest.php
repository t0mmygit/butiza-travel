<?php

use App\Models\User;

test('registration page can be accessed', function () {
    $response = $this->get('/partner');

    $response->assertStatus(200);
});

test('registration requires all fields', function () {
    $response = $this->post('/partner', []);

    $response
        ->assertSessionHasErrors([
            'first_name',
            'last_name',
            'email',
            'phone_number',
            'business_name',
            'website',
            'country',
            'city',
        ])
        ->assertStatus(302);
});

test('registration requires valid email', function () {
    $response = $this->post('/partner', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe',
        'phone_number' => '23456789012',
        'business_name' => 'John Doe',
        'website' => 'https://johndoe.com',
        'country' => 'India',
        'city' => 'Bangalore',
    ]);

    $response
        ->assertSessionHasErrors([
            'email',
        ])
        ->assertStatus(302);
});

test('registration requires valid phone number', function () {
    $response = $this->post('/partner', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@gmail.com',
        'business_name' => 'John Doe',
        'website' => 'https://johndoe.com',
        'country' => 'India',
        'city' => 'Bangalore',
    ]);

    $response
        ->assertSessionHasErrors([
            'phone_number',
        ])
        ->assertStatus(302);
});

test('a partner request can be created.', function () {
    $response = $this->post('/partner', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@gmail.com',
        'phone_number' => '23456789012',
        'business_name' => 'John Doe',
        'website' => 'https://johndoe.com',
        'country' => 'India',
        'city' => 'Bangalore',
        'message' => 'Hello World',
    ]);
    
    $response
        ->assertSessionHasNoErrors()
        ->assertStatus(302);

    $this->assertDatabaseHas('partners', [
        'email' => 'john.doe@gmail.com',
    ]);
});
