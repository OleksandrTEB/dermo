const modal = document.getElementById('modal');
const taskForm = document.getElementById('taskForm');
const tasksInfo = document.getElementById('tasksInfo');
const taskListContent = document.getElementById('taskListContent');
const modalDateInput = document.getElementById('modalDateInput');
const selectedDateLabel = document.getElementById('selectedDateLabel');

function openModal(day, fullDate, tasks, meeting) {
    modal.style.display = 'flex';
    modalDateInput.value = fullDate;
    selectedDateLabel.textContent = fullDate;

    if (tasks.length > 0 && meeting.length > 0) {
        showAll(meeting, tasks)
    } else if (meeting.length > 0) {
        showMeetig(meeting)
    } else if (tasks.length > 0) {
        showTasks(tasks);
    } else {
        showForm();
    }
}

function showTasks(tasks) {
    tasksInfo.style.display = 'flex';
    taskForm.style.display = 'none';

    taskListContent.innerHTML = '';
    tasks.forEach(t => {
        const div = document.createElement('div');
        div.classList.add('task-container');
        div.innerHTML = `<span class="task-text">${t.text}</span>`;
        taskListContent.appendChild(div);
    });
}

function showMeetig(meeting) {
    tasksInfo.style.display = 'flex';
    taskForm.style.display = 'none';

    taskListContent.innerHTML = '';
    meeting.forEach(t => {
        const div = document.createElement('div');
        div.classList.add('meeting-container');
        div.innerHTML = `<span class="task-text">${t.text}</span>`;
        taskListContent.appendChild(div);
    });
}

function showAll(meeting, tasks) {
    tasksInfo.style.display = 'flex';
    taskForm.style.display = 'none';

    taskListContent.innerHTML = '';
    meeting.forEach(t => {
        const div = document.createElement('div');
        div.classList.add('meeting-container');
        div.innerHTML = `<span class="task-text">${t.text}</span>`;
        taskListContent.appendChild(div);
    });
    
    tasks.forEach(t => {
        const div = document.createElement('div');
        div.classList.add('task-container');
        div.innerHTML = `<span class="task-text">${t.text}</span>`;
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
    if(e.target.closest('[data-role="exit"]')) {
        closeModal();
    }
});