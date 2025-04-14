/**
jQuery(document).ready(function($) {
    // Örnek AJAX çağrısı (admin-functions.php'de işleyici ekleyin)
    $('#save-button').on('click', function() {
        $.ajax({
            url: ajaxurl, // WordPress'in ajaxurl değişkeni
            type: 'POST',
            data: {
                action: 'cariflow_kaydet', // PHP tarafındaki hook
                nonce: cariflow_ajax.nonce, // Güvenlik nonce'si
                form_data: $('#your-form').serialize()
            },
            success: function(response) {
                alert('İşlem başarılı!');
            },
            error: function(xhr, status, error) {
                console.error('Hata:', error);
            }
        });
    });
});
*/


jQuery(document).ready(function($) {
    $('.cariflow-delete').on('click', function(e) {
        if (!confirm('Silmek istediğinize emin misiniz?')) {
            e.preventDefault();
        }
    });
});