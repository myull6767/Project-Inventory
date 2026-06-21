document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('barang_search');
    const hiddenId = document.getElementById('barang_id');
    const dropdown = document.getElementById('barang-dropdown');
    const stokInfo = document.getElementById('stok-info');
    const tokoSelect = document.getElementById('kode_toko_select');
    const tokoInput = document.getElementById('kode_toko_inputed');
    const barangsData = document.getElementById('barangs-data');

    if (!searchInput || !hiddenId || !dropdown || !stokInfo || !barangsData) return;

    const barangs = JSON.parse(barangsData.textContent);

    function filterBarangs(query) {
        const q = query.toLowerCase().trim();
        return q === '' ? [] : barangs.filter(function (b) {
            return b.kode.toLowerCase().includes(q) || b.nama.toLowerCase().includes(q);
        });
    }

    function renderDropdown(results) {
        if (results.length === 0) {
            dropdown.classList.add('hidden');
            return;
        }
        dropdown.innerHTML = results.map(function (b) {
            return '<div class="px-3 py-2 font-mono text-xs cursor-pointer hover:bg-neutral border-b border-primary/5 last:border-0" data-id="' + b.id + '" data-stok="' + b.total_stok + '" data-packing="' + b.stok_packing + '" data-gudang="' + b.stok_gudang + '">' + b.kode + ' — ' + b.nama + '</div>';
        }).join('');
        dropdown.classList.remove('hidden');
    }

    function selectBarang(id) {
        var b = barangs.find(function (x) { return x.id === id; });
        if (!b) return;
        searchInput.value = b.kode + ' — ' + b.nama;
        hiddenId.value = b.id;
        dropdown.classList.add('hidden');
        stokInfo.textContent = 'Packing: ' + b.stok_packing + ' | Gudang: ' + b.stok_gudang + ' | Total: ' + b.total_stok;
    }

    searchInput.addEventListener('input', function () {
        if (hiddenId.value) {
            hiddenId.value = '';
            stokInfo.textContent = '';
        }
        var results = filterBarangs(this.value);
        renderDropdown(results);
    });

    dropdown.addEventListener('click', function (e) {
        var target = e.target.closest('[data-id]');
        if (target) {
            selectBarang(parseInt(target.dataset.id));
        }
    });

    document.addEventListener('click', function (e) {
        if (!dropdown.contains(e.target) && e.target !== searchInput) {
            dropdown.classList.add('hidden');
        }
    });

    searchInput.addEventListener('focus', function () {
        if (this.value.trim() && !hiddenId.value) {
            var results = filterBarangs(this.value);
            renderDropdown(results);
        }
    });

    if (hiddenId.value) {
        selectBarang(parseInt(hiddenId.value));
    }

    if (tokoSelect && tokoInput) {
        tokoSelect.addEventListener('change', function () {
            if (this.value) {
                tokoInput.value = this.value;
            }
        });
    }
});
