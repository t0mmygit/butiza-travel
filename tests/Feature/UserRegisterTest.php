<?php

test('a normal user can register successfully', function () {
    $response = $this->post('/register', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
        'password' => 'Password@123',
        'password_confirmation' => 'Password@123',
    ]);
    
    $this
        ->assertAuthenticated()
        ->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com'
        ]); 

    $response
        ->assertStatus(302);
        // ->assertRedirect(route('/home', absolute: false));
});

test('a partner can register successfully', function () {
    $response = $this->post('/register', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
        'password' => 'Password@123',
        'password_confirmation' => 'Password@123',
    ]);
    
    $this
        ->assertAuthenticated()
        ->assertDatabaseHas('users', 
            ['email' => 'john.doe@example.com'
        ]); 

    $response
        ->assertStatus(302);
});


