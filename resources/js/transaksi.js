document.addEventListener('DOMContentLoaded', function () {
    const barangSelect = document.getElementById('barang_id');
    const stokInfo = document.getElementById('stok-info');
    const tokoSelect = document.getElementById('kode_toko_select');
    const tokoInput = document.getElementById('kode_toko_inputed');

    if (barangSelect && stokInfo) {
        barangSelect.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            const packing = opt.dataset.packing;
            const gudang = opt.dataset.gudang;
            const total = opt.dataset.stok;

            let parts = [];
            if (packing !== undefined) parts.push('Packing: ' + packing);
            if (gudang !== undefined) parts.push('Gudang: ' + gudang);
            if (total !== undefined) parts.push('Total: ' + total);
            stokInfo.textContent = parts.join(' | ');
        });
        barangSelect.dispatchEvent(new Event('change'));
    }

    if (tokoSelect && tokoInput) {
        tokoSelect.addEventListener('change', function () {
            if (this.value) {
                tokoInput.value = this.value;
            }
        });
    }
});
