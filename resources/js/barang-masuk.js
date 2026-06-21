document.addEventListener('DOMContentLoaded', function () {
    var barangsData = document.getElementById('barangs-data');
    if (!barangsData) return;

    var barangs = JSON.parse(barangsData.textContent);
    var searchInput = document.getElementById('barang_search');
    var hiddenId = document.getElementById('barang_id');
    var dropdown = document.getElementById('barang-dropdown');
    var stokGudangInfo = document.getElementById('stok-gudang-info');
    var stokInfo = document.querySelector('.stok-info');

    if (!searchInput || !hiddenId || !dropdown) return;

    function filterBarangs(query) {
        var q = query.toLowerCase().trim();
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
            return '<div class="px-3 py-2 font-mono text-xs cursor-pointer hover:bg-neutral border-b border-primary/5 last:border-0" data-id="' + b.id + '" data-gudang="' + b.stok_gudang + '" data-packing="' + b.stok_packing + '" data-total="' + b.total_stok + '">' + b.kode + ' — ' + b.nama + '</div>';
        }).join('');
        dropdown.classList.remove('hidden');
    }

    function selectBarang(id) {
        var b = barangs.find(function (x) { return x.id === id; });
        if (!b) return;
        searchInput.value = b.kode + ' — ' + b.nama;
        hiddenId.value = b.id;
        dropdown.classList.add('hidden');
        if (stokGudangInfo) {
            stokGudangInfo.textContent = 'Stok Gudang: ' + b.stok_gudang;
        }
        if (stokInfo) {
            stokInfo.textContent = 'Gudang: ' + b.stok_gudang + ' | Packing: ' + b.stok_packing + ' | Total: ' + b.total_stok;
        }
    }

    searchInput.addEventListener('input', function () {
        if (hiddenId.value) {
            hiddenId.value = '';
            if (stokGudangInfo) stokGudangInfo.textContent = '';
            if (stokInfo) stokInfo.textContent = '';
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
});
