// Toggle menu mobile
document.getElementById('navToggle')?.addEventListener('click', function () {
    document.querySelector('.nav-links')?.classList.toggle('open');
});

// Tombol bagikan
function sharePage(event) {
    event.preventDefault();
    if (navigator.share) {
        navigator.share({ title: document.title, url: window.location.href }).catch(() => {});
    } else {
        navigator.clipboard?.writeText(window.location.href).then(() => {
            alert('Link disalin ke clipboard.');
        });
    }
}