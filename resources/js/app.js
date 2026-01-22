import './bootstrap';

document.addEventListener("DOMContentLoaded", function () {
    var countdownElement = document.getElementById('retry-countdown');
    var seconds = parseInt(countdownElement.innerText);

    var timer = setInterval(function () {
        seconds--;

        if (seconds <= 0) {
            clearInterval(timer);
            countdownElement.innerText = "0";

            countdownElement.parentElement.innerHTML = "You can send your message now.";
        } else {
            countdownElement.innerText = seconds;
        }
    }, 1000);
});
