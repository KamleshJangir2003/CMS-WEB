document.querySelectorAll('.has-sub > a').forEach(menu => {
    menu.addEventListener('click', function (e) {
        e.preventDefault();
        this.parentElement.classList.toggle('open');
    });
});
