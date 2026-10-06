<?php

declare(strict_types=1);

require_once './Transaction.php';

session_start();

if (!isset($_SESSION['balance'])) {
    $_SESSION['balance'] = 0.0;
}

if (!isset($_SESSION['transactions'])) {
    $_SESSION['transactions'] = [];
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postToken = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'], $postToken)) {
        $error = 'Permintaan tidak valid. Token CSRF tidak cocok.';
    } else {
        $typeInput = $_POST['type'] ?? '';
        $amountInput = trim($_POST['amount'] ?? '');
    }

    $type = match ($typeInput) {
        'deposit' => 'deposit',
        'withdraw' => 'withdraw',
        default => null
    };

    if ($type === null) {
        $error = 'Jenis transaksi tidak valid.';
    } elseif (!is_numeric($amountInput) || (float) $amountInput <= 0) {
        $error = 'Jumlah transaksi harus berupa angka positif.';
    } else {
        $amount = (float) $amountInput;

        $transaction = new Transaction(
            bin2hex(random_bytes(8)),
            $type,
            $amount
        );

        if ($transaction->process()) {
            $_SESSION['transaction'][] = [
                'id' => $transaction->getId(),
                'type' => $transaction->getType(),
                'amount' => $transaction->getAmount(),
                'balance' => $_SESSION['balance']
            ];

            $message = 'Transaksi berhasil diproses.';

            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        } else {
            $error = 'Saldo tidak mencukupi untuk melakukan penarikan.';
        }
    }
}
?>