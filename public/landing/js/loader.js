window.addEventListener('load', function () {
    const spinner = document.getElementById('spinner-loader');

    if (spinner) {
        spinner.classList.add('hide');

        setTimeout(() => {
            spinner.remove(); // lebih bersih dari display none
        }, 500);
    }
});