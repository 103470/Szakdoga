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
});

document.addEventListener("DOMContentLoaded", function() {
    const selectors = document.querySelectorAll(".quantity-selector");

    selectors.forEach(selector => {
        const decreaseBtn = selector.querySelector(".quantity-decrease");
        const increaseBtn = selector.querySelector(".quantity-increase");
        const input = selector.querySelector(".quantity-input");

        decreaseBtn.addEventListener("click", () => {
            let value = parseInt(input.value) || 1;
            if (value > parseInt(input.min)) value--;
            input.value = value;
        });

        increaseBtn.addEventListener("click", () => {
            let value = parseInt(input.value) || 1;
            value++;
            input.value = value;
        });
    });
});

