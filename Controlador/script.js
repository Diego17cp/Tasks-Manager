// Get HTML elements
const addForm = document.getElementById("addForm");
const tituloElement = document.getElementById("titulo");
const descripcionElement = document.getElementById("descripcion")
const fecha_limiteElement = document.getElementById("fecha")
const btnAddTask= document.getElementById("btnAdd");
const btnHideAddForm = document.getElementById("btnHideAddForm");
const tasks=document.querySelectorAll('.task');
const addTaskBtn = document.getElementById("addTaskBtn");
const editForm = document.getElementById("editForm");
const addTaskForm = document.getElementById("addTaskForm");
const updateTaskBtn = document.getElementById("updateTaskBtn");
// Functions
// Function for toggling the visibility of the add task form
const toggleVisibilityAddForm = () => {
    let isVisible=false
    if(isVisible){
        addForm.classList.toggle("show");
        addForm.classList.toggle("hidden");
    }
    else{
        addForm.classList.toggle("hidden");
        addForm.classList.toggle("show");
    }
}
// Function for toggling the visibility of the task details
const toggleTaskDetails = (e) => {
    const task = e.target.closest(".task");
    if(task){
        task.classList.toggle("expanded");
    }
}
// Function for adding a task
const addTask = async () => {
    const titulo = tituloElement.value;
    const descripcion = descripcionElement.value;
    const fecha_limite = fecha_limiteElement.value;

    const response = await fetch("../Controlador/agregar_tarea.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ titulo, descripcion, fecha_limite }),
    });
    const data = await response.json();
    if (data.success) {
        alert("Tarea añadida correctamente");
        location.reload();
    }
    else {
        alert("Error al añadir la tarea: " + (data.message || "Sin mensaje de error."));
    }
}
// Function for showing the edit form
const showEditForm = (tareaid) => {
    const editForm = document.getElementById("editForm");
    editForm.classList.add("show");
    editForm.classList.remove("hidden");
    const taskDiv=document.getElementById(`task-${tareaid}`);
    const titullo=taskDiv.querySelector('h3').textContent;
    const descripcion=taskDiv.querySelector('p').textContent;
    const fechaLimite=taskDiv.querySelector('p:nth-child(2)').textContent;
    const fechaFormatted=new Date(fechaLimite).toISOString().split('T')[0];

    document.getElementById("tareaid").value = tareaid;
    document.getElementById("editTitulo").value = titullo;
    document.getElementById("editDescripcion").value = descripcion;
    document.getElementById("editFecha").value = fechaFormatted;
    document.getElementById("editForm").style.display = "block";
}
// Function for hiding the edit form
const hideEditForm = () => {
    editForm.classList.remove("show");
    editForm.classList.add("hidden");
}
// Function for updating a task
const updateTask = async () => {
    const tareaid = document.getElementById("tareaid").value;
    const titulo = document.getElementById("editTitulo").value;
    const descripcion = document.getElementById("editDescripcion").value;
    const fechaLimite = document.getElementById("editFecha").value;
    const response = await fetch("../Controlador/actualizar_tarea.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ tareaid, titulo, descripcion, fechaLimite }),
    })
    const data = await response.json();
    if (data.success) {
        alert("Tarea actualizada correctamente");
        location.reload();
    }
    else {
        alert("Error al actualizar la tarea");
    }
}
// Function for marking a task as completed
const markAsCompleted = async (tareaid) => {
    const response = await fetch("../Controlador/actualizar_tarea.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({tareaid: tareaid, estado: "Completada"}),
    });
    const data = await response.json();
    if (data.success) {
        alert("Tarea marcada como completada");
        location.reload();
    }
    else {
        alert("Error al marcar la tarea como completada");
    }
}
// Function for generating a PDF of a task
const generatePDF = (tareaid, id) => {
    window.location.href=`../Controlador/generar_pdf.php?tareaid=${tareaid}&id=${id}`;
}
// Function for deleting a task
const deleteTask = (tareaid, button) => {
    const confirmacion = confirm("¿Estás seguro de que deseas eliminar la tarea?");
    if (confirmacion) {
        fetch(`../Controlador/eliminar_tarea.php`,{
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({tareaid: tareaid}),
        }).then(response => response.json()).then(data => {
            if (data.success) {
                const taskElement=button.closest('.task');
                taskElement.remove();
                alert("Tarea eliminada correctamente");
            }
            else{
                alert("Error al eliminar la tarea" + data.message);
            }
        })
        .catch(error => {
            console.error("Error al eliminar la tarea:", error);
            alert("Error al eliminar la tarea");
        });
    }
}
// Function for closing the session
const cerrarSesion = () => {
    sessionStorage.removeItem('usuarioid');
    sessionStorage.removeItem('username');
    window.location.href = '../Modelo/cerrar_sesion.php';
}
// Event listeners
btnAddTask.addEventListener("click", toggleVisibilityAddForm);
btnHideAddForm.addEventListener("click", toggleVisibilityAddForm);
addTaskBtn.addEventListener("click", addTask);
updateTaskBtn.addEventListener("click", updateTask);
tasks.forEach(task => {
    task.addEventListener('click', toggleTaskDetails);
});
document.addEventListener("input", (event) => {
    if (event.target.tagName === "TEXTAREA") {
        const textarea = event.target;
        textarea.style.height = "auto"; // Restablece altura inicial
        textarea.style.height = textarea.scrollHeight + "px"; // Ajusta según contenido
        const form = textarea.closest("form");
        if (form) {
            form.style.height = "auto"; // Permite que el formulario se ajuste dinámicamente
        }
    }
});
