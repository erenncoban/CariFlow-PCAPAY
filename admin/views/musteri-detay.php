<div class="wrap">
    <h1><?= esc_html($musteri->ad.' '.$musteri->soyad) ?> - Detay</h1>
    
    <div class="card">
        <h3>İletişim Bilgileri</h3>
        <p><strong>Telefon:</strong> <?= esc_html($musteri->telefon) ?></p>
        <p><strong>E-posta:</strong> <?= esc_html($musteri->email) ?></p>
        <p><strong>Kayıt Tarihi:</strong> <?= date('d.m.Y H:i', strtotime($musteri->kayit_tarihi)) ?></p>
    </div>
    
    <h2>İşlem Geçmişi</h2>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Tarih</th>
                <th>Tür</th>
                <th>Tutar</th>
                <th>Açıklama</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($islemler as $islem): ?>
            <tr>
                <td><?= date('d.m.Y H:i', strtotime($islem->tarih)) ?></td>
                <td><?= $islem->tur == 'borç' ? '<span style="color:red">Borç</span>' : '<span style="color:green">Alacak</span>' ?></td>
                <td><?= number_format($islem->tutar, 2) ?> ₺</td>
                <td><?= esc_html($islem->aciklama) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <a href="<?= admin_url('admin.php?page=cariflow-islem-ekle&musteri_id='.$musteri->id) ?>" 
       class="button button-primary">
        Bu Müşteriye İşlem Ekle
    </a>
</div>