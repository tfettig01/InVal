<?php
declare(strict_types=1);

namespace InputGuardTests\Guards\Bases;

use InputGuard\Guards\Bases\SingleInput;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class SingleInputTest extends TestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testAllowNulls(): void
    {
        $object = new class()
        {
            use SingleInput {
                // A hack for some weirdness in phpmd.
                SingleInput::allowNull as allowNullWhat;
                SingleInput::success as successWhat;
            }

            public function __construct()
            {
                $this->input = null;
            }

            public function getValidated()
            {
                return $this->validated;
            }

            protected function validation(): bool
            {
                return false;
            }

        };

        $object->allowNullWhat();

        self::assertTrue($object->successWhat() && $object->getValidated());
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testAllowEmptyString(): void
    {
        $object = new class()
        {
            use SingleInput {
                // A hack for some weirdness in phpmd.
                SingleInput::allowEmptyString as allowEmptyStringWhen;
                SingleInput::success as successWhen;
            }

            public function __construct()
            {
                $this->input = '';
            }

            public function getValidated()
            {
                return $this->validated;
            }

            protected function validation(): bool
            {
                return false;
            }
        };

        $object->allowEmptyStringWhen();

        self::assertTrue($object->successWhen() && $object->getValidated());
    }
}