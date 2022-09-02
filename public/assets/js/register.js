class Register {
    constructor() {
        this.datatitle = "Register";
        this.dataname = "register";
        this.urlinsert = `/controller/insert/${this.dataname}`;
    }

    alert(alert, icon = "info", callback = console.log("")) {
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

    async send() {
        let form = new FormData(document.querySelector(`form#${this.dataname}`));
        const response = await fetch(this.urlinsert, {
            method: "POST", body: form
        })
        const result = await response.json();

        if (result.success) {
            this.alert(result.status, "success", function () { window.location.href = "/" });;
        } else {
            this.alert(result.status, "error");
        }
    }
}

var register = new Register;