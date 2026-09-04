/* ================================================================
   CoreArsitek — interaksi & animasi kursor
   Pola animasi kursor mengikuti aldeftech.com: ambient glow yang
   menempel di posisi kursor, panah kursor kustom,
   tombol magnetic, dan tilt 3D pada kartu.
   ================================================================ */

(function () {
    'use strict';

    var finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---------------- Menu mobile ---------------- */

    function initNavToggle() {
        var toggle = document.getElementById('navToggle');
        var links = document.querySelector('.nav-links');
        if (!toggle || !links) return;

        toggle.addEventListener('click', function () {
            links.classList.toggle('open');
        });

        links.querySelectorAll('a').forEach(function (a) {
            a.addEventListener('click', function () {
                links.classList.remove('open');
            });
        });
    }

    /* ---------------- Navbar saat di-scroll ---------------- */

    function initNavbarScroll() {
        var navbar = document.querySelector('.navbar');
        if (!navbar) return;

        var ticking = false;
        var apply = function () {
            navbar.classList.toggle('is-scrolled', window.scrollY > 24);
            ticking = false;
        };

        apply();
        window.addEventListener('scroll', function () {
            if (!ticking) {
                ticking = true;
                requestAnimationFrame(apply);
            }
        }, { passive: true });
    }

    /* ---------------- Tombol kembali ke atas ---------------- */

    function initBackToTop() {
        var btn = document.getElementById('backToTop');
        if (!btn) return;

        var ticking = false;
        var apply = function () {
            btn.classList.toggle('is-visible', window.scrollY > 600);
            ticking = false;
        };

        apply();
        window.addEventListener('scroll', function () {
            if (!ticking) {
                ticking = true;
                requestAnimationFrame(apply);
            }
        }, { passive: true });

        btn.addEventListener('click', function (e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
        });
    }

    /* ---------------- Ambient glow yang mengikuti kursor ---------------- */

    function initCursorGlow() {
        if (!finePointer || reduceMotion) return;

        var glow = document.createElement('div');
        glow.className = 'cursor-glow';
        glow.setAttribute('aria-hidden', 'true');
        document.body.appendChild(glow);

        var frame = null;
        var shown = false;

        document.addEventListener('mousemove', function (e) {
            if (!frame) {
                frame = requestAnimationFrame(function () {
                    glow.style.left = e.clientX + 'px';
                    glow.style.top = e.clientY + 'px';
                    frame = null;
                });
            }
            if (!shown) {
                shown = true;
                glow.style.opacity = '1';
            }
        }, { passive: true });

        document.addEventListener('mouseleave', function () {
            shown = false;
            glow.style.opacity = '0';
        });
    }

    /* ---------------- Panah pengikut kursor ---------------- */

    var HOT_SELECTOR = 'a, button, .icon-btn, .lang-btn, .service-card, .service-pill, .gallery-item, .mosaic-item, .feature-item, .contact-item, .testimonial-card, .process-step, .about-figure, .slider-dot, input, textarea, select';

    function initCursorFollower() {
        if (!finePointer || reduceMotion) return;

        var arrow = document.createElement('div');
        arrow.className = 'cursor-arrow';
        arrow.setAttribute('aria-hidden', 'true');
        arrow.innerHTML = '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">'
            + '<path class="arrow-body" d="M4.5 1.8 L4.5 19.4 L9.1 15.1 L11.9 21.7 L14.9 20.4 L12.2 14 L18 14 Z"/>'
            + '</svg>';

        document.body.appendChild(arrow);
        document.body.classList.add('has-custom-cursor');

        var mouseX = window.innerWidth / 2;
        var mouseY = window.innerHeight / 2;
        var visible = false;

        // Panah dijangkarkan pada ujungnya, bukan pada titik tengah elemen.
        var ARROW_TIP_X = 4.9;
        var ARROW_TIP_Y = 2.0;

        var place = function (x, y) {
            arrow.style.transform = 'translate(' + (x - ARROW_TIP_X) + 'px, ' + (y - ARROW_TIP_Y) + 'px)';
        };

        document.addEventListener('mousemove', function (e) {
            mouseX = e.clientX;
            mouseY = e.clientY;

            if (!visible) {
                visible = true;
                arrow.style.opacity = '1';
            }

            place(mouseX, mouseY);
        }, { passive: true });

        document.addEventListener('mouseleave', function () {
            visible = false;
            arrow.style.opacity = '0';
        });

        document.addEventListener('mousedown', function () {
            arrow.classList.add('is-down');
        });

        document.addEventListener('mouseup', function () {
            arrow.classList.remove('is-down');
        });

        // Panah berbalik warna saat kursor berada di atas elemen interaktif
        document.addEventListener('mouseover', function (e) {
            var hot = e.target.closest ? e.target.closest(HOT_SELECTOR) : null;
            arrow.classList.toggle('is-hot', !!hot);
        }, { passive: true });

        place(mouseX, mouseY);
    }

    /* ---------------- Tombol magnetic ---------------- */

    function initMagnetic() {
        if (!finePointer || reduceMotion) return;

        document.querySelectorAll('.magnetic').forEach(function (el) {
            var strength = parseFloat(el.dataset.magnetic) || 0.16;

            el.addEventListener('mousemove', function (e) {
                var r = el.getBoundingClientRect();
                var dx = e.clientX - r.left - r.width / 2;
                var dy = e.clientY - r.top - r.height / 2;
                el.style.transition = 'transform 120ms linear';
                el.style.transform = 'translate(' + dx * strength + 'px, ' + dy * strength + 'px)';
            });

            el.addEventListener('mouseleave', function () {
                el.style.transition = 'transform 700ms cubic-bezier(0.22, 1, 0.36, 1)';
                el.style.transform = 'translate(0, 0)';
            });
        });
    }

    /* ---------------- Tilt 3D ---------------- */

    function initTilt() {
        if (!finePointer || reduceMotion) return;

        document.querySelectorAll('[data-tilt]').forEach(function (el) {
            var max = parseFloat(el.dataset.tilt) || 4;
            el.style.transformStyle = 'preserve-3d';

            el.addEventListener('mousemove', function (e) {
                var r = el.getBoundingClientRect();
                var px = (e.clientX - r.left) / r.width - 0.5;
                var py = (e.clientY - r.top) / r.height - 0.5;
                el.style.transition = 'transform 220ms cubic-bezier(0.33, 1, 0.68, 1)';
                el.style.transform = 'perspective(1200px) rotateY(' + px * max + 'deg) rotateX(' + (-py * max) + 'deg)';
            });

            el.addEventListener('mouseleave', function () {
                el.style.transition = 'transform 900ms cubic-bezier(0.22, 1, 0.36, 1)';
                el.style.transform = 'perspective(1200px) rotateY(0deg) rotateX(0deg)';
            });
        });
    }

    /* ---------------- Reveal saat scroll ---------------- */

    function initReveal() {
        var items = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale');
        if (!items.length) return;

        if (reduceMotion || !('IntersectionObserver' in window)) {
            items.forEach(function (el) { el.classList.add('is-in'); });
            return;
        }

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                var el = entry.target;
                var delay = parseInt(el.dataset.revealDelay || '0', 10);
                setTimeout(function () { el.classList.add('is-in'); }, delay);
                observer.unobserve(el);
            });
        }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

        items.forEach(function (el) { observer.observe(el); });
    }

    /* ---------------- Beri jeda berurutan pada grid ---------------- */

    function initStaggerGroups() {
        document.querySelectorAll('[data-reveal-group]').forEach(function (group) {
            var step = parseInt(group.dataset.revealGroup, 10) || 60;
            var children = group.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale');
            children.forEach(function (el, i) {
                if (!el.dataset.revealDelay) el.dataset.revealDelay = String(i * step);
            });
        });
    }

    /* ---------------- Angka statistik menghitung naik ---------------- */

    function initCounters() {
        var nodes = document.querySelectorAll('[data-counter]');
        if (!nodes.length) return;

        if (reduceMotion || !('IntersectionObserver' in window)) return;

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                var el = entry.target;
                observer.unobserve(el);

                var target = parseFloat(el.dataset.counter);
                if (isNaN(target)) return;
                var suffix = el.dataset.counterSuffix || '';
                var duration = 1400;
                var start = performance.now();

                var step = function (now) {
                    var p = Math.min((now - start) / duration, 1);
                    var eased = 1 - Math.pow(1 - p, 4);
                    el.textContent = String(Math.round(target * eased)) + suffix;
                    if (p < 1) requestAnimationFrame(step);
                };
                requestAnimationFrame(step);
            });
        }, { threshold: 0.4 });

        nodes.forEach(function (el) { observer.observe(el); });
    }

    /* ---------------- Slider banner hero ---------------- */

    function initHeroSlider() {
        document.querySelectorAll('[data-slider]').forEach(function (root) {
            var slides = Array.prototype.slice.call(root.querySelectorAll('[data-slide]'));
            if (slides.length < 2) return;

            var dots = Array.prototype.slice.call(root.querySelectorAll('[data-slider-dot]'));
            var prev = root.querySelector('[data-slider-prev]');
            var next = root.querySelector('[data-slider-next]');
            var interval = parseInt(root.dataset.interval, 10) || 6500;

            var current = 0;
            var timer = null;

            var show = function (index) {
                current = (index + slides.length) % slides.length;

                slides.forEach(function (slide, i) {
                    slide.classList.toggle('is-active', i === current);
                });

                dots.forEach(function (dot, i) {
                    dot.classList.toggle('is-active', i === current);
                });
            };

            var start = function () {
                if (reduceMotion) return;
                stop();
                timer = setInterval(function () { show(current + 1); }, interval);
            };

            var stop = function () {
                if (timer) {
                    clearInterval(timer);
                    timer = null;
                }
            };

            // Interaksi manual menunda putaran otomatis, tidak mematikannya.
            var goTo = function (index) {
                show(index);
                start();
            };

            if (prev) prev.addEventListener('click', function () { goTo(current - 1); });
            if (next) next.addEventListener('click', function () { goTo(current + 1); });

            dots.forEach(function (dot, i) {
                dot.addEventListener('click', function () { goTo(i); });
            });

            root.addEventListener('mouseenter', stop);
            root.addEventListener('mouseleave', start);

            // Hentikan saat tab tidak terlihat supaya tidak berputar sia-sia.
            document.addEventListener('visibilitychange', function () {
                if (document.hidden) {
                    stop();
                } else {
                    start();
                }
            });

            // Geser dengan sentuhan di perangkat mobile.
            var touchX = null;
            root.addEventListener('touchstart', function (e) {
                touchX = e.changedTouches[0].clientX;
            }, { passive: true });

            root.addEventListener('touchend', function (e) {
                if (touchX === null) return;
                var delta = e.changedTouches[0].clientX - touchX;
                if (Math.abs(delta) > 50) goTo(current + (delta < 0 ? 1 : -1));
                touchX = null;
            }, { passive: true });

            // Panah kiri/kanan saat slider sedang difokuskan.
            root.addEventListener('keydown', function (e) {
                if (e.key === 'ArrowLeft') goTo(current - 1);
                if (e.key === 'ArrowRight') goTo(current + 1);
            });

            show(0);
            start();
        });
    }

    /* ---------------- Flipbook foto karya ----------------
       Peningkatan bertahap: markup aslinya tetap daftar foto biasa, buku
       hanya dirakit oleh JavaScript. Tanpa JS halaman tetap utuh terbaca.

       Dua mode:
       - dua lembar  : layar lebar dan ponsel dalam posisi mendatar
       - satu lembar : ponsel/tablet dalam posisi tegak, agar fotonya
                       tidak menyusut jadi terlalu kecil                     */

    var BUKU_DUA_HALAMAN = '(min-width: 900px), (orientation: landscape) and (min-width: 560px)';

    function initFlipbook() {
        var root = document.querySelector('[data-flipbook]');
        if (!root) return;

        var daftar = root.querySelector('[data-flip-list]');
        var foto = Array.prototype.slice.call(root.querySelectorAll('[data-flip-photo]'));
        if (!daftar || foto.length < 2) return;

        var tplSampul = root.querySelector('[data-flip-cover]');
        var tplPenutup = root.querySelector('[data-flip-end]');
        var lebar = window.matchMedia(BUKU_DUA_HALAMAN);

        var buku = null;
        var lembar = [];
        var posisi = 0;
        var ganda = null;

        function klonTemplate(tpl) {
            return tpl ? tpl.content.cloneNode(true) : null;
        }

        /**
         * Susunan halaman buku. Halaman penutup berlogo harus mendarat di
         * sisi BELAKANG lembar terakhir supaya tampil di sebelah kiri saat
         * buku ditutup — dan sisi belakang selalu berindeks ganjil. Karena
         * itu, bila jumlah fotonya genap disisipkan satu halaman kosong
         * lebih dulu agar indeks penutupnya jatuh ganjil.
         */
        function susunHalaman() {
            var h = [];

            if (!ganda) h.push({ jenis: 'sampul' });

            foto.forEach(function (_, i) { h.push({ jenis: 'foto', indeks: i }); });

            if (ganda && h.length % 2 === 0) h.push({ jenis: 'kosong' });

            h.push({ jenis: 'penutup' });

            return h;
        }

        function isiMuka(muka, halaman) {
            if (!halaman) {
                muka.classList.add('leaf-kosong');
                return;
            }

            if (halaman.jenis === 'foto') {
                var sumber = foto[halaman.indeks];
                var img = sumber.querySelector('img');
                if (img) muka.appendChild(img);

                var teks = sumber.querySelector('figcaption');
                if (teks) muka.appendChild(teks);

                muka.insertAdjacentHTML('beforeend',
                    '<span class="leaf-no">' + (halaman.indeks + 1) + '</span>');
                return;
            }

            if (halaman.jenis === 'sampul') {
                muka.classList.add('leaf-plate', 'leaf-cover');
                var isiSampul = klonTemplate(tplSampul);
                if (isiSampul) muka.appendChild(isiSampul);
                return;
            }

            if (halaman.jenis === 'penutup') {
                muka.classList.add('leaf-plate', 'leaf-end');
                var isiPenutup = klonTemplate(tplPenutup);
                if (isiPenutup) muka.appendChild(isiPenutup);
                return;
            }

            muka.classList.add('leaf-kosong');
        }

        function rakit() {
            ganda = lebar.matches;

            var halaman = susunHalaman();
            var jumlah = ganda ? Math.ceil(halaman.length / 2) : halaman.length;

            buku = document.createElement('div');
            buku.className = 'book' + (ganda ? '' : ' is-single');

            var spread = document.createElement('div');
            spread.className = 'book-spread';

            if (ganda) {
                // Sampul jadi panel kiri yang diam, terlihat saat buku tertutup.
                var kiri = document.createElement('div');
                kiri.className = 'book-side book-left leaf-plate leaf-cover';
                var isiSampul = klonTemplate(tplSampul);
                if (isiSampul) kiri.appendChild(isiSampul);
                spread.appendChild(kiri);
            }

            // Panel kanan dibiarkan polos sebagai dasar penutup buku.
            var kanan = document.createElement('div');
            kanan.className = 'book-side book-right';
            spread.appendChild(kanan);

            lembar = [];

            for (var i = 0; i < jumlah; i++) {
                var lem = document.createElement('div');
                lem.className = 'leaf';

                var depan = document.createElement('div');
                depan.className = 'leaf-face leaf-front';
                isiMuka(depan, ganda ? halaman[i * 2] : halaman[i]);
                lem.appendChild(depan);

                var belakang = document.createElement('div');
                belakang.className = 'leaf-face leaf-back';
                isiMuka(belakang, ganda ? halaman[i * 2 + 1] : null);
                lem.appendChild(belakang);

                spread.appendChild(lem);
                lembar.push(lem);
            }

            buku.appendChild(spread);

            var bar = document.createElement('div');
            bar.className = 'book-bar';
            bar.innerHTML =
                '<button type="button" class="book-btn" data-book-prev aria-label="Halaman sebelumnya">'
                + '<i class="fa-solid fa-chevron-left"></i></button>'
                + '<span class="book-count" data-book-count></span>'
                + '<button type="button" class="book-btn" data-book-next aria-label="Halaman berikutnya">'
                + '<i class="fa-solid fa-chevron-right"></i></button>';
            buku.appendChild(bar);

            daftar.parentNode.insertBefore(buku, daftar);
            daftar.hidden = true;

            buku.querySelector('[data-book-prev]').addEventListener('click', function () { ke(posisi - 1); });
            buku.querySelector('[data-book-next]').addEventListener('click', function () { ke(posisi + 1); });

            spread.addEventListener('click', function (e) {
                if (e.target.closest('.book-btn')) return;
                var r = spread.getBoundingClientRect();
                // Mode satu halaman: seluruh bidang membuka ke depan.
                ke(!ganda || e.clientX - r.left > r.width / 2 ? posisi + 1 : posisi - 1);
            });

            buku.tabIndex = 0;
            buku.addEventListener('keydown', function (e) {
                if (e.key === 'ArrowRight') { e.preventDefault(); ke(posisi + 1); }
                if (e.key === 'ArrowLeft') { e.preventDefault(); ke(posisi - 1); }
            });

            var sentuhX = null;
            spread.addEventListener('touchstart', function (e) {
                sentuhX = e.changedTouches[0].clientX;
            }, { passive: true });

            spread.addEventListener('touchend', function (e) {
                if (sentuhX === null) return;
                var beda = e.changedTouches[0].clientX - sentuhX;
                if (Math.abs(beda) > 45) ke(posisi + (beda < 0 ? 1 : -1));
                sentuhX = null;
            }, { passive: true });

            gambar();
        }

        function ke(target) {
            posisi = Math.max(0, Math.min(lembar.length, target));
            gambar();
        }

        function gambar() {
            lembar.forEach(function (lem, i) {
                var terbuka = i < posisi;
                lem.classList.toggle('is-flipped', terbuka);
                // Yang sudah dibuka menumpuk ke kiri, yang belum ke kanan.
                lem.style.zIndex = terbuka ? i : lembar.length - i;
            });

            buku.classList.toggle('is-awal', posisi === 0);
            buku.classList.toggle('is-akhir', posisi === lembar.length);

            buku.querySelector('[data-book-prev]').disabled = posisi === 0;
            buku.querySelector('[data-book-next]').disabled = posisi === lembar.length;
            buku.querySelector('[data-book-count]').textContent =
                (ganda ? 'Lembar ' : 'Halaman ')
                + Math.min(posisi + 1, lembar.length) + ' dari ' + lembar.length;
        }

        /** Kembalikan gambar ke daftar aslinya sebelum bukunya dibuang. */
        function bongkar() {
            if (!buku) return;

            buku.querySelectorAll('.leaf-face').forEach(function (muka) {
                var img = muka.querySelector('img');
                var no = muka.querySelector('.leaf-no');
                if (!img || !no) return;

                var sumber = foto[parseInt(no.textContent, 10) - 1];
                if (!sumber) return;

                sumber.insertBefore(img, sumber.firstChild);

                var teks = muka.querySelector('figcaption');
                if (teks) sumber.appendChild(teks);
            });

            buku.remove();
            buku = null;
            lembar = [];
            posisi = 0;
            daftar.hidden = false;
        }

        // Berganti orientasi mengubah jumlah halaman, jadi bukunya dirakit ulang.
        function sesuaikan() {
            if (buku && ganda === lebar.matches) return;
            bongkar();
            rakit();
        }

        rakit();

        if (lebar.addEventListener) {
            lebar.addEventListener('change', sesuaikan);
        }
    }

    /* ---------------- Bootstrap ---------------- */

    function init() {
        initNavToggle();
        initNavbarScroll();
        initHeroSlider();
        initFlipbook();
        initBackToTop();
        initStaggerGroups();
        initReveal();
        initCounters();
        initCursorGlow();
        initCursorFollower();
        initMagnetic();
        initTilt();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
