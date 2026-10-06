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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Keuangan</title>
</head>

<body>
    <h1>Sistem Manajemen Keuangan Sederhana</h1>

    <h2>
        Saldo:
        Rp<?= htmlspecialchars(number_format($_SESSION['balance'], 2, ',', '.')) ?>
    </h2>

    <?php if ($message !== ''): ?>
        <p>
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <?php if ($error !== ''): ?>
        <p>
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <h2>Transaksi Baru</h2>

    <form action="" method="POST">
        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars(
                $_SESSION['csrf_token'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

        <div>
            <label for="type">Jenis Transaksi</label>
            <select name="type" id="type" required>
                <option value="">-- Pilih transaksi --</option>
                <option value="deposit">Deposit</option>
                <option value="withdraw">Penarikan</option>
            </select>
        </div>

        <br>

        <div>
            <label for="amount">Jumlah</label>
            <input
                type="number"
                name="amount"
                id="amount"
                min="0.01"
                step="0.01"
                required
            >
        </div>

        <br>

        <button type="submit">
            Proses Transaksi
        </button>
    </form>

    <h2>Riwayat Transaksi</h2>

    <?php if (empty($_SESSION['transactions'])): ?>
        <p>Belum ada transaksi.</p>
    <?php else: ?>
        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Saldo Setelah Transaksi</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($_SESSION['transactions'] as $transaction): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars(
                                $transaction['id'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $transaction['type'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>

                        <td>
                            Rp<?= htmlspecialchars(
                                number_format(
                                    $transaction['amount'],
                                    2,
                                    ',',
                                    '.'
                                )
                            ) ?>
                        </td>

                        <td>
                            Rp<?= htmlspecialchars(
                                number_format(
                                    $transaction['balance'],
                                    2,
                                    ',',
                                    '.'
                                )
                            ) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>