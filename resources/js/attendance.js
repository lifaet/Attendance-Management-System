document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.mark-btn');
    const alertBox = document.getElementById('attendance-alert');

    buttons.forEach(btn => {
        btn.addEventListener('click', async function() {
            const studentId = this.dataset.student;
            const status = this.dataset.status;
            const classId = window.Laravel?.classId || null;

            if (!classId) return;

            try {
                const res = await fetch(`/classes/${classId}/attendance`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ attendance: [{ student_id: studentId, status: status }] })
                });

                const data = await res.json();
                alertBox.classList.remove('hidden');
                if (res.ok) {
                    alertBox.classList.remove('bg-red-100', 'text-red-800');
                    alertBox.classList.add('bg-green-100', 'text-green-800');
                    alertBox.textContent = 'Marked ' + status + ' for ' + data.student_name;
                } else {
                    alertBox.classList.remove('bg-green-100', 'text-green-800');
                    alertBox.classList.add('bg-red-100', 'text-red-800');
                    alertBox.textContent = data.message || 'Error marking attendance';
                }

                setTimeout(() => alertBox.classList.add('hidden'), 3000);
            } catch (e) {
                console.error(e);
            }
        });
    });
});