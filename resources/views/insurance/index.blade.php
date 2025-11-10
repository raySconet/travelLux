<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Insurance List') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <h2 class="text-2xl font-bold mb-4 text-gray-700">Insurance List</h2>


        <x-primary-btn id="addInsuranceBtn" class="">{{ __('Add Insurance') }}</x-primary-btn>

        <table class="min-w-full border mt-4">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 border">Insurance Name</th>
                    <th class="p-2 border">Claim #</th>
                    <th class="p-2 border">BI Adjuster</th>
                    <th class="p-2 border">Tel</th>
                    <th class="p-2 border">Email</th>
                    <th class="p-2 border">Fax</th>
                    <th class="p-2 border">Action</th>
                </tr>
            </thead>
            <tbody id="insuranceTable">
                @foreach($insurances as $insurance)
                <tr>
                    <td class="p-2 border">{{ $insurance->insurance_name }}</td>
                    <td class="p-2 border">{{ $insurance->claim_number }}</td>
                    <td class="p-2 border">{{ $insurance->bi_adjuster }}</td>
                    <td class="p-2 border">{{ $insurance->tel }}</td>
                    <td class="p-2 border">{{ $insurance->email }}</td>
                    <td class="p-2 border">{{ $insurance->fax }}</td>
                   <td class="p-2 border flex gap-2">
                        <button class="editBtn inline-block bg-yellow-600  text-black px-2 py-1 rounded cursor-pointer"
                            data-id="{{ $insurance->id }}">Edit</button>
                        <button class="deleteBtn inline-block bg-red-600  text-black px-2 py-1 rounded cursor-pointer"
                            data-id="{{ $insurance->id }}">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div id="insuranceModal"
        class="hidden fixed inset-0 bg-black-100/30 bg-opacity-10 flex items-center justify-center">
        <div class="bg-white p-6 rounded shadow-lg w-[400px]">
            <h3 class="text-xl mb-4" id="modalTitle">Add Insurance</h3>

            <form id="insuranceForm" method="POST" action="{{ route('insurance.store') }}">
                @csrf
                <input type="hidden" id="insuranceId" name="id">

                <div class="mb-2">
                    <label>Insurance Name</label>
                    <input type="text" name="insurance_name" class="w-full border p-2">
                </div>

                <div class="mb-2">
                    <label>Claim #</label>
                    <input type="text" name="claim_number" class="w-full border p-2">
                </div>

                <div class="mb-2">
                    <label>BI Adjuster</label>
                    <input type="text" name="bi_adjuster" class="w-full border p-2">
                </div>

                <div class="mb-2">
                    <label>Tel</label>
                    <input type="text" name="tel" class="w-full border p-2">
                </div>

                <div class="mb-2">
                    <label>Email</label>
                    <input type="email" name="email" class="w-full border p-2">
                </div>

                <div class="mb-2">
                    <label>Fax</label>
                    <input type="text" name="fax" class="w-full border p-2">
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" id="closeModal" class="bg-red-600  text-white px-3 py-1 rounded">Cancel</button>
                    <x-primary-btn type="submit" class="">{{ __('Save') }}</x-primary-btn>

                </div>
            </form>
        </div>
    </div>

    <script>

    </script>
</x-app-layout>
