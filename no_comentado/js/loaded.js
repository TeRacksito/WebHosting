document.addEventListener('DOMContentLoaded', function () {
    window.addEventListener('load', function () {
        let deliveryType = performance.getEntriesByType("navigation")[0].deliveryType;
        if (deliveryType === "cache") {
            showPageFast();
        } else {
            this.setTimeout(showPage, 2000);
        }
    });
});

function showPage() {
    window.scrollTo(0, 0);
    let loading = document.querySelector('.loading');
    
    loading.setAttribute('hidden', '');

    setTimeout(() => {
        loading.remove();
    }, 200);
}

function showPageFast() {
    window.scrollTo(0, 0);
    let loading = document.querySelector('.loading');
    let spinner = document.querySelector('.spinner');
    
    spinner.remove();
    loading.setAttribute('hidden', '');

    setTimeout(() => {
        loading.remove();
    }, 200);
}