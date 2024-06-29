importjs = function (l) { d = document, e = d.createElement('script'); e.setAttribute("src", l); d.head.appendChild(e); }
importjs("https://cdn.jsdelivr.net/npm/sweetalert2@11")

class Login {
    constructor() {
        var form_elements = document.querySelectorAll("#form-login-recovery *");

        form_elements.forEach(element => {
            element.disabled = true;
        });

        this.username = document.querySelector("#form-login-recovery [name=username]").value;
        this.password = document.querySelector("#form-login-recovery [name=password]").value;
        this.persistent = document.querySelector("#form-login-recovery [name=persistent]").value;
        if (this.dataValidate()) {
            this.send();
        } else {
            form_elements.forEach(element => {
                element.disabled = false;
            });
        };
    }

    dataValidate() {
        if (!this.username || this.password < 8) {
            this.alert("Incorrect username or password!", "error");
            return false;
        } else {
            return true;
        }
    }

    async send() {
        this.form = new FormData;
        this.form.set("username", this.username);
        this.form.set("password", this.password);
        this.form.set("persistent", this.persistent);
        const response = await fetch("controller/login", {
            method: "POST", body: this.form
        })
        const result = await response.json();
        if (result.success) {
            this.toast("Login efetuado com sucesso!", "success", function () { window.location.href = "/" });
        } else {
            this.alert(result.status, "error");
            var form_elements = document.querySelectorAll("#form-login-recovery *");
            form_elements.forEach(element => {
                element.disabled = false;
            });
        }
    }

    alert(alert, icon = "info", callback = console.log) {
        if (alert) {
            this.alertText = alert;
        }
        Swal.fire({
            title: this.alertText,
            icon: icon,
            buttonsStyling: false,
            didClose: () => {
                callback();
            },
        })
    }

    toast(alert, icon = "info", callback = console.log) {
        if (alert) {
            this.alertText = alert;
        }
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            },
            didClose: () => {
                callback();
            },
        })

        Toast.fire({
            icon: icon,
            title: this.alertText
        })
    }
}

class RecoveryPassword {
    constructor() {
        var form_elements = document.querySelectorAll("#form-login-recovery *");

        form_elements.forEach(element => {
            element.disabled = true;
        });

        this.username = document.querySelector("#form-login-recovery [name=username]").value;
        if (this.dataValidate()) {
            this.send();
        } else {
            form_elements.forEach(element => {
                element.disabled = false;
            });
        }
    }

    dataValidate() {
        if (!this.username) {
            this.alert("Incorrect username!", "error");
            return false;
        } else {
            return true;
        }
    }

    async send() {
        this.form = new FormData;
        this.form.set("username", this.username);
        const response = await fetch("controller/recoverypassword", {
            method: "POST", body: this.form
        })
        const result = await response.json();
        if (result.success) {
            this.alert("Solicitação efetuada com sucesso, verifique seu e-mail!", "success", function () { window.location.href = "/" });
        } else {
            this.alert(result.status, "error");
            var form_elements = document.querySelectorAll("#form-login-recovery *");
            form_elements.forEach(element => {
                element.disabled = false;
            });
        }
    }

    alert(alert, icon = "info", callback = () => { }) {
        if (alert) {
            this.alertText = alert;
        }
        Swal.fire({
            title: this.alertText,
            icon: icon,
            buttonsStyling: false,
            didClose: () => {
                callback();
            },
        })
    }

    toast(alert, icon = "info", callback) {
        if (alert) {
            this.alertText = alert;
        }
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            },
            didClose: () => {
                callback();
            },
        })

        Toast.fire({
            icon: icon,
            title: this.alertText
        })
    }
}

class NewPassword {
    constructor() {
        var form_elements = document.querySelectorAll("#form-login-recovery *");

        form_elements.forEach(element => {
            element.disabled = true;
        });

        this.password = document.querySelector("#form-login-recovery [name=password]").value;
        this.confirmpassword = document.querySelector("#form-login-recovery [name=confirm-password]").value;
        if (this.dataValidate()) {
            this.send();
        } else {
            form_elements.forEach(element => {
                element.disabled = false;
            });
        };
    }

    dataValidate() {
        if (!this.password != this.confirmpassword) {
            this.alert("Senhas não coincidem!", "error");
            return false;
        } else {
            return true;
        }
    }

    async send() {
        this.form = new FormData;
        this.form.set("password", this.password);
        const response = await fetch("controller/newpassword", {
            method: "POST", body: this.form
        })
        const result = await response.json();
        if (result.success) {
            this.toast("Senha atualizada com sucesso!", "success", function () { window.location.href = "/" });
        } else {
            this.alert(result.status, "error");
            var form_elements = document.querySelectorAll("#form-login-recovery *");
            form_elements.forEach(element => {
                element.disabled = false;
            });
        }
    }

    alert(alert, icon = "info") {
        if (alert) {
            this.alertText = alert;
        }
        Swal.fire({
            title: this.alertText,
            icon: icon,
            buttonsStyling: false,
        })
    }

    toast(alert, icon = "info", callback) {
        if (alert) {
            this.alertText = alert;
        }
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            },
            didClose: () => {
                callback();
            },
        })

        Toast.fire({
            icon: icon,
            title: this.alertText
        })
    }
}

function SwitchMode() {
    var title = document.querySelector("#form-login-recovery>h1"),
        username = document.querySelector("#form-login-recovery [name=username]"),
        password = document.querySelector("#form-login-recovery [name=password]"),
        email = document.querySelector("#form-login-recovery [name=email]"),
        confirmpassword = document.querySelector("#form-login-recovery [name=confirm-password]"),
        switchmode = document.querySelector("#form-login-recovery>#switch-mode"),
        mode = switchmode.innerHTML;

    switchmode.innerHTML = title.innerHTML;
    title.innerHTML = mode;

    if (mode === "Login") {
        username.style.display = "initial";
        email.style.display = "none";
        password.style.display = "initial";
        confirmpassword.style.display = "none";
        history.pushState({}, mode, "/login");
    }
    else if (mode === "Recovery password") {
        username.style.display = "initial";
        email.style.display = "initial";
        password.style.display = "none";
        confirmpassword.style.display = "none";
        history.pushState({}, mode, "/recoverypassword");
    }
    else if (mode === "New password") {
        username.style.display = "none";
        email.style.display = "none";
        password.style.display = "initial";
        confirmpassword.style.display = "initial";
        history.pushState({}, mode, "/newpassword");
    }
}


document.querySelector("#form-login-recovery>button").addEventListener("click", function (event) {
    event.preventDefault();
    new Login();
});

document.querySelectorAll('#form-login-recovery *').forEach(element => {
    element.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            var mode = document.querySelector("#form-login-recovery h1").innerHTML;
            if (mode === "Login") {
                new Login();
            }
            else if (mode === "Recovery password") {
                new RecoveryPassword();
            }
            else if (mode === "New password") {
                new NewPassword();
            }
        }
    })
});