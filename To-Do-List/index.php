<div id="tasks">
    
</div>

<script>
    
    const taskList = document.getElementById("tasks");
    const taskInput = document.querySelector("#taskInput");
    const addBtn = document.querySelector("addBtn");



    //Load Tasks
    async function loadTasks() {
        const response = await fetch("ctrl_index.php");

        if (response.ok){
            const data = await response.json();
            
            data.forEach(task => {
                const div = document.createElement("div");
                div.textContent = task.listContent;
                taskList.appendChild(div);
            });

        } else{
            throw new Error(`Server error: ${response.status}`);
        }
    }

    loadTasks();

    //Create
    async function loadTasks(){
        const response = await fetch("ctrl_index.php",{
        method: "POST",
        body: formData
        });


    }


</script>