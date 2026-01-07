<div>
    <label class="text-base">Form Items</label>

    <div class="form-item bg-white shadow rounded-lg p-6 mt-1" style="box-shadow: 1px 1px 6px #808080;">
        <p class="font-extrabold text-base mb-3">HTML</p>
        <label class="text-sm">Content</label>
        <textarea
            id="content"
            name="content"
            rows="3"
            class="w-full border-b border-[#bdbdbd] focus:outline-none focus:border-[#f18325] resize-none pt-1 pb-1">
        </textarea>
    </div>

    <div id="form-items-container" class="space-y-4"></div>
    <el-dropdown class="inline-block">
        <button class="bg-[#6c757d] text-white mt-3 flex items-center gap-2 py-1 px-2 mb-3">
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
</div>