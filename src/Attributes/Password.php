<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Attributes;

use Attribute;
use Illuminate\Validation\Rules\Password as BasePassword;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Password
{
    public function __construct(
        public int $min = 8,
        public bool $letters = true,
        public bool $mixedCase = false,
        public bool $numbers = false,
        public bool $symbols = false,
        public bool $uncompromised = false,
        public int $compromisedThreshold = 0,
    ) {
    }

    public function getRule(): BasePassword
    {
        $rule = BasePassword::min($this->min);

        if ($this->letters) {
            $rule->letters();
        }

        if ($this->mixedCase) {
            $rule->mixedCase();
        }

        if ($this->numbers) {
            $rule->numbers();
        }

        if ($this->symbols) {
            $rule->symbols();
        }

        if ($this->uncompromised) {
            $rule->uncompromised($this->compromisedThreshold);
        }

        return $rule;
    }
}
