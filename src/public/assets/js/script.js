document.addEventListener("DOMContentLoaded", function() {
    const typeSearch = document.getElementById('typeSearch');

    if (typeSearch) {

        const normalize = str => str?.normalize('NFD').replace(/[\u0300-\u036f]/g, '').trim().toLowerCase() || '';

        typeSearch.addEventListener('input', function() {
            const filter = normalize(this.value);

            const cards = document.querySelectorAll('.type-card');
            cards.forEach(card => {
                const titleElem = card.querySelector('.type-card-title');
                const text = titleElem ? titleElem.textContent : card.textContent;
                const matches = normalize(text).includes(filter);
                card.classList.toggle('d-none', !matches);
            });

            const rows = document.querySelectorAll('.vintage-table tbody tr');
            rows.forEach(row => {
                if (!row.querySelector('td')) return;

                const rowText = Array.from(row.querySelectorAll('td'))
                    .map(td => td.textContent)
                    .join(' ');

                const matches = normalize(rowText).includes(filter);
                row.style.display = matches ? '' : 'none';
            });
        });
    }


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
        const max = parseInt(input.getAttribute('max')) || 100;

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

    document.body.addEventListener('click', function (e) {
        const incBtn = e.target.closest('.quantity-increase');
        const decBtn = e.target.closest('.quantity-decrease');

        if (incBtn) {
            e.preventDefault();
            const id = incBtn.dataset.id;
            changeQuantity(id, 1, true);
        }

        if (decBtn) {
            e.preventDefault();
            const id = decBtn.dataset.id;
            changeQuantity(id, -1, true);
        }

        const removeBtn = e.target.closest('.remove-item-btn');
        if (removeBtn) {
            e.preventDefault();
            const id = removeBtn.dataset.id;
            removeItemFromCart(id, true);
        }
    });
    
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.id;
            const card = this.closest('.card') || document;
            const quantityInput = card.querySelector('.quantity-input');
            const quantity = quantityInput ? parseInt(quantityInput.value) : 1;

            fetch(`/cart/add/${productId}`, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ quantity })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount && data.cartCount !== undefined) cartCount.textContent = data.cartCount;
                    refreshCartDropdown();
                } else {
                    showAlert('Hiba történt a kosárhoz adáskor!', 'danger');
                }
            })
            .catch(err => {
                console.error('Hiba a kosárhoz adáskor:', err);
                showAlert('Szerverhiba!', 'danger');
            });
        });
    });

    function showAlert(message, type = 'success') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
        alertDiv.style.zIndex = '1055';
        alertDiv.innerHTML = `
            <div>${message}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.body.appendChild(alertDiv);
        setTimeout(() => {
            alertDiv.classList.remove('show');
            setTimeout(() => alertDiv.remove(), 300);
        }, 3000);
    }

    function refreshCartDropdown() {
        fetch('/cart/dropdown')
            .then(res => res.json())
            .then(cartData => {
                const dropdown = document.querySelector('#cart-dropdown');
                if (!dropdown) return;

                let items = Array.isArray(cartData.cart) ? cartData.cart : Object.values(cartData.cart);

                if (!items || items.length === 0) {
                    dropdown.innerHTML = `
                        <p class="text-center p-3">A kosár üres <i class="fa-solid fa-cart-shopping"></i></p>
                    `;
                    const cartCountElem = document.querySelector('.cart-count');
                    if (cartCountElem) cartCountElem.textContent = 0;
                    return; 
                }

                let html = `<ul class="list-group list-group-flush">`;

                items.forEach(item => {
                    html += `
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <img src="${item.product?.image ?? '/placeholder.png'}" 
                                alt="${item.product?.name ?? 'Termék'}" 
                                class="me-2" 
                                style="width:50px; height:50px; object-fit:cover;">

                            <div class="flex-grow-1 me-2">
                                <div class="fw-bold">${item.product?.name ?? 'Ismeretlen termék'}</div>
                                <div class="text-muted">${item.product?.price ?? 0} Ft</div>
                            </div>

                            <div class="input-group input-group-sm me-2" style="width:100px;">
                                <button class="btn btn-outline-secondary quantity-decrease" data-id="${item.product_id}">-</button>
                                <input type="text" class="form-control text-center quantity-input" 
                                    value="${item.quantity}" readonly 
                                    data-max="${item.product?.stock ?? 1}" 
                                    data-id="${item.product_id}">
                                <button class="btn btn-outline-secondary quantity-increase" data-id="${item.product_id}">+</button>
                            </div>

                            <button class="btn btn-outline-danger btn-sm remove-item-btn" data-id="${item.product_id}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </li>
                    `;
                });

                html += `</ul>
                        <div class="p-3 text-end fw-bold">Összesen: ${cartData.total ?? 0} Ft</div>
                        <div class="p-2 text-center">
                            <a href="/cart" class="btn btn-warning w-100">🛒 Kosár megtekintése</a>
                        </div>`;

                dropdown.innerHTML = html;

                const cartCountElem = document.querySelector('.cart-count');
                if (cartCountElem && cartData.count !== undefined) {
                    cartCountElem.textContent = cartData.count;
                }

                dropdown.querySelectorAll('.quantity-increase').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        const input = btn.closest('li').querySelector('.quantity-input');
                        const max = parseInt(input.dataset.max) || 1;
                        if (parseInt(input.value) < max) changeQuantity(btn.dataset.id, 1);
                    });
                });

                dropdown.querySelectorAll('.quantity-decrease').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        const input = btn.closest('li').querySelector('.quantity-input');
                        if (parseInt(input.value) > 1) changeQuantity(btn.dataset.id, -1);
                    });
                });

                dropdown.querySelectorAll('.remove-item-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        removeItemFromCart(btn.dataset.id);
                    });
                });
            })
            .catch(err => console.error('Kosár dropdown frissítési hiba:', err));
    }

    function changeQuantity(id, delta, isCartPage = false) {
        const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
        if (!input) return;

        fetch(`/cart/item/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ delta })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) return;

            input.value = data.newQuantity;

            if (isCartPage) {
                const subtotalElem = document.getElementById('subtotal');
                const shippingElem = document.getElementById('shipping');
                const totalElem = document.getElementById('cart-total');

                if (subtotalElem) subtotalElem.textContent = (data.subtotal ?? 0).toLocaleString('hu-HU') + ' Ft';
                const shippingCost = (data.count > 0 ? 990 : 0);
                if (shippingElem) shippingElem.textContent = shippingCost.toLocaleString('hu-HU') + ' Ft';
                if (totalElem) totalElem.textContent = ((data.subtotal ?? 0) + shippingCost).toLocaleString('hu-HU') + ' Ft';
            } else {
                refreshCartDropdown();
            }

            const cartCountElem = document.querySelector('.cart-count');
            if (cartCountElem && data.count !== undefined) cartCountElem.textContent = data.count;
        })
        .catch(err => console.error('Hiba a mennyiség frissítésekor:', err));
    }



    function removeItemFromCart(id, isCartPage = false) {
        fetch(`/cart/item/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            if (isCartPage) {
                const itemElem = document.querySelector(`.remove-item-btn[data-id="${id}"]`)?.closest('.cart-item');
                if (itemElem) {
                    itemElem.style.transition = 'opacity 0.3s';
                    itemElem.style.opacity = 0;
                    setTimeout(() => itemElem.remove(), 300);
                }

                const subtotalElem = document.getElementById('subtotal');
                const shippingElem = document.getElementById('shipping');
                const totalElem = document.getElementById('cart-total');

                if (subtotalElem) subtotalElem.textContent = (data.subtotal ?? 0).toLocaleString('hu-HU') + ' Ft';
                const shippingCost = (data.count > 0 ? 990 : 0);
                if (shippingElem) shippingElem.textContent = shippingCost.toLocaleString('hu-HU') + ' Ft';
                if (totalElem) totalElem.textContent = ((data.subtotal ?? 0) + shippingCost).toLocaleString('hu-HU') + ' Ft';

                if (data.count === 0) {
                    document.querySelector('.col-lg-8').innerHTML = `
                        <div class="text-center text-muted py-5">
                            <i class="fa-solid fa-cart-shopping fa-3x mb-3"></i>
                            <p class="fs-5">Üres a kosarad</p>
                            <a href="/" class="btn btn-primary mt-3">Vásárlás folytatása</a>
                        </div>
                    `;
                }
            } else {
                const itemElem = document.querySelector(`.remove-item-btn[data-id="${id}"]`)?.closest('li');
                if (itemElem) {
                    itemElem.style.transition = 'opacity 0.3s';
                    itemElem.style.opacity = 0;
                    setTimeout(() => itemElem.remove(), 300);
                }

                const totalElem = document.querySelector('#cart-dropdown .text-end.fw-bold');
                if (totalElem) totalElem.textContent = `Összesen: ${data.total.toLocaleString('hu-HU')} Ft`;
                refreshCartDropdown();
            }

            const cartCountElem = document.querySelector('.cart-count');
            if (cartCountElem && data.count !== undefined) {
                cartCountElem.textContent = data.count;
            }
        })
        .catch(err => console.error('Hiba a törléskor:', err));
    }

    refreshCartDropdown();

    const sameAsShippingElem = document.getElementById('sameAsShipping');
    if (sameAsShippingElem) {
        sameAsShippingElem.addEventListener('change', function() {
            const checked = this.checked;
            if(checked) {
                document.getElementById('billing_name').value = document.getElementById('shipping_name').value;
                document.getElementById('billing_email').value = document.getElementById('shipping_email').value;

                const prefix = document.getElementById('shipping_phone_prefix').value;
                const number = document.getElementById('shipping_phone').value;
                document.getElementById('billing_phone').value = number;
                document.getElementById('billing_phone_prefix').value = prefix;

                document.getElementById('billing_address').value = document.getElementById('shipping_address').value;
                document.getElementById('billing_city').value = document.getElementById('shipping_city').value;
                document.getElementById('billing_zip').value = document.getElementById('shipping_zip').value;
                document.getElementById('billing_country').value = document.getElementById('shipping_country').value;
            } else {
                document.getElementById('billing_name').value = '';
                document.getElementById('billing_email').value = '';
                document.getElementById('billing_phone').value = '';
                document.getElementById('billing_phone_prefix').value = '';
                document.getElementById('billing_address').value = '';
                document.getElementById('billing_city').value = '';
                document.getElementById('billing_zip').value = '';
                document.getElementById('billing_country').value = '';
            }
        });
    }

    const subtotalElem = document.getElementById('subtotal-value');
    if (subtotalElem) {

        function updateTotal() {
            const subtotal = Number(subtotalElem.dataset.subtotal) || 0;
            const delivery = document.querySelector('input[name="delivery_option"]:checked');
            const payment = document.querySelector('input[name="payment_option"]:checked');
            const deliveryPrice = Number(delivery?.dataset.price) || 0;
            const paymentPrice = Number(payment?.dataset.fee) || 0;
            const sum = subtotal + deliveryPrice + paymentPrice;

            const shippingElem = document.getElementById('shipping-cost');
            const paymentElem = document.getElementById('payment-fee');
            const totalElem = document.getElementById('total');

            if (shippingElem) shippingElem.textContent = deliveryPrice.toLocaleString('hu-HU') + ' Ft';
            if (paymentElem) paymentElem.textContent = paymentPrice.toLocaleString('hu-HU') + ' Ft';
            if (totalElem) totalElem.textContent = sum.toLocaleString('hu-HU') + ' Ft';
        }

        document.querySelectorAll('input[name="delivery_option"], input[name="payment_option"]')
            .forEach(r => r.addEventListener('change', updateTotal));

        updateTotal();
    }

    const checkoutForm = document.getElementById('checkout-form');
    if (!checkoutForm) return;

    checkoutForm.addEventListener('submit', function(e) {
        const tosCheckbox = document.getElementById('accept-tos');
        const privacyCheckbox = document.getElementById('accept-privacy');

        const tosError = document.getElementById('tos-error');
        const privacyError = document.getElementById('privacy-error');

        let valid = true;

        if (!tosCheckbox.checked) {
            valid = false;
            tosCheckbox.classList.add('is-invalid');
            tosError.textContent = 'El kell fogadni az Általános Szerződési feltételeket!';
        } else {
            tosCheckbox.classList.remove('is-invalid');
            tosError.textContent = '';
        }

        if (!privacyCheckbox.checked) {
            valid = false;
            privacyCheckbox.classList.add('is-invalid');
            privacyError.textContent = 'El kell fogadni az adatvédelmi szabályzatot!';
        } else {
            privacyCheckbox.classList.remove('is-invalid');
            privacyError.textContent = '';
        }

        if (!valid) {
            e.preventDefault();
            e.stopPropagation();
        }
    });


});
