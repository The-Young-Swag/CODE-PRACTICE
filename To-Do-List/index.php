<div id="TaskHeader">
    <input type="text" id="taskInput" placeholder="Enter task">
    <button id="addBtn">Add</button>
</div>

<div id="tasks"></div>

<script>
const taskList = document.getElementById("tasks");
const taskInput = document.getElementById("taskInput");
const addBtn = document.getElementById("addBtn");

// ✅ STATE (you missed this)
let state = {
    tasks: []
};

// ✅ RENDER (only reads from state)
function renderTasks() {
    taskList.innerHTML = "";

    state.tasks.forEach(task => {
        const div = document.createElement("div");
        div.textContent = task.listContent;
        taskList.appendChild(div);
    });
}

// 📥 LOAD
async function loadTasks() {
    try {
        const response = await fetch("ctrl_index.php");

        if (!response.ok) {
            throw new Error(`Server error: ${response.status}`);
        }

        const data = await response.json();

        state.tasks = data;   // update state
        renderTasks();        // render from state

    } catch (error) {
        console.error("Failed to load tasks:", error);
    }
}

// ➕ CREATE
async function createTask() {
    if (!taskInput.value.trim()) return; // ✅ prevent empty

    const fd = new FormData();
    fd.append("task", taskInput.value);

    await fetch("ctrl_index.php", {
        method: "POST",
        body: fd
    });

    taskInput.value = ""; // clear input

    await loadTasks(); // 🔥 server-driven sync
}

async function deleteTask(id) {
    try {
        const response = await fetch("ctrl_index.php", {
            method: "POST",
            body: new URLSearchParams({
                action: "delete",
                id: id
            })
        });

        if (!response.ok) {
            throw new Error(`Server error: ${response.status}`);
        }

        await loadTasks(); // 🔥 re-sync from DB (correct pattern)

    } catch (error) {
        console.error("Failed to delete task:", error);
    }
}

// 🎯 EVENT
addBtn.addEventListener("click", createTask);

// 🚀 INIT
loadTasks();
</script>