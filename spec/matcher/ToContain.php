<?php
namespace testing\spec\matcher;

use Exception;
use Behat\Mink\Element\Element;
use testing\spec\api\ElementNotFound;
use kahlan\util\Text;

class ToContain {

    protected static $_description;

    /**
     * Expect that `$actual` contain `$expected`.
     *
     * @param  object  $actual   The actual value.
     * @param  string  $expected The expected value.
     * @return boolean
     */
    public static function match($actual, $expected)
    {
        if ($actual instanceof ElementNotFound) {
            static::_buildDescription((string) $actual, $expected);
            return false;
        }

        if (!$actual instanceof Element) {
            throw new Exception("Actual is not a valid `Behat\Mink\Element\Element`.");
        }

        $actual = $actual->getText();

        static::_buildDescription($actual, $expected);

        return strpos($actual, $expected) !== false;
    }

    /**
     * Build the description of the runned `::match()` call.
     *
     * @param string $actual   The actual value.
     * @param string $expected The expected value.
     */
    public static function _buildDescription($actual, $expected)
    {
        $description = "to contain the expected text.";
        $params['actual'] = $actual;
        $params['expected'] = $expected;

        static::$_description = compact('description', 'params');
    }

    /**
     * Returns the description report.
     */
    public static function description()
    {
        return static::$_description;
    }
}
