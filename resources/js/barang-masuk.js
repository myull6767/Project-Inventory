document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('barang_id');
    const info = document.getElementById('stok-gudang-info');

    if (select && info) {
        select.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            const gudang = opt.dataset.gudang;
            info.textContent = gudang !== undefined ? 'Stok Gudang: ' + gudang : '';
        });
        select.dispatchEvent(new Event('change'));
    }
});
