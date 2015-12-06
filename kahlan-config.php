<?php
require __DIR__ . "/spec/Api/Helpers.php";

use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use Peridot\WebDriverManager\Manager;
use Kahlan\Box\Box;
use Kahlan\Code\Code;
use Kahlan\Code\TimeoutException;
use Kahlan\Filter\Filter;
use Kahlan\Matcher;

$box = box('spec', new Box());

$box->service('manager', function() {
    $manager = new Manager();
    $manager->update();
    return $manager->startInBackground();
});

$box->service('mink', function() {
    $selenium = new Selenium2Driver('firefox', null, 'http://localhost:4444/wd/hub');
    $mink = new Mink(['firefox' => new Session($selenium)]);
    $mink->setDefaultSessionName('firefox');
    return $mink;
});

Filter::register('exclude.namespaces', function ($chain) {
    $defaults = ['Behat'];
    $excluded = $this->args()->get('exclude');
    $this->args()->set('exclude', array_unique(array_merge($excluded, $defaults)));
    return $chain->next();
});

Filter::register('run.webdriver', function($chain) {
    $process = box('spec')->get('manager');
    try {
        $fp = Code::spin(function() {
            return @fsockopen('localhost', 4444);
        }, 5, true);
        fclose($fp);
    } catch (TimeoutException $e) {
        echo "Unable to run the WebDriver binary, abording.\n";
        $process->close();
        exit(-1);
    }
    return $chain->next();
});

Filter::register('register.globals', function ($chain) {
    $root = $this->suite();
    $root->mink = $mink = box('spec')->get('mink');

    $root->afterEach(function() use ($mink) {
        $mink->resetSessions();
    });
    return $chain->next();
});

Filter::register('register.matchers', function ($chain) {
    Matcher::register('toContain', 'Testing\Spec\Matcher\ToContain', 'Behat\Mink\Element\Element');
    return $chain->next();
});

Filter::register('cleanup', function ($chain) {
    $box = box('spec');
    $box->get('mink')->stopSessions();
    $box->get('manager')->close();
    return $chain->next();
});

Filter::apply($this, 'interceptor', 'exclude.namespaces');
Filter::apply($this, 'run', 'run.webdriver');
Filter::apply($this, 'run', 'register.globals');
Filter::apply($this, 'run', 'register.matchers');
Filter::apply($this, 'stop', 'cleanup');
