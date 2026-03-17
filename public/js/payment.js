document.addEventListener('DOMContentLoaded', function () {

    const paymentMode = document.getElementById('payment_mode');
    const txnInput    = document.getElementById('transaction_number');

    function toggleTxn() {

        if (paymentMode.value === 'Cash') {
            txnInput.value = '';
            txnInput.removeAttribute('required');
            txnInput.closest('.col-md-12').style.display = 'none';
        } else {
            txnInput.setAttribute('required', 'required');
            txnInput.closest('.col-md-12').style.display = 'block';
        }
    }

    if(paymentMode && txnInput){
        toggleTxn();
        paymentMode.addEventListener('change', toggleTxn);
    }

});
