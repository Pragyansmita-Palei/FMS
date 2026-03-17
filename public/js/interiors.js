document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.btn-edit').forEach(function (btn) {

        btn.addEventListener('click', function () {

            let id = this.dataset.id;

            document.getElementById('edit_firm_name').value = this.dataset.firm_name ?? '';
            document.getElementById('edit_email').value     = this.dataset.email ?? '';
            document.getElementById('edit_phone').value     = this.dataset.phone ?? '';
            document.getElementById('edit_address').value   = this.dataset.address ?? '';

            let form = document.getElementById('editInteriorForm');
            form.action = "{{ url('interiors') }}/" + id;

        });

    });

});