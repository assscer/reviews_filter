$(document).ready(function () {
    $('#reviewForm').on('submit', function (e) {
        e.preventDefault(); 

        const formData = new FormData(this);

        $.ajax({
            url: 'process.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('#reviews').prepend(response); 
                $('#reviewForm')[0].reset(); 
            },
            error: function () {
                alert('Ошибка при добавлении отзыва.');
            },
        });
    });

    $('#sortOrder').on('change', function () {
        const sortOrder = $(this).val();
        window.location.href = `index.php?order=${sortOrder}`;
    });
});
