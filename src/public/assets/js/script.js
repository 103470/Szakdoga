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

    document.querySelectorAll('.cart-item .quantity-increase, .cart-item .quantity-decrease').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const delta = this.classList.contains('quantity-increase') ? 1 : -1;
            changeQuantity(id, delta);
        });
    });

    document.querySelectorAll('.cart-item .remove-item-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            removeItemFromCart(id);
        });
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

    const cartDropdown = document.querySelector('#cart-dropdown');

    if (cartDropdown) {
        cartDropdown.addEventListener('click', function(e) {
            const btn = e.target.closest('.quantity-increase, .quantity-decrease, .remove-item-btn');
            if (!btn) return;

            e.preventDefault();
            e.stopPropagation();

            const id = btn.dataset.id;
            console.log('Kattintott gomb:', btn.className, 'Termék ID:', id); 

            if (btn.classList.contains('quantity-increase')) {
                console.log('Növeljük a mennyiséget');
                changeQuantity(id, 1);
            }
            if (btn.classList.contains('quantity-decrease')) {
                console.log('Csökkentjük a mennyiséget');
                changeQuantity(id, -1);
            }
            if (btn.classList.contains('remove-item-btn')) {
                console.log('Eltávolítjuk a terméket a kosárból');
                removeItemFromCart(id);
            }
        });
    }

    function refreshCartDropdown() {
        fetch('/cart/dropdown')
            .then(res => res.json())
            .then(cartData => {
                if (!cartDropdown) return;

                let items = Array.isArray(cartData.cart) ? cartData.cart : Object.values(cartData.cart);

                if (!items || items.length === 0) {
                    cartDropdown.innerHTML = `<p class="text-center p-3">A kosár üres <i class="fa-solid fa-cart-shopping"></i></p>`;
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

                cartDropdown.innerHTML = html;

                const cartCountElem = document.querySelector('.cart-count');
                if (cartCountElem && cartData.count !== undefined) {
                    cartCountElem.textContent = cartData.count;
                }
            })
            .catch(err => console.error('Kosár dropdown frissítési hiba:', err));
    }

    function changeQuantity(id, delta) {
        console.log(`changeQuantity hívva: id=${id}, delta=${delta}`);
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
            console.log('Válasz a PATCH hívásból:', data);

            if (data.success) {
                updateCartPageItem(id, data.newQuantity);
                updateCartSummary();
                refreshCartDropdown();
            }
        })
        .catch(err => console.error('Hiba a mennyiség frissítésekor:', err));
    }

    function removeItemFromCart(id) {
        console.log(`removeItemFromCart hívva: id=${id}`);
        fetch(`/cart/item/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            refreshCartDropdown();

            const cartCountElem = document.querySelector('.cart-count');
            if (cartCountElem && data.count !== undefined) cartCountElem.textContent = data.count;

            const itemDiv = document.querySelector(`.cart-item .quantity-input[data-id="${id}"]`)?.closest('.cart-item');
            if (itemDiv) itemDiv.remove();

            updateCartSummary();
            
            const cartContainer = document.querySelector('.col-lg-8');
            if (!cartContainer.querySelector('.cart-item')) {
                cartContainer.innerHTML = `
                    <div class="text-center text-muted py-5">
                        <i class="fa-solid fa-cart-shopping fa-3x mb-3"></i>
                        <p class="fs-5">Üres a kosarad</p>
                        <a href="{{ route('home') }}" class="btn btn-primary mt-3">Vásárlás folytatása</a>
                    </div>
                `;
            }
        })
        .catch(err => console.error('Hiba a törléskor:', err));
    }

    function updateCartPageItem(id, quantity) {
        const itemDiv = document.querySelector(`.cart-item .quantity-input[data-id="${id}"]`);
         console.log('updateCartPageItem selektor:', itemDiv);
        if (!itemDiv) {
            console.warn('Nem található quantity-input id:', id);
            return;
        }

        itemDiv.value = quantity;

        const parent = itemDiv.closest('.cart-item');
        if (!parent) {
            console.warn('Nem található cart-item szülő a termékhez id:', id);
            return;
        }

        const totalDiv = parent.querySelector('.text-end.fw-bold');
        if (!totalDiv) {
            console.warn('Nem található totalDiv id:', id);
            return;
        }

        const price = parseFloat(totalDiv.dataset.price);
        if (isNaN(price)) {
            console.warn('data-price hiányzik vagy nem szám id:', id, 'totalDiv:', totalDiv);
            return;
        }

        totalDiv.textContent = (price * quantity).toLocaleString('hu-HU') + ' Ft';
        console.log(`Termék frissítve: id=${id}, quantity=${quantity}, price=${price}, total=${price*quantity}`);

        let subtotal = 0;
        document.querySelectorAll('.cart-item').forEach(cartItem => {
            const input = cartItem.querySelector('.quantity-input');
            const totalEl = cartItem.querySelector('.text-end.fw-bold');
            if (input && totalEl) {
                const q = parseInt(input.value) || 0;
                const p = parseFloat(totalEl.dataset.price) || 0;
                subtotal += q * p;
            }
        });

        const totalElem = document.getElementById('cart-total');
        if (!totalElem) console.warn('Nem található #cart-total elem');
        else totalElem.textContent = subtotal.toLocaleString('hu-HU') + ' Ft';

        console.log('Új subtotal:', subtotal);
    }

    function updateCartSummary() {
        const summaryContainer = document.querySelector('.card-body'); 
        if (!summaryContainer) return;

        const summaryList = summaryContainer.querySelectorAll('.d-flex.justify-content-between.mb-1');
        let subtotal = 0;
        summaryList.forEach((row) => row.remove()); 

        document.querySelectorAll('.cart-item').forEach(cartItem => {
            const input = cartItem.querySelector('.quantity-input');
            const name = cartItem.querySelector('h5 a')?.textContent || 'Ismeretlen termék';
            const price = parseFloat(cartItem.querySelector('.text-end.fw-bold')?.dataset.price) || 0;
            const quantity = parseInt(input.value) || 0;
            const itemTotal = price * quantity;
            subtotal += itemTotal;

            const div = document.createElement('div');
            div.className = 'd-flex justify-content-between mb-1';
            div.innerHTML = `<span>${quantity} × ${name}</span><span>${itemTotal.toLocaleString('hu-HU')} Ft</span>`;
            summaryContainer.insertBefore(div, summaryContainer.querySelector('hr'));
        });

        const totalElem = document.getElementById('cart-total');
        if (totalElem) totalElem.textContent = subtotal.toLocaleString('hu-HU') + ' Ft';
    }


    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.cart-item .quantity-increase, .cart-item .quantity-decrease, .cart-item .remove-item-btn');
        if (!btn) return;

        const id = btn.dataset.id;
        console.log('Kattintott gomb:', btn.className, 'Termék ID:', id);

        if (btn.classList.contains('quantity-increase')) {
            console.log('Növeljük a mennyiséget');
            changeQuantity(id, 1);
        } else if (btn.classList.contains('quantity-decrease')) {
            console.log('Csökkentjük a mennyiséget');
            changeQuantity(id, -1);
        } else if (btn.classList.contains('remove-item-btn')) {
            console.log('Eltávolítjuk a terméket a kosárból');
            removeItemFromCart(id);
        }
    });

    refreshCartDropdown();


    const sameAsShippingElem = document.getElementById('sameAsShipping');
    if (sameAsShippingElem) {
        sameAsShippingElem.addEventListener('change', function() {
            const checked = this.checked;

            const fields = [
                'name', 'email', 'phone_prefix', 'phone',
                'street_type', 'street_name', 'house_number',
                'building', 'floor', 'door', 'city', 'zip', 'country'
            ];

            fields.forEach(field => {
                const shippingElem = document.getElementById(`shipping_${field}`);
                const billingElem = document.getElementById(`billing_${field}`);
                if (shippingElem && billingElem) {
                    billingElem.value = checked ? shippingElem.value : '';
                }
            });
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

    const stripeButton = document.getElementById('stripe-submit');
    if (stripeButton) {
        stripeButton.addEventListener('click', async function(e) {
            e.preventDefault();

            const selectedPayment = document.querySelector('input[name="payment_option"]:checked');
            if (!selectedPayment) return;

            if (selectedPayment.dataset.type === 'card') {
                try {
                    const finalizeRes = await fetch("/checkout/finalize", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            delivery_option: document.querySelector('input[name="delivery_option"]:checked')?.value,
                            payment_option: 'card'
                        })
                    });

                    const finalizeData = await finalizeRes.json(); 

                    const stripeRes = await fetch("/checkout/create-session", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ order_id: finalizeData.order_id })
                    });

                    const stripeData = await stripeRes.json(); 

                    if (stripeData.url) {
                        window.location.href = stripeData.url;
                    } else {
                        alert(stripeData.error || 'Hiba a Stripe session létrehozásakor');
                    }

                } catch (err) {
                    console.error('Fetch hiba:', err);
                    alert('Szerverhiba történt!');
                }
            } else {
                document.getElementById('checkout-form').submit();
            }
        });


    }


    const pendingDiv = document.getElementById('pending-order');
    if (!pendingDiv) return;

    const orderId = pendingDiv.dataset.orderId;

    async function checkPayment() {
        const res = await fetch(`/checkout/payment-status/${orderId}`);
        const data = await res.json();

        if (data.status === 'succeeded') {
            window.location.href = `/checkout/success?order_id=${orderId}`;
        } else if (data.status === 'pending') {
            setTimeout(checkPayment, 5000);
        } else {
            window.location.href = `/checkout/payment`;
        }
    }

    checkPayment();

});
