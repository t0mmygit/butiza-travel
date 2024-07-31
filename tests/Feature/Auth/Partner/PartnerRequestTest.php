<?php

test('partner registration page can be accessed', function () {
    $response = $this->get('/partner');

    $response->assertStatus(200);
});