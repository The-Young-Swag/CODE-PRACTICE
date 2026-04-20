<div id="listView">
    
</div>

<script>
    const tasks = document.getElementById("tasks");

    async function loadTasks() {
    const response = await fetch("ctrl_index.php",{
        method: "POST",
        body:"tasks"
    });

    if (response.ok){
        const data = await response.json();
        return data.result;
    }else{
        thro new Error(`Server error: ${response.status}`);
    }
        
    }
</script>