export const importjs = function (url) {
    let d = document;
    let es = document.querySelectorAll('script');
    for (let i = 0; i < es.length; i++) {
        if (es[i].src === url) {
            return;
        }
    }

    let e = d.createElement('script');
    e.setAttribute("src", url);
    d.head.appendChild(e);
};

export const importcss = function (url) {
    let d = document;
    let es = document.querySelectorAll('link');
    for (var i = 0; i < es.length; i++) {
        if (es[i].href === url && es[i].rel === "stylesheet") {
            return;
        }
    }

    let e = d.createElement('link');
    e.setAttribute("href", url);
    e.setAttribute("rel", "stylesheet");
    e.setAttribute("type", "text/css");
    d.head.appendChild(e);
};
