<section class="mt-1">
    <h2 class="text-xl font-medium text-center text-gray-900">
        {{ __('To Do') }}
    </h2>
    <p>
        <b><?php echo date('m/d/Y'); ?></b>
    </p>

    <div id="displayTodayTodosHere">
    </div>

    <div class=" mt-3 text-green-800">
        <p class="mt-1 text-md"><b>ToDo Notebox:</b></p>
        <textarea name="displayTodoNoteHere" id="displayTodoNoteHere" placeholder="Todo Note Box"  rows="6" style="width:100%;"></textarea>
    </div>

</section>
