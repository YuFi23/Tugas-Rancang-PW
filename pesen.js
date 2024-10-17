document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    var nama = document.querySelector('#nama').value;
    var email = document.querySelector('#email').value;
    var noTelp = document.querySelector('#no-telp').value;
    var metodePembayaran = document.querySelector('#metode-pembayaran').value;
    
    if (nama === '' || email === '' || noTelp === '' || metodePembayaran === '') {
        alert('Silakan isi semua field!');
    } else {
        //
    }
});