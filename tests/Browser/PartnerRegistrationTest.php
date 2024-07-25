<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Sleep;
use Laravel\Dusk\Browser;

// uses(DatabaseMigrations::class);

test('a partner can visit the partner request page.', function () {
    $this->browse(function (Browser $browser) {
        $browser->visitRoute('partner.create')
                ->assertPathIs('/partner');

        Sleep::for(1)->seconds();
    });
});

test('a partner can submit the request form.', function () {
    $this->browse(function (Browser $browser) {
        $browser->type('first_name', config('constant.test.partner.first_name'))
                ->type('last_name', config('constant.test.partner.last_name'))
                ->type('email', config('constant.test.partner.email'))
                ->type('phone_number', config('constant.test.partner.phone_number'))
                ->type('business_name', config('constant.test.partner.business_name'))
                ->type('website', config('constant.test.partner.website'))
                ->type('country', config('constant.test.partner.country'))
                ->type('city', config('constant.test.partner.city'))
                ->type('message', config('constant.test.partner.message'))
                ->click('@submit-button');

        Sleep::for(3)->seconds();
    });

});

test('a partner can access the partner registration page after form submiting.', function () {
    $this->browse(function (Browser $browser) {
        $browser->waitForRoute('partner-account.create')
                ->assertRouteIs('partner-account.create')
                ->assertPathIs('/partner/account/create');

        Sleep::for(3)->seconds();
    });
});

test('a partner account can be created.', function () {
    $this->browse(function (Browser $browser) {
        $browser->type('password', config('constant.test.partner.password'))
                ->type('password_confirmation', config('constant.test.partner.password'))
                ->press('@submit-button')
                ->assertRouteIs('partner-account.store'); 

        Sleep::for(3)->seconds();       
    });
});

test('a partner is redirected and can access the partner account page.', function () {
    $this->browse(function (Browser $browser) {
        $browser->waitForRoute('partner-account')
                ->assertRouteIs('partner-account');

        Sleep::for(3)->seconds();       
    });
});