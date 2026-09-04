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

    /**
     * Merakit satu buku dari sebuah wadah [data-flipbook]. Dipakai dua kali:
     * langsung di halaman detail portofolio, dan di dalam lapisan penuh layar
     * pada halaman Galeri. Mengembalikan kendali seperlunya, atau null kalau
     * isinya belum cukup untuk dijadikan buku.
     */
    function bangunFlipbook(root) {
        if (!root) return null;

        var daftar = root.querySelector('[data-flip-list]');
        var foto = Array.prototype.slice.call(root.querySelectorAll('[data-flip-photo]'));
        if (!daftar || foto.length < 2) return null;

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
         * Susunan halaman buku: hanya sampul (mode satu halaman) dan foto.
         * Penutup tidak ikut jadi lembar melainkan lapisan yang menutup
         * seluruh bidang buku, supaya tidak menyisakan halaman kosong di
         * sebelahnya saat buku sudah habis.
         */
        function susunHalaman() {
            var h = [];

            if (!ganda) h.push({ jenis: 'sampul' });

            foto.forEach(function (_, i) { h.push({ jenis: 'foto', indeks: i }); });

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
                if (img) {
                    // Tanpa ini, menyeret foto di desktop memicu drag bawaan
                    // browser dan rangkaian pointer event kita ikut dibatalkan.
                    img.draggable = false;
                    muka.appendChild(img);
                }

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

            var penutup = document.createElement('div');
            penutup.className = 'book-end-overlay';
            var isiPenutup = klonTemplate(tplPenutup);
            if (isiPenutup) penutup.appendChild(isiPenutup);
            spread.appendChild(penutup);

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

            buku.tabIndex = 0;
            buku.addEventListener('keydown', function (e) {
                if (e.key === 'ArrowRight') { e.preventDefault(); ke(posisi + 1); }
                if (e.key === 'ArrowLeft') { e.preventDefault(); ke(posisi - 1); }
            });

            /* ---- Menyeret halaman ----
               Memakai pointer event supaya tetikus, sentuhan, dan pena
               ditangani lewat satu jalur yang sama. Halaman mengikuti jari
               atau kursor selama diseret, lalu menutup sendiri ke posisi
               terdekat begitu dilepas. */
            var seret = null;
            var jarakTerakhir = 0;

            function lebarHalaman() {
                var r = spread.getBoundingClientRect();
                return ganda ? r.width / 2 : r.width;
            }

            function mulaiSeret(e) {
                if (e.button !== undefined && e.button !== 0) return;
                // Tombol navigasi dan tautan di dalam halaman tetap normal.
                if (e.target.closest('.book-btn') || e.target.closest('a')) return;

                jarakTerakhir = 0;
                seret = { x0: e.clientX, y0: e.clientY, lem: null, maju: true, jauh: 0, kemajuan: 0, id: e.pointerId };

                // Tanpa penangkapan pointer, begitu kursor keluar dari bidang
                // buku aliran pointermove-nya putus di tengah seretan.
                if (spread.setPointerCapture) {
                    try { spread.setPointerCapture(e.pointerId); } catch (err) { /* diabaikan */ }
                }
            }

            function gerakSeret(e) {
                if (!seret) return;

                var dx = e.clientX - seret.x0;
                var dy = e.clientY - seret.y0;
                seret.jauh = Math.abs(dx);

                if (!seret.lem) {
                    // Tunggu sampai gerakannya jelas mendatar, supaya menggulir
                    // halaman ke bawah tidak terbaca sebagai membalik lembar.
                    if (Math.abs(dx) < 8 || Math.abs(dx) < Math.abs(dy)) return;

                    seret.maju = dx < 0;
                    seret.lem = lembar[seret.maju ? posisi : posisi - 1] || null;

                    if (!seret.lem) { seret = null; return; }

                    seret.lem.classList.add('is-dragging');
                    // Lembar yang ditarik kembali harus terlihat lagi.
                    seret.lem.classList.remove('is-flipped');
                    seret.lem.style.zIndex = lembar.length + 1;
                }

                if (e.cancelable) e.preventDefault();

                seret.kemajuan = Math.min(Math.max(Math.abs(dx) / lebarHalaman(), 0), 1);
                var sudut = seret.maju ? -180 * seret.kemajuan : -180 * (1 - seret.kemajuan);
                seret.lem.style.transform = 'rotateY(' + sudut + 'deg)';
            }

            function selesaiSeret() {
                if (!seret) return;

                var s = seret;
                seret = null;
                jarakTerakhir = s.jauh;

                if (!s.lem) return;

                s.lem.classList.remove('is-dragging');
                s.lem.style.transform = '';
                s.lem.style.zIndex = '';

                // Lewat sepertiga jalan dianggap sebagai niat membalik.
                if (s.kemajuan > 0.34) {
                    posisi = Math.max(0, Math.min(lembar.length, posisi + (s.maju ? 1 : -1)));
                }

                gambar();
            }

            spread.addEventListener('pointerdown', mulaiSeret);
            spread.addEventListener('pointermove', gerakSeret);
            spread.addEventListener('pointerup', selesaiSeret);
            spread.addEventListener('pointercancel', selesaiSeret);
            window.addEventListener('pointerup', selesaiSeret);

            // Drag bawaan browser pada gambar akan membatalkan pointer event,
            // sehingga di desktop halaman tidak pernah bisa diseret.
            spread.addEventListener('dragstart', function (e) { e.preventDefault(); });

            // Klik hanya berlaku kalau memang bukan akhir dari seretan.
            spread.addEventListener('click', function (e) {
                if (e.target.closest('.book-btn') || e.target.closest('a')) return;
                if (jarakTerakhir > 8) { jarakTerakhir = 0; return; }

                var r = spread.getBoundingClientRect();
                // Mode satu halaman: seluruh bidang membuka ke depan.
                ke(!ganda || e.clientX - r.left > r.width / 2 ? posisi + 1 : posisi - 1);
            });

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
                lem.classList.toggle('is-top', i === posisi);
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

        /**
         * Buka buku pada foto ke-k. Dalam mode dua halaman satu lembar memuat
         * dua foto, jadi foto genap ada di muka depan lembar k/2 dan foto ganjil
         * di muka belakang lembar sebelumnya — keduanya terlihat pada posisi
         * yang sama, yaitu pembulatan ke atas dari k/2.
         */
        function keFoto(k) {
            ke(ganda ? Math.ceil(k / 2) : k + 1);
        }

        return {
            keFoto: keFoto,
            lepas: function () {
                if (lebar.removeEventListener) lebar.removeEventListener('change', sesuaikan);
                bongkar();
            }
        };
    }

    function initFlipbook() {
        bangunFlipbook(document.querySelector('[data-flipbook]'));
    }

    /* ---------------- Deretan kartu yang digeser di ponsel ----------------
       Di layar sempit, grid kartu diubah jadi satu baris yang digeser
       mendatar dengan scroll-snap, ditambah titik penanda posisi. Di layar
       lebar kembali jadi grid biasa. */

    var GESER_PONSEL = '(max-width: 768px)';

    function initCardsSwipe() {
        var wadah = Array.prototype.slice.call(document.querySelectorAll('[data-swipe]'));
        if (!wadah.length) return;

        var ponsel = window.matchMedia(GESER_PONSEL);

        wadah.forEach(function (rak) {
            var titik = null;
            var kartu = Array.prototype.slice.call(rak.children);
            if (kartu.length < 2) return;

            function langkah() {
                var pertama = rak.firstElementChild;
                if (!pertama) return rak.clientWidth || 1;
                var jarak = parseFloat(getComputedStyle(rak).columnGap || '0') || 0;
                return pertama.getBoundingClientRect().width + jarak;
            }

            function segarkan() {
                if (!titik) return;
                var aktif = Math.round(rak.scrollLeft / langkah());
                Array.prototype.forEach.call(titik.children, function (t, i) {
                    t.classList.toggle('is-active', i === aktif);
                    t.setAttribute('aria-current', i === aktif ? 'true' : 'false');
                });
            }

            var menunggu = false;
            function padaGeser() {
                if (menunggu) return;
                menunggu = true;
                requestAnimationFrame(function () { segarkan(); menunggu = false; });
            }

            var pakaiTitik = !rak.hasAttribute('data-swipe-nodots');

            function pasang() {
                if (rak.classList.contains('cards-swipe')) return;

                rak.classList.add('cards-swipe');

                if (!pakaiTitik) return;

                titik = document.createElement('div');
                titik.className = 'swipe-dots';
                titik.setAttribute('role', 'tablist');
                titik.setAttribute('aria-label', 'Posisi kartu');

                kartu.forEach(function (_, i) {
                    var t = document.createElement('button');
                    t.type = 'button';
                    t.className = 'swipe-dot';
                    t.setAttribute('aria-label', 'Kartu ' + (i + 1));
                    t.addEventListener('click', function () {
                        rak.scrollTo({ left: i * langkah(), behavior: reduceMotion ? 'auto' : 'smooth' });
                    });
                    titik.appendChild(t);
                });

                rak.parentNode.insertBefore(titik, rak.nextSibling);
                rak.addEventListener('scroll', padaGeser, { passive: true });
                segarkan();
            }

            function lepas() {
                if (!rak.classList.contains('cards-swipe')) return;
                rak.classList.remove('cards-swipe');
                rak.removeEventListener('scroll', padaGeser);
                if (titik) { titik.remove(); titik = null; }
                rak.scrollLeft = 0;
            }

            function sesuaikan() { ponsel.matches ? pasang() : lepas(); }

            sesuaikan();

            if (ponsel.addEventListener) {
                ponsel.addEventListener('change', sesuaikan);
            }

            window.addEventListener('resize', function () {
                if (titik) segarkan();
            }, { passive: true });
        });
    }

    /* ---------------- Modal foto ----------------
       Satu modal dipakai bersama seluruh foto dalam satu kelompok, jadi
       bisa berpindah maju-mundur tanpa menutupnya lebih dulu. */

    /* ---------------- Galeri: pilih cara membuka foto ----------------
       Dua tampilan disediakan berdampingan supaya bisa dibandingkan
       langsung: buku yang dibalik halaman demi halaman, atau modal foto
       tunggal. Pilihannya diingat di peramban masing-masing pengunjung. */

    var GALERI_TAMPILAN = 'corearsitek:galeri-tampilan';

    function modeGaleri() {
        try {
            return localStorage.getItem(GALERI_TAMPILAN) === 'modal' ? 'modal' : 'buku';
        } catch (e) {
            // Mode penyamaran atau penyimpanan diblokir: pakai bawaan saja.
            return 'buku';
        }
    }

    function simpanModeGaleri(nilai) {
        try { localStorage.setItem(GALERI_TAMPILAN, nilai); } catch (e) { /* diabaikan */ }
    }

    function initGalleryViewSwitch() {
        var saklar = document.querySelector('[data-gallery-view]');
        if (!saklar) return;

        var tombol = Array.prototype.slice.call(saklar.querySelectorAll('[data-view]'));

        function tandai() {
            var aktif = modeGaleri();
            tombol.forEach(function (t) {
                var ini = t.dataset.view === aktif;
                t.classList.toggle('is-active', ini);
                t.setAttribute('aria-pressed', ini ? 'true' : 'false');
            });
        }

        tombol.forEach(function (t) {
            t.addEventListener('click', function () {
                simpanModeGaleri(t.dataset.view);
                tandai();
            });
        });

        tandai();
    }

    /* ---------------- Galeri: buku penuh layar ----------------
       Foto galeri dipinjamkan ke sebuah [data-flipbook] di dalam lapisan
       penuh layar, lalu dirakit memakai mesin buku yang sama dengan halaman
       detail portofolio. Lapisannya dibongkar habis saat ditutup supaya
       tidak ada sisa lembar yang menumpuk. */

    function initGalleryBook() {
        var kelompok = document.querySelector('[data-gallery-book]');
        if (!kelompok) return;

        var tombolFoto = Array.prototype.slice.call(kelompok.querySelectorAll('[data-lightbox-item]'));
        if (tombolFoto.length < 2) return;

        var logo = kelompok.dataset.bookLogo || '';
        var nama = kelompok.dataset.bookName || 'CoreArsitek';

        var lapisan = null;
        var buku = null;
        var pemicu = null;

        function halamanFoto() {
            return tombolFoto.map(function (t) {
                var ket = t.dataset.desc
                    ? '<figcaption>' + t.dataset.title + ' &middot; ' + t.dataset.desc + '</figcaption>'
                    : (t.dataset.title ? '<figcaption>' + t.dataset.title + '</figcaption>' : '');

                return '<figure data-flip-photo>'
                    + '<img src="' + t.dataset.src + '" alt="' + (t.dataset.title || '') + '">'
                    + ket
                    + '</figure>';
            }).join('');
        }

        function rakit() {
            lapisan = document.createElement('div');
            lapisan.className = 'gbook';
            lapisan.setAttribute('role', 'dialog');
            lapisan.setAttribute('aria-modal', 'true');
            lapisan.setAttribute('aria-label', 'Galeri dalam bentuk buku');
            lapisan.hidden = true;

            lapisan.innerHTML =
                '<div class="gbook-backdrop" data-gb-close></div>'
                + '<button type="button" class="gbook-close" data-gb-close aria-label="Tutup buku">'
                + '<i class="fa-solid fa-xmark"></i></button>'
                + '<div class="gbook-shell">'
                + '<div data-flipbook>'
                + '<template data-flip-cover>'
                + (logo ? '<img src="' + logo + '" alt="' + nama + '" class="book-logo">' : '')
                + '<h2 class="book-cover-title">GALERI</h2>'
                + '<p class="book-cover-note">' + tombolFoto.length + ' foto &middot; geser atau ketuk untuk membalik halaman</p>'
                + '</template>'
                + '<template data-flip-end>'
                + (logo ? '<img src="' + logo + '" alt="' + nama + '" class="book-logo book-logo-pulse">' : '')
                + '</template>'
                + '<div data-flip-list>' + halamanFoto() + '</div>'
                + '</div>'
                + '</div>';

            document.body.appendChild(lapisan);

            lapisan.querySelectorAll('[data-gb-close]').forEach(function (el) {
                el.addEventListener('click', tutup);
            });
        }

        function buka(i, dariTombol) {
            if (!lapisan) rakit();

            pemicu = dariTombol || null;
            lapisan.hidden = false;
            document.body.classList.add('is-locked');

            // Bukunya dirakit ulang tiap kali dibuka: lebar layar bisa berubah
            // di antara dua kali buka, dan jumlah lembarnya ikut berubah.
            if (buku) buku.lepas();
            buku = bangunFlipbook(lapisan.querySelector('[data-flipbook]'));

            requestAnimationFrame(function () {
                lapisan.classList.add('is-open');
                if (buku) buku.keFoto(i);
                var tutupBtn = lapisan.querySelector('.gbook-close');
                if (tutupBtn) tutupBtn.focus();
            });
        }

        function tutup() {
            if (!lapisan || lapisan.hidden) return;

            lapisan.classList.remove('is-open');
            document.body.classList.remove('is-locked');

            var sudah = false;
            var selesai = function () {
                if (sudah) return;
                sudah = true;
                lapisan.hidden = true;
                if (pemicu) pemicu.focus();
            };

            if (reduceMotion) {
                selesai();
                return;
            }

            // Sama seperti modal: transitionend saja tidak cukup kalau
            // transisinya dimatikan, jadi selalu ada penghitung pengaman.
            var pengaman = setTimeout(selesai, 400);
            lapisan.addEventListener('transitionend', function () {
                clearTimeout(pengaman);
                selesai();
            }, { once: true });
        }

        tombolFoto.forEach(function (t, i) {
            t.addEventListener('click', function () {
                if (modeGaleri() !== 'buku') return;
                buka(i, t);
            });
        });

        document.addEventListener('keydown', function (e) {
            if (!lapisan || lapisan.hidden) return;
            if (e.key === 'Escape') { e.preventDefault(); tutup(); }
        });
    }

    function initLightbox() {
        var kelompok = document.querySelector('[data-lightbox-group]');
        if (!kelompok) return;

        var tombolFoto = Array.prototype.slice.call(kelompok.querySelectorAll('[data-lightbox-item]'));
        if (!tombolFoto.length) return;

        var modal = null;
        var gambar = null;
        var judul = null;
        var ket = null;
        var hitung = null;
        var indeks = 0;
        var pemicu = null;

        function rakit() {
            modal = document.createElement('div');
            modal.className = 'lightbox';
            modal.setAttribute('role', 'dialog');
            modal.setAttribute('aria-modal', 'true');
            modal.setAttribute('aria-label', 'Pratinjau foto');
            modal.hidden = true;

            modal.innerHTML =
                '<div class="lb-backdrop" data-lb-close></div>'
                + '<button type="button" class="lb-btn lb-close" data-lb-close aria-label="Tutup">'
                + '<i class="fa-solid fa-xmark"></i></button>'
                + '<button type="button" class="lb-btn lb-prev" data-lb-prev aria-label="Foto sebelumnya">'
                + '<i class="fa-solid fa-chevron-left"></i></button>'
                + '<button type="button" class="lb-btn lb-next" data-lb-next aria-label="Foto berikutnya">'
                + '<i class="fa-solid fa-chevron-right"></i></button>'
                + '<figure class="lb-figure">'
                + '<img alt="">'
                + '<figcaption><strong></strong><span></span></figcaption>'
                + '</figure>'
                + '<div class="lb-count"></div>';

            document.body.appendChild(modal);

            gambar = modal.querySelector('img');
            judul = modal.querySelector('figcaption strong');
            ket = modal.querySelector('figcaption span');
            hitung = modal.querySelector('.lb-count');

            modal.querySelectorAll('[data-lb-close]').forEach(function (el) {
                el.addEventListener('click', tutup);
            });
            modal.querySelector('[data-lb-prev]').addEventListener('click', function () { ke(indeks - 1); });
            modal.querySelector('[data-lb-next]').addEventListener('click', function () { ke(indeks + 1); });

            // Geser mendatar untuk berpindah foto.
            var x0 = null;
            modal.addEventListener('touchstart', function (e) { x0 = e.changedTouches[0].clientX; }, { passive: true });
            modal.addEventListener('touchend', function (e) {
                if (x0 === null) return;
                var beda = e.changedTouches[0].clientX - x0;
                if (Math.abs(beda) > 45) ke(indeks + (beda < 0 ? 1 : -1));
                x0 = null;
            }, { passive: true });
        }

        function tampilkan() {
            var t = tombolFoto[indeks];
            gambar.src = t.dataset.src;
            gambar.alt = t.dataset.title || '';
            judul.textContent = t.dataset.title || '';
            ket.textContent = t.dataset.desc || '';
            ket.hidden = !t.dataset.desc;
            hitung.textContent = (indeks + 1) + ' / ' + tombolFoto.length;

            // Satu foto saja tidak perlu tombol maju-mundur.
            var banyak = tombolFoto.length > 1;
            modal.querySelector('[data-lb-prev]').hidden = !banyak;
            modal.querySelector('[data-lb-next]').hidden = !banyak;
            hitung.hidden = !banyak;
        }

        function ke(target) {
            indeks = (target + tombolFoto.length) % tombolFoto.length;
            tampilkan();
        }

        function buka(i, dariTombol) {
            if (!modal) rakit();

            pemicu = dariTombol || null;
            indeks = i;
            tampilkan();

            modal.hidden = false;
            // Halaman di belakang modal tidak ikut tergulir.
            document.body.classList.add('is-locked');
            requestAnimationFrame(function () { modal.classList.add('is-open'); });
            modal.querySelector('.lb-close').focus();
        }

        function tutup() {
            if (!modal || modal.hidden) return;

            modal.classList.remove('is-open');
            document.body.classList.remove('is-locked');

            var sudah = false;
            var selesai = function () {
                if (sudah) return;
                sudah = true;
                modal.hidden = true;
                // Fokus dikembalikan ke foto yang tadi diketuk.
                if (pemicu) pemicu.focus();
            };

            if (reduceMotion) {
                selesai();
                return;
            }

            // transitionend saja tidak cukup: bila transisinya tidak pernah
            // berjalan — misalnya dimatikan lewat CSS — modal akan tetap
            // menutupi halaman. Penghitung waktu memastikan ia selalu tertutup.
            var pengaman = setTimeout(selesai, 400);
            modal.addEventListener('transitionend', function () {
                clearTimeout(pengaman);
                selesai();
            }, { once: true });
        }

        // Di halaman Galeri ada dua tampilan; modal mengalah kalau yang
        // sedang dipilih adalah buku.
        var bisaBuku = kelompok.hasAttribute('data-gallery-book');

        tombolFoto.forEach(function (t, i) {
            t.addEventListener('click', function () {
                if (bisaBuku && modeGaleri() === 'buku') return;
                buka(i, t);
            });
        });

        document.addEventListener('keydown', function (e) {
            if (!modal || modal.hidden) return;
            if (e.key === 'Escape') { e.preventDefault(); tutup(); }
            if (e.key === 'ArrowRight') { e.preventDefault(); ke(indeks + 1); }
            if (e.key === 'ArrowLeft') { e.preventDefault(); ke(indeks - 1); }
        });
    }

    /* ---------------- Bootstrap ---------------- */

    function init() {
        initNavToggle();
        initNavbarScroll();
        initHeroSlider();
        initFlipbook();
        initCardsSwipe();
        initGalleryViewSwitch();
        initGalleryBook();
        initLightbox();
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
