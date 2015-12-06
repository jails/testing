<?php
use Kahlan\Suite;
use Testing\Spec\Api\ElementNotFound;

/**
 * Gets the browser session.
 *
 * @param  string  $session The session name.
 * @return Session
 */
function browser($session = null)
{
    return Suite::current()->mink->getSession($session);
}

/**
 * Gets the browser page.
 *
 * @param  string          $session The session name.
 * @return DocumentElement
 */
function page($session = null)
{
    return browser($session)->getPage();
}

/**
 * @param  string $selector The CSS selector
 * @param  object $parent   The parent `Element` node.
 * @return object           Returns a `NodeElement`.
 */
function element($selector = 'body', $parent = null)
{
    $parent = $parent ?: page();
    $element = $parent->find('css', $selector);
    return $element ?: new ElementNotFound($selector);
}

/**
 * API shortcut.
 *
 * @param  mixed          $actual The actual value.
 * @return kahlan\Matcher         A matcher instance.
 */
function wait($actual, $timeout = 0)
{
    return waitsFor(function() use ($actual) {
        return $actual;
    }, $timeout);
}
