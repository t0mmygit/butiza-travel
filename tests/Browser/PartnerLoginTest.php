<?php

use Illuminate\Support\Sleep;
use Laravel\Dusk\Browser;

test('a partner can visit the login page.', function () {
    $this->browse(function (Browser $browser) {
        $browser->visitRoute('partner-login.create')
                ->assertPathIs('/partner/login');
    });
});

test('a partner can login.', function () {
    $this->browse(function (Browser $browser) {
        $browser->type('email', config('constant.test.partner.email'))
                ->type('password', config('constant.test.partner.password'))
                ->press('@login-button');
    });
});

test('a partner is redirected and can access the partner account page.', function () {
    $this->browse(function (Browser $browser) {
        $browser->waitForRoute('partner-account')
                ->assertRouteIs('partner-account');
    });

    Sleep::for(5)->seconds();
});