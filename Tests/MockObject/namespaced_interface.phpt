--TEST--
PHPUnit_Framework_MockObject_Generator::generate('NS\Foo', array(), 'MockFoo', TRUE, TRUE)
--FILE--
<?php
namespace NS;

interface Foo
{
    public function bar(Foo $foo);
}

require_once 'PHPUnit/Autoload.php';
require_once 'Text/Template.php';

$mock = \PHPUnit_Framework_MockObject_Generator::generate(
  'NS\Foo',
  array(),
  'MockFoo',
  TRUE,
  TRUE
);

print $mock['code'];
?>
--EXPECTF--
class MockFoo implements PHPUnit_Framework_MockObject_MockObject, NS\Foo
{
    private static $staticInvocationMocker;
    private $invocationMocker;
    private $id;
    private static $nextId = 0;

    public function __clone()
    {
        $this->invocationMocker = clone $this->__phpunit_getInvocationMocker();
        $this->__phpunit_setId();
    }

    public function bar(NS\Foo $foo)
    {
        $arguments = array($foo);
        $count     = func_num_args();

        if ($count > 1) {
            $_arguments = func_get_args();

            for ($i = 1; $i < $count; $i++) {
                $arguments[] = $_arguments[$i];
            }
        }

        $result = $this->__phpunit_getInvocationMocker()->invoke(
          new PHPUnit_Framework_MockObject_Invocation_Object(
            'NS\Foo', 'bar', $arguments, $this, TRUE
          )
        );

        return $result;
    }

    public function expects(PHPUnit_Framework_MockObject_Matcher_Invocation $matcher)
    {
        return $this->__phpunit_getInvocationMocker()->expects($matcher);
    }

    public static function staticExpects(PHPUnit_Framework_MockObject_Matcher_Invocation $matcher)
    {
        return self::__phpunit_getStaticInvocationMocker()->expects($matcher);
    }

    public function __phpunit_getInvocationMocker()
    {
        if ($this->invocationMocker === NULL) {
            $this->invocationMocker = new PHPUnit_Framework_MockObject_InvocationMocker;
        }

        return $this->invocationMocker;
    }

    public static function __phpunit_getStaticInvocationMocker()
    {
        if (self::$staticInvocationMocker === NULL) {
            self::$staticInvocationMocker = new PHPUnit_Framework_MockObject_InvocationMocker;
        }

        return self::$staticInvocationMocker;
    }

    public function __phpunit_hasMatchers()
    {
        return self::__phpunit_getStaticInvocationMocker()->hasMatchers() ||
               $this->__phpunit_getInvocationMocker()->hasMatchers();
    }

    public function __phpunit_verify()
    {
        self::__phpunit_getStaticInvocationMocker()->verify();
        $this->__phpunit_getInvocationMocker()->verify();
    }

    public function __phpunit_cleanup()
    {
        self::$staticInvocationMocker = NULL;
        $this->invocationMocker       = NULL;
        $this->id                     = NULL;
    }

    public function __phpunit_setId()
    {
        $this->id = sprintf('%s#%s', get_class($this), self::$nextId++);
    }
}
