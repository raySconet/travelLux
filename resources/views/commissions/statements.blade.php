<x-app-layout>
    <x-slot name="header">
        <div class="py-4 px-4 bg-white shadow sm:rounded-lg flex items-center justify-between">
            <h2 class="text-xl text-gray-500 leading-tight flex items-baseline">
                <i class="fas fa-envelope-open-text mr-2 text-[#B6844A]"></i>
                <span>Statements</span>
            </h2>
        </div>
    </x-slot>

    @include('commissions.statementsContainer')

    <script>
        window.STMT_CONFIG = {
            csrf: @json(csrf_token()),
            routes: {
                list:   @json(route('statements.list')),
                save:   @json(route('statements.save')),
                items:  @json(route('statements.items')),
                delete: @json(route('statements.delete'))
            }
        };
    </script>
</x-app-layout>