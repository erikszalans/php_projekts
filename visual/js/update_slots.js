function updateTimeSlots() {
    const dateInput = document.getElementById('reservation_date');
    const timeSelect = document.getElementById('reservation_time');
    const selectedDate = dateInput.value;

    if (!selectedDate) {
        timeSelect.innerHTML = '<option value="">-- Izvēlies laiku --</option>';
        return;
    }

    fetch(`../views/fetch_times.php?date=${selectedDate}`)
        .then(response => response.json())
        .then(data => {
            timeSelect.innerHTML = '<option value="">-- Izvēlies laiku --</option>';
            if (data.availableTimes && data.availableTimes.length > 0) {
                data.availableTimes.forEach(time => {
                    const option = document.createElement('option');
                    option.value = time;
                    option.textContent = time;
                    timeSelect.appendChild(option);
                });
            } else {
                const noOptions = document.createElement('option');
                noOptions.value = '';
                noOptions.textContent = 'Nav pieejamu laiku';
                timeSelect.appendChild(noOptions);
            }
        })
        .catch(error => {
            console.error("Error fetching available times:", error);
        });
}
