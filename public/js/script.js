/**
 * TodoList JS
 */

/**
 * Show modal window for confirmation
 */
function deleteTasksListener() {
    var tasks = document.querySelectorAll("[data-delete-task]");
    tasks.forEach(
        (task) => {
        task.addEventListener("click", function(event) {
            event.preventDefault();

            let form = this.closest("form");

            let myModal = new bootstrap.Modal(document.querySelector("#modal-window"));
            myModal.show();

            let deleteButton = document.querySelector("#delete-task");

            deleteButton.addEventListener("click", function() {
                form.submit();
            });
        });
    });
}
