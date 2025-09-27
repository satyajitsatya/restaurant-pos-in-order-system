import "./bootstrap";

// Real-time functionality
window.startRealTimeUpdates = function (updateFunction, interval = 5000) {
    setInterval(updateFunction, interval);
};

// Global toast function
window.showToast = function (message, type = "success") {
    const toastContainer = document.getElementById("toast-container");
    if (!toastContainer) return;

    const toastId = "toast-" + Date.now();
    const bgColor = type === "success" ? "bg-green-500" : "bg-red-500";

    const toast = document.createElement("div");
    toast.id = toastId;
    toast.className = `${bgColor} text-white px-4 py-2 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300`;
    toast.textContent = message;

    toastContainer.appendChild(toast);

    // Animate in
    setTimeout(() => {
        toast.classList.remove("translate-x-full");
    }, 100);

    // Animate out and remove
    setTimeout(() => {
        toast.classList.add("translate-x-full");
        setTimeout(() => toast.remove(), 300);
    }, 3000);
};

// Cart functionality for menu
window.Cart = {
    items: [],

    add(product) {
        const existingItem = this.items.find((item) => item.id === product.id);

        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            this.items.push({
                id: product.id,
                name: product.name,
                price: product.price,
                quantity: 1,
            });
        }

        this.update();
        window.showToast(`${product.name} added to cart!`);
    },

    remove(productId) {
        this.items = this.items.filter((item) => item.id !== productId);
        this.update();
    },

    updateQuantity(productId, quantity) {
        const item = this.items.find((item) => item.id === productId);
        if (item) {
            if (quantity > 0) {
                item.quantity = quantity;
            } else {
                this.remove(productId);
            }
        }
        this.update();
    },

    getTotal() {
        return this.items.reduce(
            (total, item) => total + item.price * item.quantity,
            0
        );
    },

    getCount() {
        return this.items.reduce((total, item) => total + item.quantity, 0);
    },

    clear() {
        this.items = [];
        this.update();
    },

    update() {
        // Update cart display
        const cartCount = document.getElementById("cart-count");
        const cartTotal = document.getElementById("cart-total");

        if (cartCount) cartCount.textContent = this.getCount();
        if (cartTotal)
            cartTotal.textContent =
                "₹" + this.getTotal().toLocaleString("en-IN");

        // Enable/disable checkout button
        const checkoutBtn = document.getElementById("checkout-btn");
        if (checkoutBtn) {
            checkoutBtn.disabled = this.items.length === 0;
        }

        // Update cart items display
        this.updateDisplay();
    },

    updateDisplay() {
        const cartItemsContainer = document.getElementById("cart-items");
        if (!cartItemsContainer) return;

        cartItemsContainer.innerHTML = "";

        if (this.items.length === 0) {
            cartItemsContainer.innerHTML =
                '<p class="text-gray-500 text-center py-8">Your cart is empty</p>';
            return;
        }

        this.items.forEach((item, index) => {
            const itemElement = document.createElement("div");
            itemElement.className =
                "flex items-center justify-between py-3 border-b";
            itemElement.innerHTML = `
                <div class="flex-1">
                    <h4 class="font-medium text-sm">${item.name}</h4>
                    <p class="text-orange-500 font-semibold">₹${item.price.toLocaleString(
                        "en-IN"
                    )}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-sm" onclick="Cart.updateQuantity(${
                        item.id
                    }, ${item.quantity - 1})">-</button>
                    <span class="w-8 text-center font-medium">${
                        item.quantity
                    }</span>
                    <button class="w-8 h-8 rounded-full bg-orange-500 text-white flex items-center justify-center text-sm" onclick="Cart.updateQuantity(${
                        item.id
                    }, ${item.quantity + 1})">+</button>
                </div>
            `;
            cartItemsContainer.appendChild(itemElement);
        });
    },
};
