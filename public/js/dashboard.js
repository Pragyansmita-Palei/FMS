document.addEventListener("DOMContentLoaded", function () {
    const chart = document.getElementById("projectProgress");
    const text = document.getElementById("progressText");
    const label = document.getElementById("progressLabel");

    if (!chart) return;

let done = parseFloat(chart.dataset.done) || 0;
let progress = parseFloat(chart.dataset.progress) || 0;
let pending = parseFloat(chart.dataset.pending) || 0;

// Normalize
const total = done + progress + pending;

if (total > 0) {
    done = (done / total) * 100;
    progress = (progress / total) * 100;
    pending = (pending / total) * 100;
}

// Round
done = Math.round(done);
progress = Math.round(progress);
pending = Math.round(pending);

// Convert % → degrees (semi-circle = 180°)
const doneDeg = (done / 100) * 180;
const progressDeg = (progress / 100) * 180;
const pendingDeg = (pending / 100) * 180;

// Correct gradient
chart.style.background = `conic-gradient(
    from 0deg at 50% 100%,
    #0b5c12bf 0deg ${doneDeg}deg,
    #1f7a4f ${doneDeg}deg ${doneDeg + progressDeg}deg,
    #0f4d2f ${doneDeg + progressDeg}deg ${doneDeg + progressDeg + pendingDeg}deg,
    #e0e0e0 ${doneDeg + progressDeg + pendingDeg}deg 180deg
)`;
    // Set initial text
    text.innerText = done + "%";
    label.innerText = "Completed";

    // Add click event to the chart
   chart.addEventListener("click", function (e) {
    const rect = chart.getBoundingClientRect();

    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;

    const centerX = rect.width / 2;
    const centerY = rect.height;

    let angle = Math.atan2(centerY - y, x - centerX);
    angle = angle * (180 / Math.PI);

    // FIX: proper conversion
    angle = 180 - angle;

    angle = Math.max(0, Math.min(180, angle));

    const doneAngle = done * 1.8;
    const progressAngle = progress * 1.8;
    const pendingAngle = pending * 1.8;

    if (angle <= doneAngle) {
        text.innerText = done + "%";
        label.innerText = "Completed";
    }
    else if (angle <= doneAngle + progressAngle) {
        text.innerText = progress + "%";
        label.innerText = "In Progress";
    }
    else if (angle <= doneAngle + progressAngle + pendingAngle) {
        text.innerText = pending + "%";
        label.innerText = "Pending";
    }
    else {
        text.innerText = "0%";
        label.innerText = "No Status";
    }
});

    // Optional: Add legend click handlers
    document.querySelectorAll('.legend-item').forEach((item, index) => {
        item.addEventListener('click', function() {
            if (index === 0) { // Completed
                text.innerText = done + "%";
                label.innerText = "Completed";
            } else if (index === 1) { // In Progress
                text.innerText = progress + "%";
                label.innerText = "In Progress";
            } else if (index === 2) { // Pending
                text.innerText = pending + "%";
                label.innerText = "Pending";
            }
        });
    });
});
$(document).ready(function () {

    $.get('/dashboard/daily-project-stats', function (data) {

        let html = '';

        data.forEach(function (item, index) {

            let barClass = "bar bar" + (index + 1);

            html += `
            <div class="bar-item">

                <div class="${barClass} bar-hover"
                     style="height:${item.height}px;"
                     data-percent="${item.percentage}%">
                </div>

                <div class="bar-label">${item.day}</div>
            </div>
            `;

        });

        $('.analytics-bars').html(html);

    });

});
