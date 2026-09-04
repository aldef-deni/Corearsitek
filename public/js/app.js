/* ================================================================
   CoreArsitek — interaksi & animasi kursor
   Pola animasi kursor mengikuti aldeftech.com: ambient glow yang
   menempel di posisi kursor, ditambah panah + ring dengan easing,
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

    /* ---------------- Panah + ring pengikut kursor ---------------- */

    var HOT_SELECTOR = 'a, button, .icon-btn, .lang-btn, .service-card, .service-pill, .gallery-item, .mosaic-item, .feature-item, .contact-item, .testimonial-card, .process-step, .about-figure, .slider-dot, input, textarea, select';

    function initCursorFollower() {
        if (!finePointer || reduceMotion) return;

        var dot = document.createElement('div');
        dot.className = 'cursor-arrow';
        dot.setAttribute('aria-hidden', 'true');
        dot.innerHTML = '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">'
            + '<path class="arrow-body" d="M4.5 1.8 L4.5 19.4 L9.1 15.1 L11.9 21.7 L14.9 20.4 L12.2 14 L18 14 Z"/>'
            + '</svg>';

        var ring = document.createElement('div');
        ring.className = 'cursor-ring';
        ring.setAttribute('aria-hidden', 'true');

        document.body.appendChild(ring);
        document.body.appendChild(dot);
        document.body.classList.add('has-custom-cursor');

        var mouseX = window.innerWidth / 2;
        var mouseY = window.innerHeight / 2;
        var ringX = mouseX;
        var ringY = mouseY;
        var visible = false;
        var running = false;

        var place = function (el, x, y) {
            el.style.transform = 'translate(' + x + 'px, ' + y + 'px) translate(-50%, -50%)';
        };

        // Panah dijangkarkan pada ujungnya, bukan pada titik tengah elemen.
        var ARROW_TIP_X = 4.9;
        var ARROW_TIP_Y = 2.0;

        var placeArrow = function (x, y) {
            dot.style.transform = 'translate(' + (x - ARROW_TIP_X) + 'px, ' + (y - ARROW_TIP_Y) + 'px)';
        };

        // Ring menyusul kursor dengan easing. Loop berhenti begitu ring sudah
        // menyusul, jadi tidak ada requestAnimationFrame yang jalan terus-menerus.
        var follow = function () {
            var dx = mouseX - ringX;
            var dy = mouseY - ringY;
            ringX += dx * 0.16;
            ringY += dy * 0.16;
            place(ring, ringX, ringY);

            if (Math.abs(dx) < 0.2 && Math.abs(dy) < 0.2) {
                running = false;
                return;
            }
            requestAnimationFrame(follow);
        };

        document.addEventListener('mousemove', function (e) {
            mouseX = e.clientX;
            mouseY = e.clientY;

            if (!visible) {
                visible = true;
                ringX = mouseX;
                ringY = mouseY;
                place(ring, ringX, ringY);
                dot.style.opacity = '1';
                ring.style.opacity = '1';
            }

            placeArrow(mouseX, mouseY);

            if (!running) {
                running = true;
                requestAnimationFrame(follow);
            }
        }, { passive: true });

        document.addEventListener('mouseleave', function () {
            visible = false;
            dot.style.opacity = '0';
            ring.style.opacity = '0';
        });

        document.addEventListener('mousedown', function () {
            ring.classList.add('is-down');
            dot.classList.add('is-down');
        });

        document.addEventListener('mouseup', function () {
            ring.classList.remove('is-down');
            dot.classList.remove('is-down');
        });

        // Ring membesar saat kursor berada di atas elemen interaktif
        document.addEventListener('mouseover', function (e) {
            var hot = e.target.closest ? e.target.closest(HOT_SELECTOR) : null;
            ring.classList.toggle('is-hot', !!hot);
            dot.classList.toggle('is-hot', !!hot);
        }, { passive: true });

        placeArrow(mouseX, mouseY);
        place(ring, ringX, ringY);
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

    /* ---------------- Bootstrap ---------------- */

    function init() {
        initNavToggle();
        initNavbarScroll();
        initHeroSlider();
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
