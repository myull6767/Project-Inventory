document.addEventListener('DOMContentLoaded', function () {
    var barangsData = document.getElementById('barangs-data');
    if (!barangsData) return;

    var barangs = JSON.parse(barangsData.textContent);
    var container = document.getElementById('items-container');
    var addBtn = document.getElementById('add-row');
    var template = document.getElementById('item-row-template');
    var tokoSelect = document.getElementById('kode_toko_select');
    var tokoInput = document.getElementById('kode_toko_inputed');

    var rowIndex = 0;

    function filterBarangs(query) {
        var q = query.toLowerCase().trim();
        return q === '' ? [] : barangs.filter(function (b) {
            return b.kode.toLowerCase().includes(q) || b.nama.toLowerCase().includes(q);
        });
    }

    function setupRow(row) {
        var searchInput = row.querySelector('.barang-search');
        var hiddenId = row.querySelector('.barang-id');
        var dropdown = row.querySelector('.barang-dropdown');
        var stokInfo = row.querySelector('.stok-info');

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

        row.querySelector('.remove-row').addEventListener('click', function () {
            if (container.querySelectorAll('.item-row').length > 1) {
                row.remove();
            }
        });
    }

    function addRow() {
        var clone = template.content.cloneNode(true);
        clone.querySelectorAll('[name*="__INDEX__"]').forEach(function (el) {
            el.name = el.name.replace(/__INDEX__/g, rowIndex);
        });
        var row = clone.querySelector('.item-row');
        container.appendChild(clone);
        setupRow(row);
        rowIndex++;
    }

    if (addBtn) {
        addBtn.addEventListener('click', addRow);
    }

    if (tokoSelect && tokoInput) {
        tokoSelect.addEventListener('change', function () {
            if (this.value) {
                tokoInput.value = this.value;
            }
        });
    }

    addRow();
});
