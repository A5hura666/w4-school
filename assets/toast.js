document.addEventListener('DOMContentLoaded', function () {
    const toastElements = document.querySelectorAll('[id^="toast-"]');
    toastElements.forEach(toast => {
        setTimeout(() => {
            toast.classList.add('opacity-0');
            toast.classList.add('transition-opacity');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 5000);
    });
});
