<?php
declare(strict_types=1);

namespace InputGuard\Guards\Bases;

use ArrayAccess;
use Countable;
use Traversable;

trait SingleIterableInput
{
    use SingleInput;

    /**
     * @var int
     */
    private $minCount = 0;

    /**
     * @var int|null;
     */
    private $maxCount;

    /**
     * @var bool
     */
    private $allowNullElement = false;

    /**
     * A method to allow extra validation to be done for each element of an iterable.
     *
     * @param mixed $element
     * @param mixed $value
     *
     * @return bool
     */
    abstract protected function validateIterableElement($element, &$value): bool;

    public function maxCount(int $max): self
    {
        $this->maxCount = $max;

        return $this;
    }

    public function minCount(int $min): self
    {
        $this->minCount = $min;

        return $this;
    }

    public function betweenCount(int $min, int $max): self
    {
        $this->minCount = $min;
        $this->maxCount = $max;

        return $this;
    }

    public function allowNullElement(): self
    {
        $this->allowNullElement = true;

        return $this;
    }

    public function value(): ?iterable
    {
        $this->success();

        return $this->value;
    }

    public function valueAsArray(): array
    {
        $this->success();

        return $this->value instanceof Traversable ? iterator_to_array($this->value) : (array)$this->value;
    }

    /**
     * @param $input
     * @param $value
     *
     * @return bool
     */
    protected function validation($input, &$value): bool
    {
        if (is_iterable($input) === false) {
            return false;
        }

        /** @var iterable $input */
        if ($this->isBetweenCountSize($input) === false) {
            return false;
        }

        $can_be_updated = $this->elementsCanBeUpdated($input);

        foreach ($input as $key => $element) {
            if ($element === null) {
                if ($this->allowNullElement) {
                    continue;
                }

                return false;
            }

            $element_value = null;
            if ($this->validateIterableElement($element, $element_value) === false) {
                return false;
            }

            if ($can_be_updated) {
                /** @noinspection OffsetOperationsInspection */
                $input[$key] = $element_value;
            }

            // @todo Make the method return false if strict validation is disabled and the iterable cannot be updated.
        }

        $value = $input;

        return true;
    }

    private function isBetweenCountSize(iterable $input): bool
    {
        /** @noinspection PhpParamsInspection */
        $count = \is_array($input) || $input instanceof Countable ? count($input) : iterator_count($input);

        return $this->isMoreThanMinCount($count) && $this->isLessThanMaxCount($count);
    }

    private function isMoreThanMinCount(int $count): bool
    {
        return $count >= $this->minCount;
    }

    private function isLessThanMaxCount(int $count): bool
    {
        return $this->maxCount === null || $count <= $this->maxCount;
    }

    protected function elementsCanBeUpdated(iterable $input): bool
    {
        // At this time only iterable that are arrays or implement array access can be modified.
        // I need to look into using http://www.php.net/manual/en/closure.bind.php or Refection to see if it's
        // possible to modify the elements of an object that only implements the Iterator interface.
        return \is_array($input) || $input instanceof ArrayAccess;
    }
}