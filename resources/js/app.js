(function () {
    var toggle = document.getElementById('dark-toggle');
    var sun = document.getElementById('sun-icon');
    var moon = document.getElementById('moon-icon');
    if (toggle) {
        function setDark(dark) {
            if (dark) {
                document.documentElement.classList.add('dark');
                sun && sun.classList.remove('hidden');
                moon && moon.classList.add('hidden');
            } else {
                document.documentElement.classList.remove('dark');
                sun && sun.classList.add('hidden');
                moon && moon.classList.remove('hidden');
            }
            localStorage.setItem('dark-mode', dark ? 'true' : 'false');
        }
        setDark(document.documentElement.classList.contains('dark'));
        toggle.addEventListener('click', function () {
            setDark(!document.documentElement.classList.contains('dark'));
        });
    }
})();

document.addEventListener('change', function (e) {
    if (e.target.matches('[data-auto-submit]')) {
        e.target.form.submit();
    }
});

document.addEventListener('submit', function (e) {
    var msg = e.target.getAttribute('data-confirm');
    if (msg && !confirm(msg)) {
        e.preventDefault();
    }
});
