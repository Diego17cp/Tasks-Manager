function showAddForm() {
    const addForm = document.getElementById("addForm");
    addForm.classList.add("show"); // Muestra el formulario
    addForm.classList.remove("hidden");
}

function hideAddForm() {
    const addForm = document.getElementById("addForm");
    addForm.classList.remove("show"); // Oculta el formulario
    addForm.classList.add("hidden");
}

const taskContainer = document.getElementById("taskContainer");
taskContainer.addEventListener("click", (e) => {
    const tarea = e.target.closest(".task");
    if (tarea) {
        const contenidoExpandido = tarea.querySelector(".contenidoExpandido");
        document.querySelectorAll(".task.expanded").forEach(task => {
            task.classList.remove("expanded");
        });

        // Si la tarea no estaba expandida, expándela
        if (!tarea.classList.contains("expanded")) {
            tarea.classList.add("expanded");
        }
    }
})

async function addTask() {
    const titulo = document.getElementById("titulo").value;
    const descripcion = document.getElementById("descripcion").value;
    const fecha_limite = document.getElementById("fecha").value;

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
function showEditForm(tareaid) {
    const editForm = document.getElementById("editForm");
    editForm.classList.add("show");
    editForm.classList.remove("hidden");
    const taskDiv=document.getElementById(`task-${tareaid}`);
    const titullo=taskDiv.querySelector('h3').textContent;
    const descripcion=taskDiv.querySelector('p').textContent;
    const fechaLimite=taskDiv.querySelector('p:nth-child(2)').textContent;

    document.getElementById("tareaid").value = tareaid;
    document.getElementById("editTitulo").value = titullo;
    document.getElementById("editDescripcion").value = descripcion;
    document.getElementById("editFecha").value = fechaLimite;
    document.getElementById("editForm").style.display = "block";
}
function hideEditForm() {
    const editForm = document.getElementById("editForm");
    editForm.classList.remove("show");
    editForm.classList.add("hidden");
}
async function updateTask() {
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
async function markAsCompleted(tareaid) {
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
function generatePDF(tareaid, id) {
    window.location.href=`../Controlador/generar_pdf.php?tareaid=${tareaid}&id=${id}`;
}
function deleteTask(tareaid, button) {
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
function cerrarSesion() {
    sessionStorage.removeItem('usuarioid');
    sessionStorage.removeItem('username');
    window.location.href = '../Modelo/cerrar_sesion.php';
}
