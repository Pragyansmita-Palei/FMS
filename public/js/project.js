
document.querySelectorAll('.step-tabs li').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelector('.step-tabs li.active')?.classList.remove('active');
        tab.classList.add('active');
    });
});

// document.addEventListener('DOMContentLoaded', function () {

//     document.querySelectorAll('.project-status-select').forEach(select => {

//         select.addEventListener('change', function () {

//             const projectId = this.dataset.projectId;
//             const status    = this.value;

//             fetch(`/projects/${projectId}/status`, {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/json',
//                     'X-CSRF-TOKEN': '{{ csrf_token() }}'
//                 },
//                 body: JSON.stringify({ status })
//             })
//             .then(res => res.json())
//             .then(data => {
//                 if (!data.success) {
//                     alert('Failed to update status');
//                 }
//             })
//             .catch(() => alert('Failed to update status'));

//         });

//     });

// });

