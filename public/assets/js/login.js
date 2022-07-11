importjs=function(l){d=document,e=d.createElement('script');e.setAttribute("src",l);d.head.appendChild(e);} 
importjs("https://cdn.jsdelivr.net/npm/sweetalert2@11")

class Login{
    constructor(user,pw,token){
        this.form = new FormData;
        this.setUser(user);
        this.setPw(pw);
        this.setToken(token);
    }

    dataValidate(){
        if(!this.user || this.pw < 8){
            this.alert("Usuário ou senha incorreta!", "error");
            return false;
        } else {
            return true;
        }        
    }

    alert(alert, icon = "info"){
        if (alert) {
            this.alertText = alert;
        }
        Swal.fire({
            title: this.alertText,
            icon: icon,
            buttonsStyling: false,
        })
    }

    toast(alert, icon = "info", callback){
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

    setUser(user){
        this.user = user;
        this.form.set("user", user);
    }

    setPw(pw){
        this.pw = pw;
        this.form.set("pw", pw);
    }

    setToken(token){
        this.token = token;
        this.form.set("token", token);
    }

    async send(){
        const response = await fetch("controller/login",{
            method:"POST",body:this.form
        })
        const result = await response.json();
        if (result.success) {
            this.toast(result.status, "success", function(){window.location.href = "/"});
        } else {
            this.alert(result.status, "error");
        }
    }
}

function login(token){
    var user = document.querySelector("[name=loginuser]").value,
    pw = document.querySelector("[name=loginpw]").value,
    login = new Login(user,pw,token);
    if(login.dataValidate()){
        login.send();
    };  
}