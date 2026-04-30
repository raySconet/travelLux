<div class="flex flex-col">
    <label class="text-base">Form Items</label>

    <div id="form-items-container" class="space-y-4"></div>
    <el-dropdown class="inline-block">
        <button class="bg-[#6c757d] text-white mt-3 flex items-center gap-2 py-1 px-2 mb-3 rounded">
            <i class="fas fa-plus"></i>Add Form Item
        </button>

        <el-menu anchor="bottom right" popover class="w-66 origin-top-right rounded-md bg-white shadow-lg outline-1 outline-black/5 transition transition-discrete translate-x-17 data-closed:scale-95 data-closed:transform data-closed:opacity-0 data-enter:duration-100 data-enter:ease-out data-leave:duration-75 data-leave:ease-in">
            <div class="py-0.25">
                <a data-type="title" class="add-item block px-3 py-1 text-sm text-gray-700 hover:bg-gray-100">Title</a>
                <a data-type="acknowledgement" class="add-item block px-3 py-1 text-sm text-gray-700 hover:bg-gray-100">Acknowledgement (Checkbox)</a>
                <a data-type="plainTextBlock" class="add-item block px-3 py-1 text-sm text-gray-700 hover:bg-gray-100">Plain Text Block</a>
                <a data-type="customerInputBlock" class="add-item block px-3 py-1 text-sm text-gray-700 hover:bg-gray-100">Customer Input Box</a>
                <a data-type="customerDeclaration" class="add-item block px-3 py-1 text-sm text-gray-700 hover:bg-gray-100">Customer Declaration(Name/Date)</a>
                <a data-type="choice" class="add-item block px-3 py-1 text-sm text-gray-700 hover:bg-gray-100">Choice (Radio Buttons)</a>
                <a data-type="dropdown" class="add-item block px-3 py-1 text-sm text-gray-700 hover:bg-gray-100">Dropdown</a>
                <a data-type="signatureBlock" class="add-item block px-3 py-1 text-sm text-gray-700 hover:bg-gray-100">Signature Block</a>
                <a data-type="HTML" class="add-item block px-3 py-1 text-sm text-gray-700 hover:bg-gray-100">HTML</a>
            </div>
        </el-menu>
    </el-dropdown>

    <label class="text-base mt-2">Form Preview (Read-only)</label>

    <div class="rounded-none bg-[#fff] flex px-5 py-2 mt-4" style="box-shadow: 1px 1px 6px #808080;">
        <button class="space-x-2 mt-2 mb-2 w-full py-2 bg-[#bdbdbd] text-white rounded cursor-pointer border border-transparent hover:bg-white hover:border-[#bdbdbd] hover:text-[#bdbdbd] transition-all duration-200">
            <i class="fas fa-paper-plane"></i>
            <span>Submit</span>
        </button>
    </div>
</div>