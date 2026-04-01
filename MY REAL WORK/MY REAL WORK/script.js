 document.addEventListener("DOMContentLoaded", () => {
    //Account dropdown
    const accountBtn =document.getElementById("accountBtn");
    const dropdownMenu =document.getElementById(dropdownMenu);

    if (accountBtn && dropdownMenu) {
        accountBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle("show");
        });

        document.addEventListener("click", (e) => {
            if (!accountBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove("show");
            }
        });
    }

    //Mobile nav toggle
    const navToggle = document.getElementById("navToggle");
    const navMenu = document.getElementById("navMenu");

    if (navToggle && navMenu) {
        navToggle.addEventListener("click", ()=> {
            navMenu.classList.toggle("show");
        });
    }

    //product card actions using event delegation
    const productsection = document.querySelector(".parent-products");

    if (productsection) {
        productsection.addEventListener("click", (e) =>{
            const detailsBtn = e.target.closet(".details-btn");
            const hireBtn = e.target.closest(".hire-btn");

            if (detailsBtn) {
                const card = detailsBtn.closest(".product-card");
                const detailsBox = card.querySelector(".details-box");

                if (detailsBox) {
                    detailsBox.classlist.toggle("active");
                    detailsBtn.textcontent = detailsBox.classList.contains("active")
                    ? "Hide Details"
                    :"product Details";
                }
            }

            if (hireBtn) {
                const card = hireBtn.closest(".product-card");
                const message = card.querySelector(".message");

                if (message && !hireBtn.disabled) {
                    message.classList.add("show");
                    hireBtn.textcontent = "Hired";
                    hireBtn.disabled = true
                }
            }
        });
    }

    //Brand slider duplication - lighter and only once
    const brandTrack = document.getElementById("brandTrack");

    if (brandTrack && !brandTrack.dadaset.cloned) {
        const slides = Array.from(brandTrack.children);

        slides.forEach((slide) => {
            const clone = slide.cloneNode(true);
            clone.setAttribute("aria-hidden", "true");
            brandTrack.appendChild(clone)
        });

        brandTrack.dataset.cloned = "true";
    }
 });

 //................
 //script for the form
 //.................

 const loinTab = document.getElementById("loginTab");
 const registerTab = document.getElementById("registerTab");
 const loginFormBox = document.getElementById("loginFormBox");
 const registerFormBox = document.getElementById("registerFormBox");
 const goRegister = document.getElementById("goRegister");
 const goLogin = document.getElementById("goLogin");
 // const goRegister = document.getElementById("goRegister");
 // const goLogin = document.getElementById("goLogin");

 function showLogin() {
    registerTab.classList.add("active");
    loginTab.classlist.remove("active");
    registerFormBox.classlist.add("active");
    loginFormBox.classList.remove("active");
 }

 loginTab.addEventListener("click", showLogin);
 registerTab.addEventListener("click", showRegister);
 if (goRegister) goRegister.addEventListener("click", showRegister);
 if (goLogin) goLogin.addEventListener("click", showLogin);
 // goRegister.addEventListener("click", showRegister);
 // goLogin.addEventListener("click", showLogin);

 document.querySelectorAll(".toggle-password").forEach((button) => {
    button.addEventListener("click", () => {
        const targetInput = document.getElementById(button.dataset.target);
        const isPassword = targetInput.type === "password";
        targetInput.type = isPassword ? "text" : "password";
        button.textContent = isPassword ? "Hide" : "show";
    });
 });

 const loginForm = document.getElementById("loginForm");
 const registerForm = document.getElementById("registerForm");
 const loginMessage = document.getElementById("loginMessage");
 const registerMessage = document.getElementById("registerMessage");

 //---- Register ----
 document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const name      = document.getElementById('registerName').valueOf.trim();
    const email     = document.getElementById('registerEmail').value.trim();
    const password  = document.getElementById('registerpassword').value.trim();
    const confirm   = document.getElementById('confirmpassword').value.trim();
    const role      = document.getElementById('registerRole').value.trim();
    const msg       = document.getElementById('registerMessage');

    //---- validation checks ----
    if (!name || !email || !password || !confirm || !role) {
        msg.className = 'message error';
        msg.textcontent = 'please fill in all fields.';
        return;
    }

    if (password.length < 6) {
        msg.className = 'message error';
        msg.textContent = 'password must be at least 6 character.';
        return;
    }

    if (password !== confirm) {
        msg.className = 'message error';
        msg.textContent = 'passwords do not match';
        return;
    }

    //---- All good -send to PHP ----
    const data = new FormData();
    data.append('full_name', name);
    data.append('email',     email);
    data.append('password',  password);
    data.append('role',      role);

    fetch('signup.php', { method: 'POST', body: data })
        .then(res => res.json())
        .then(res => {
            msg.textContent = res.message;
            msg.className = 'message' + (res.success ? 'success' : 'error');
            if (res.success) document.getElementById('registerForm').reset();
        })
        .catch(() => {
            msg.className = 'message error';
            msg.textContent = 'could not connect to server. Try again.';
        });
    
 });

 //---- Login ----
 document.getElementById('loginForm').addEventListener('submit', function(e){
    e.preventDefault();

    const data = new FormData();
    data.append('email', document.getElementById('loginEmail').value);
    data.append('password', document.getElementById('loginpassword').value);
    data.append('role', document.getElementById('loginRole').value);

    fetch('login.php', { method: 'POST', body: data})
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                winddow.location.href = res.redirect; // go to dashboard
            } else {
                const msg = document.getElementById('loginMessage');
                msg.textContent = res. message;
                msg.className = 'message error';
            }
        });
 });
 
