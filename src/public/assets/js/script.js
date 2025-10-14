document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                window.scrollTo({
                    top: target.offsetTop - 70, 
                    behavior: 'smooth'
                });
            }
        });
    });

     document.querySelectorAll('.quantity-selector').forEach(selector => {
        const decreaseBtn = selector.querySelector('.quantity-decrease');
        const increaseBtn = selector.querySelector('.quantity-increase');
        const input = selector.querySelector('.quantity-input');

        if (!input) return;

        const min = parseInt(input.getAttribute('min')) || 1;
        const max = parseInt(input.getAttribute('max')) || 1;

        const updateButtons = () => {
            decreaseBtn.disabled = parseInt(input.value) <= min;
            increaseBtn.disabled = parseInt(input.value) >= max;
        };

        decreaseBtn.addEventListener('click', () => {
            let value = parseInt(input.value) || min;
            if (value > min) input.value = value - 1;
            updateButtons();
        });

        increaseBtn.addEventListener('click', () => {
            let value = parseInt(input.value) || min;
            if (value < max) input.value = value + 1;
            updateButtons();
        });

        updateButtons();
    });
});







