const modal = document.getElementById('modal');
const taskForm = document.getElementById('taskForm');
const tasksInfo = document.getElementById('tasksInfo');
const taskListContent = document.getElementById('taskListContent');
const modalDateInput = document.getElementById('modalDateInput');
const selectedDateLabel = document.getElementById('selectedDateLabel');
const czas = document.querySelector('.czas');
const btn = document.querySelector('[data-role="exit"]');
const btnAdd = document.querySelector('[data-role="add"]');
const daysContainer = document.querySelector('.days-container');
const type = document.getElementById('type');

daysContainer.addEventListener('click', (e) => {
    const el = e.target.closest('.day-container');
    if (!el) return;

    const date = el.dataset.date;
    const terms = JSON.parse(el.dataset.terms);

    openModal(date, terms);
});

if (btn) {
    btn.addEventListener('click', closeModal);
}

if (btnAdd) {
    btnAdd.addEventListener('click', showForm);
}

function openModal(fullDate, terms) {
    modal.style.display = 'flex';
    modalDateInput.value = fullDate;
    selectedDateLabel.textContent = fullDate;

    if (terms.length > 0) {
        showTerms(terms);
    } else {
        showForm();
    }
}

function showTerms(terms) {
    tasksInfo.style.display = 'flex';
    taskForm.style.display = 'none';

    taskListContent.innerHTML = '';

    terms.forEach(t => {
        const div = document.createElement('div');

        if (t.time && t.time !== "00:00:00") {
            div.classList.add('meeting-container');

            const time = t.time.slice(0, 5);
            div.textContent = `[${time}] ${t.text}`;
        } else {
            div.classList.add('task-container');
            div.textContent = t.text;
        }

        taskListContent.appendChild(div);
    });
}

function showForm() {
    tasksInfo.style.display = 'none';
    taskForm.style.display = 'flex';
}

function closeModal() {
    modal.style.display = 'none';
    taskForm.reset();
}

modal.addEventListener('click', (e) => {
    if (e.target.closest('[data-role="exit"]')) {
        closeModal();
    }
});

type.addEventListener('change', (e) => {
    if (e.target.value === "zadanie") {
        czas.style.display = "none";
    } else {
        czas.style.display = "block";
    }
});