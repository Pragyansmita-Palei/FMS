document.querySelectorAll('.btn-delete').forEach(function (button) {

    button.addEventListener('click', function () {

        const form = this.closest('.delete-form');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This labour will be deleted!',
            icon: 'warning',

            showCancelButton: true,
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel',

            // ✅ button colors
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#6b7280'

        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });

    });

});
