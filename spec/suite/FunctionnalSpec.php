<?php
namespace testing\spec\suite;

describe("Google", function() {

    it("finds the Google logo", function() {

        browser()->visit('http://www.google.fr');
        expect(element('#hplogo')->getAttribute('title'))->toBe('Google');

    });

    it("finds Kahlan in the search result", function() {

        browser()->visit('http://www.google.fr');
        page()->fillField('q', 'Unit/BDD PHP Test Framework for Freedom, Truth, and Justice');
        page()->pressButton('btnG');
        wait(page())->toContain('Kahlan');

    });

}, 10);
