document.querySelectorAll('.btn-delete').forEach(function (button) {

    button.addEventListener('click', function () {

        const form = this.closest('.delete-form');

        // ✅ get custom message (fallback if not provided)
        const message = this.dataset.message || 'This item will be deleted!';

        Swal.fire({
            title: 'Are you sure?',
            text: message,
            icon: 'warning',

            showCancelButton: true,
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel',

            confirmButtonColor: '#1d5d41',
            cancelButtonColor: '#6b7280'

        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });

    });

});
