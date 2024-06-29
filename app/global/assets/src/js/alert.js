import { importjs } from "./import.js";
importjs("https://cdn.jsdelivr.net/npm/sweetalert2@11");

export function Alert(message, icon = "info", callback = console.log) {
    Swal.fire({
        title: message,
        icon: icon,
        buttonsStyling: false,
        didClose: () => {
            callback();
        },
    });
}

export function Toast(message, icon = "info", callback = console.log) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        },
        didClose: () => {
            callback();
        },
    });

    Toast.fire({
        icon: icon,
        title: message
    });
}