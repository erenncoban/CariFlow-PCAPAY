<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Fatura #<?= $musteri->id ?></title>
    <style>
        body { font-family: Arial; }
        .header { text-align: center; margin-bottom: 20px; }
        .info { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>FATURA</h1>
        <p>No: <?= date('Ymd') ?>-<?= $musteri->id ?></p>
    </div>
    
    <div class="info">
        <p><strong>Müşteri:</strong> <?= $musteri->ad ?> <?= $musteri->soyad ?></p>
        <p><strong>Vergi No:</strong> <?= $musteri->vergi_no ?></p>
        <p><strong>Tarih:</strong> <?= date('d.m.Y') ?></p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Açıklama</th>
                <th>Tutar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($islemler as $islem): ?>
            <tr>
                <td><?= $islem->aciklama ?></td>
                <td><?= number_format($islem->tutar, 2) ?> TL</td>
            </tr>
            <?php endforeach; ?>
            <tr class="total">
                <td>TOPLAM</td>
                <td><?= number_format(array_sum(array_column($islemler, 'tutar')), 2) ?> TL</td>
            </tr>
        </tbody>
    </table>
</body>
</html>