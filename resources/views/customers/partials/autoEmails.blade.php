@if($isNewCustomer)
    <div>
        <div class="space-x-2">
            <i class="fas fa-exclamation-triangle text-[#6c757d] text-base"></i>
            <span class="text-[#6c757d] text-base">Auto Emails Information will be available after customer is saved.</span>
        </div>
    </div>
@else
<div>
    <h6 class="text-xl">Sent Auto Emails</h6>

    <div class="overflow-x-auto mt-4">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b-2 border-t-2 border-[#dee2e6]">
                    <th class="w-1/2 px-3 py-2 text-left font-bold">Auto Email</th>
                    <th class="w-1/2 px-3 py-2 text-left font-bold">Date</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4" class="text-center py-6">
                        No data available in table
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="flex justify-end text-sm text-gray-400 mt-4">
        Previous &nbsp;  &nbsp; Next
    </div>
</div>  
@endif  