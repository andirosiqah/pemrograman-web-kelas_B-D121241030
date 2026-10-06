<?php

declare(strict_types=1);

class Transaction
{
    public function __construct(
        private string $id,
        private string $type,
        private float $amount
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function process(): bool
    {
        if (!isset($_SESSION['balance'])) {
            $_SESSION['balance'] = 0.0;
        }

        if ($this->type === 'deposit') {
            $_SESSION['balance'] += $this->amount;

            return true;
        }

        if ($this->type === 'withdraw') {
            if ($_SESSION['balance'] < $this->amount) {
                return false;
            }

            $_SESSION['balance'] -= $this->amount;

            return true;
        }

        return false;
    }
}