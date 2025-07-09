 // Wait for DOM to load
document.addEventListener("DOMContentLoaded", () => {
  // Toggle show/hide password
  const togglePassword = document.getElementById("togglePassword");
  const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");
  const password = document.getElementById("password");
  const confirmPassword = document.getElementById("confirm-password");

  togglePassword?.addEventListener("click", () => {
    const type = password.type === "password" ? "text" : "password";
    password.type = type;
    togglePassword.textContent = type === "password" ? "Show" : "Hide";
  });

  toggleConfirmPassword?.addEventListener("click", () => {
    const type = confirmPassword.type === "password" ? "text" : "password";
    confirmPassword.type = type;
    toggleConfirmPassword.textContent = type === "password" ? "Show" : "Hide";
  });

  // Handle form submission
  document.getElementById("register-form")?.addEventListener("submit", function (e) {
    e.preventDefault();

    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const pwd = password.value.trim();
    const confirmPwd = confirmPassword.value.trim();

    if (!name || !email || !pwd || !confirmPwd) {
      alert("Please fill in all fields.");
      return;
    }

    if (pwd !== confirmPwd) {
      alert("Passwords do not match.");
      return;
    }

    alert("Account created successfully for: " + name);

    // Example: Use fetch() to send data to server
  });

  // Menu toggle
  const menu = document.querySelector('#menu-bar');
  const navbar = document.querySelector('.navbar');

  menu?.addEventListener("click", () => {
    menu.classList.toggle('fa-times');
    navbar.classList.toggle('active');
  });

  // Scroll behavior
  window.addEventListener("scroll", () => {
    menu?.classList.remove('fa-times');
    navbar?.classList.remove('active');

    if (window.scrollY > 60) {
      document.querySelector('#scroll-top')?.classList.add('active');
    } else {
      document.querySelector('#scroll-top')?.classList.remove('active');
    }
  });

  // Loader
  function loader() {
    document.querySelector('.loader-container')?.classList.add('fade-out');
  }

  function fadeOut() {
    setInterval(loader, 1500);
  }

  fadeOut(); // Start loader fade on load
});
// === CART FUNCTIONS ===
function getCartItems() {
  const items = localStorage.getItem("cartItems");
  return items ? JSON.parse(items) : [];
}

function setCartItems(items) {
  localStorage.setItem("cartItems", JSON.stringify(items));
}

function updateCartBadge() {
  const items = getCartItems();
  const count = items.reduce((sum, item) => sum + item.qty, 0);
  const badge = document.getElementById("cart-count");
  if (badge) badge.textContent = count;
}

// === ADD TO CART ON CLICK ===
document.querySelectorAll(".btn").forEach(btn => {
  if (btn.textContent.toLowerCase().includes("order now")) {
    btn.addEventListener("click", e => {
      e.preventDefault();
      const box = btn.closest(".box");
      const name = box?.querySelector("h3")?.innerText || "Item";
      const price = 5; // Example price

      let items = getCartItems();
      const existing = items.find(i => i.name === name);
      if (existing) {
        existing.qty += 1;
      } else {
        items.push({ name, price, qty: 1 });
      }

      setCartItems(items);
      updateCartBadge();
      alert(`${name} added to cart!`);
    });
  }
});

updateCartBadge();
// ========== LOGIN ACCESS CONTROL ==========

// Pages that require login
const protectedPages = ["cart.html", "order.html"];

// Get current page
const currentPage = window.location.pathname.split("/").pop();

if (protectedPages.includes(currentPage)) {
  const isLoggedIn = localStorage.getItem("userLoggedIn") === "true";
  if (!isLoggedIn) {
    alert("Please log in to access this page.");
    window.location.href = "account.html";
  }
}
   const mobileInput = document.getElementById("mobile");

  mobileInput.addEventListener("keypress", function(e) {
    // Allow only digits (0–9)
    if (!/[0-9]/.test(e.key)) {
      e.preventDefault();
    }
  });

  mobileInput.addEventListener("paste", function(e) {
    // Prevent pasting non-digit characters
    const pasted = e.clipboardData.getData("text");
    if (!/^\d+$/.test(pasted)) {
      e.preventDefault();
    }
  });
  

 