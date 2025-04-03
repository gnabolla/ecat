// Countdown timer
let totalSeconds;
const countdownElem = document.getElementById('countdown');
const hoursElem = document.getElementById('hours');
const minutesElem = document.getElementById('minutes');
const secondsElem = document.getElementById('seconds');

function initializeTimer(remainingTime) {
    totalSeconds = remainingTime;
    updateCountdown();
    // Update countdown every second
    setInterval(updateCountdown, 1000);
}

function updateCountdown() {
    if (totalSeconds <= 0) {
        // Time's up, submit the exam
        countdownElem.innerHTML = '<span style="color: #ffcdd2;">Time\'s Up! Submitting exam...</span>';
        document.querySelector('.button-submit').click();
        return;
    }
    
    const hours = Math.floor(totalSeconds / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);
    const seconds = totalSeconds % 60;
    
    hoursElem.textContent = hours;
    minutesElem.textContent = minutes.toString().padStart(2, '0');
    secondsElem.textContent = seconds.toString().padStart(2, '0');
    
    // Change color when less than 10 minutes remain
    if (totalSeconds < 600) {
        countdownElem.style.backgroundColor = '#f44336';
    }
    
    totalSeconds--;
}

// Form submission confirmation
document.addEventListener('DOMContentLoaded', function() {
    const submitButton = document.querySelector('.button-submit');
    if (submitButton) {
        submitButton.addEventListener('click', function(e) {
            const confirmed = confirm('Are you sure you want to submit your exam? You cannot change your answers after submission.');
            if (!confirmed) {
                e.preventDefault();
            }
        });
    }
});

// Set up the previous button action
document.addEventListener('DOMContentLoaded', function() {
    const prevButton = document.querySelector('.button-prev');
    if (prevButton) {
        prevButton.addEventListener('click', function() {
            document.getElementById('questionForm').elements.namedItem('next_action').value = 'prev';
        });
    }
});