/* ================================================================
   CoreArsitek — penjaga unggahan di dashboard

   nginx menolak permintaan yang terlalu besar dengan halaman 413 mentah
   sebelum Laravel sempat menampilkan pesan apa pun. Pemeriksaan di sini
   mencegatnya lebih dulu supaya admin tahu berkas mana yang bermasalah.
   ================================================================ */

(function () {
    'use strict';

    var cfg = document.body ? document.body.dataset : {};
    var MAX_FILE = parseInt(cfg.uploadMaxKb || '15360', 10) * 1024;
    var MAX_TOTAL = parseInt(cfg.uploadTotalKb || '117760', 10) * 1024;
    var MAX_BATCH = parseInt(cfg.uploadMaxBatch || '8', 10);

    function ukuran(bytes) {
        return bytes >= 1048576
            ? (bytes / 1048576).toFixed(1).replace('.', ',') + ' MB'
            : Math.max(1, Math.round(bytes / 1024)) + ' KB';
    }

    function tampilkanPesan(form, pesan) {
        var kotak = form.querySelector('.upload-alert');

        if (! kotak) {
            kotak = document.createElement('div');
            kotak.className = 'alert alert-error upload-alert';
            form.insertBefore(kotak, form.firstChild);
        }

        kotak.innerHTML = pesan;
        kotak.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function bersihkanPesan(form) {
        var kotak = form.querySelector('.upload-alert');
        if (kotak) kotak.remove();
    }

    function periksa(form) {
        var inputs = form.querySelectorAll('input[type="file"]');
        var total = 0;
        var jumlah = 0;
        var galat = [];

        inputs.forEach(function (input) {
            var berkas = input.files ? Array.prototype.slice.call(input.files) : [];

            if (input.multiple && berkas.length > MAX_BATCH) {
                galat.push('Terlalu banyak foto sekaligus: <strong>' + berkas.length
                    + '</strong> berkas dipilih, maksimal <strong>' + MAX_BATCH + '</strong>.');
            }

            berkas.forEach(function (f) {
                total += f.size;
                jumlah += 1;

                if (f.size > MAX_FILE) {
                    galat.push('<strong>' + f.name + '</strong> berukuran ' + ukuran(f.size)
                        + ', melebihi batas ' + ukuran(MAX_FILE) + ' per berkas.');
                }
            });
        });

        if (jumlah > 0 && total > MAX_TOTAL) {
            galat.push('Total unggahan ' + ukuran(total) + ' melebihi batas '
                + ukuran(MAX_TOTAL) + ' sekali kirim. Unggah beberapa foto dulu, simpan, lalu ulangi untuk sisanya.');
        }

        return galat;
    }

    function pasang(form) {
        if (! form.querySelector('input[type="file"]')) return;

        form.addEventListener('submit', function (e) {
            var galat = periksa(form);

            if (galat.length) {
                e.preventDefault();
                tampilkanPesan(form, '<ul><li>' + galat.join('</li><li>') + '</li></ul>');
            } else {
                bersihkanPesan(form);
            }
        });

        // Beri tahu begitu berkas dipilih, tidak perlu menunggu tombol simpan.
        form.querySelectorAll('input[type="file"]').forEach(function (input) {
            input.addEventListener('change', function () {
                var galat = periksa(form);
                galat.length
                    ? tampilkanPesan(form, '<ul><li>' + galat.join('</li><li>') + '</li></ul>')
                    : bersihkanPesan(form);
            });
        });
    }

    function init() {
        document.querySelectorAll('form').forEach(pasang);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
