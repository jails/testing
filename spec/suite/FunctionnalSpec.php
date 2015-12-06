<?php
namespace Testing\Spec\Suite;

describe("Google", function() {

    it("finds the Google logo", function() {

        browser()->visit('http://www.google.fr');
        expect(element('#hplogo'))->not->toBeAnInstanceOf('Testing\Spec\Api\ElementNotFound');

    });

    it("finds Kahlan in the search result", function() {

        browser()->visit('http://www.google.fr');
        page()->fillField('q', 'Unit/BDD PHP Test Framework for Freedom, Truth, and Justice');
        page()->pressButton('btnG');
        wait(page())->toContain('Kahlan');

    });

}, 10);
