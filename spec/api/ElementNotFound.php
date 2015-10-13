<?php
namespace testing\spec\api;

use Exception;

class ElementNotFound
{
    protected $_selector;

    public function __construct($selector)
    {
        $this->_selector = $selector;
    }

    public function __call($name, $args = [])
    {
        throw new Exception("Can't call `{$name}()` on `'{$this->_selector}'` no matching element found.");
    }

    public function __toString()
    {
        return "No matching element found for `'{$this->_selector}'`";
    }
}
