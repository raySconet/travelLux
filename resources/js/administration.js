import './bootstrap';
import Alpine from 'alpinejs';

window.dateDropdown = function(minYear = 1920, maxYear = 2040) {
    return {
        day: '',
        month: '',
        year: '',
        days: Array.from({ length: 31 }, (_, i) => i + 1),
        months: [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ],
        years: Array.from({ length: maxYear - minYear + 1 }, (_, i) => minYear + i),
        get formattedDate() {
            if (!this.day || !this.month || !this.year) return '';
            return `${String(this.month).padStart(2, '0')}/` +
            `${String(this.day).padStart(2, '0')}/` +
            `${this.year}`;
        }
    };
};

window.Alpine = Alpine;

Alpine.start();
$(document).ready(() => {

    // start forms manager
    $('#addRowBtn').on('click', function () {
        const row = `
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center">
                <div class="relative mt-3 md:col-span-2">
                    <label class="text-sm">Product</label>
                    <select class="w-full border-b-2 border-[#dee2e6] mb-4 focus:outline-none focus:border-[#f18325]">
                        <option value="-1">--Select product--</option>
                    </select>
                </div>

                <div class="relative mt-3 md:col-span-2">
                    <label class="text-sm">Destination</label>
                    <select class="w-full border-b-2 border-[#dee2e6] mb-4 focus:outline-none focus:border-[#f18325]">
                        <option value="-1">--Select destination--</option>
                    </select>
                </div>

                <button type="button"
                    class="text-[#bdbdbd] text-xl delete-row flex  mt-6">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        $('#productDestinationContainer').append(row);
    });

    $(document).on('click', '.delete-row', function () {
        $(this).closest('.grid').remove();
    });


    $(document).on('click', '.add-item', function (e) {
        e.preventDefault();
        let type = $(this).data('type');
        let html = '';
        if (type === 'title') {
            html = `
            <div class="form-item bg-white shadow rounded-lg p-6 mt-3" style="box-shadow: 1px 1px 6px #808080;">
                <p class="font-extrabold text-base mb-3">Title</p>
                <div class="relative mt-3">
                    <input type="text"  placeholder=" " class="peer w-full border-b focus:outline-none focus:border-[#f18325] pt-3 pb-1" />
                    <label class="absolute left-0 top-1 transition-all duration-200 peer-placeholder-shown:top-2  peer-focus:top-0 peer-focus:text-sm ">Text</label>
                </div>


                <div class="flex place-content-end gap-4 mt-6">
                    <button type="button"
                        class="text-[#000] text-xl delete-row2 flex  mt-3">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl flex  mt-3">
                        <i class="fas fa-arrow-down"></i>
                    </button>
                </div>
            </div>`;
        }else if(type === 'acknowledgement'){
            html = `
            <div class="form-item bg-white shadow rounded-lg p-6 mt-3" style="box-shadow: 1px 1px 6px #808080;">
                <p class="font-extrabold text-base mb-3">Acknowledgement (Checkbox)</p>
                <div class="relative mt-3">
                    <input type="text" placeholder=" " class="peer w-full border-b focus:outline-none focus:border-[#f18325] pt-3 pb-1 mb-5" />
                    <label class="absolute left-0 top-1 transition-all duration-200 peer-placeholder-shown:top-2  peer-focus:top-0 peer-focus:text-sm">Text</label>
                </div>

                <div>
                    <input type="checkbox" for="acknowledgement">
                    <label for="acknowledgement">Required</label>
                </div>

                <div class="flex place-content-end gap-4 mt-6">
                    <button type="button"
                        class="text-[#000] text-xl delete-row2 flex  mt-3">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-down"></i>
                    </button>
                </div>
            </div>`;
        }else if(type === 'plainTextBlock'){
            html = `
            <div class="form-item bg-white shadow rounded-lg p-6 mt-3" style="box-shadow: 1px 1px 6px #808080;">
                <p class="font-extrabold text-base mb-3">Plain Text Block</p>
                <div class="relative mt-3">
                    <input type="text" placeholder=" " class="peer w-full border-b focus:outline-none focus:border-[#f18325] pt-3 pb-1" />
                    <label class="absolute left-0 top-1 transition-all duration-200 peer-placeholder-shown:top-2  peer-focus:top-0 peer-focus:text-sm ">Text</label>
                </div>

                <div class="flex place-content-end gap-4 mt-6">
                    <button type="button"
                        class="text-[#000] text-xl delete-row2 flex  mt-3">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-down"></i>
                    </button>
                </div>
            </div>`;
        }else if(type === 'customerInputBlock'){
            html = `
            <div class="form-item bg-white shadow rounded-lg p-6 mt-3" style="box-shadow: 1px 1px 6px #808080;">
                <p class="font-extrabold text-base mb-3">Text Input</p>
                <div class="relative mt-3">
                    <input type="text" placeholder=" " class="peer w-full border-b focus:outline-none focus:border-[#f18325] pt-3 pb-1 mb-5" />
                    <label class="absolute left-0 top-1 transition-all duration-200 peer-placeholder-shown:top-2  peer-focus:top-0 peer-focus:text-sm">Text</label>
                </div>

                <div>
                    <input type="checkbox" for="customerInputBlockRequired">
                    <label for="customerInputBlockRequired">Required</label>
                </div>

                <div class="flex place-content-end gap-4 mt-6">
                    <button type="button"
                        class="text-[#000] text-xl delete-row2 flex  mt-3">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-down"></i>
                    </button>
                </div>
            </div>`;
        }else if(type === 'customerDeclaration'){
            html = `
            <div class="form-item bg-white shadow rounded-lg p-6 mt-3" style="box-shadow: 1px 1px 6px #808080;">
                <p class="font-extrabold text-base mb-3">Customer Declaration</p>
                <div>
                    <input type="checkbox" for="customerDeclarationRequired">
                    <label for="customerDeclarationRequired">Required</label>
                </div>

                <div class="flex place-content-end gap-4 mt-6">
                    <button type="button"
                        class="text-[#000] text-xl delete-row2 flex  mt-3">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-down"></i>
                    </button>
                </div>
            </div>`;
        }else if(type === 'choice'){
            html = `
            <div class="form-item bg-white shadow rounded-lg p-6 mt-3" style="box-shadow: 1px 1px 6px #808080;">
                <p class="font-extrabold text-base mb-3">Choice (Radio Buttons)</p>
                <div>
                    <input type="checkbox" for="requiredChoice">
                    <label for="requiredChoice">Required</label>
                </div>

                <div>
                    <input type="checkbox" for="displayInRowFormat">
                    <label for="displayInRowFormat">Display In Row Format</label>
                </div>

                <div class="choiceText flex gap-4 relative mt-3">
                    <div class=" flex-1 relative">
                        <input type="text" placeholder=" " class="peer w-full border-b focus:outline-none focus:border-[#f18325] pt-3 pb-1 mb-5 w-full" />
                        <label class="absolute left-0 top-1 transition-all duration-200 peer-placeholder-shown:top-2  peer-focus:top-0 peer-focus:text-sm">Choice Text</label>
                    </div>
                    <button type="button"
                        class="text-[#bdbdbd] text-xl delete-row3 flex  mt-3">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <button type="button" class="addChoiceBtn space-x-2 bg-[#bdbdbd] border text-white py-2 px-8 rounded cursor-pointer  hover:bg-white hover:border-[#bdbdbd] hover:text-[#bdbdbd]
                    transition-all duration-200 mt-3"><i class="fas fa-plus"></i>Add Choice</button>

                <div class="flex place-content-end gap-4 mt-6">
                    <button type="button"
                        class="text-[#000] text-xl delete-row2 flex  mt-3">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-down"></i>
                    </button>
                </div>

            </div>
            <div class="addChoice mt-4 space-y-3"></div>`;
        }else if(type === 'dropdown'){
            html = `
            <div class="form-item bg-white shadow rounded-lg p-6 mt-3" style="box-shadow: 1px 1px 6px #808080;">
                <p class="font-extrabold text-base mb-3">Dropdown</p>
                <div class="relative mt-3">
                    <input type="text" placeholder=" " class="peer w-full border-b focus:outline-none focus:border-[#f18325] pt-3 pb-1 mb-5" />
                    <label class="absolute left-0 top-1 transition-all duration-200 peer-placeholder-shown:top-2  peer-focus:top-0 peer-focus:text-sm">Dropdown Label</label>
                </div>

                <div>
                    <input type="checkbox" for="requiredChoice">
                    <label for="requiredChoice">Required</label>
                </div>

                <div class="choiceText flex gap-4 relative mt-3">
                    <div class="relative flex-1">
                        <input type="text" placeholder=" " class="peer w-full border-b focus:outline-none focus:border-[#f18325] pt-3 pb-1 mb-5 w-full" />
                        <label class="absolute left-0 top-1 transition-all duration-200 peer-placeholder-shown:top-2  peer-focus:top-0 peer-focus:text-sm">Choice Text</label>
                    </div>
                    <button type="button"
                        class="text-[#bdbdbd] text-xl delete-row3 flex  mt-3">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <button  type="button" class="addChoiceBtn space-x-2 bg-[#bdbdbd] border text-white py-2 px-8 rounded cursor-pointer  hover:bg-white hover:border-[#bdbdbd] hover:text-[#bdbdbd]
                    transition-all duration-200 mt-3"><i class="fas fa-plus"></i>Add Choice</button>

                <div class="flex place-content-end gap-4 mt-6">
                    <button type="button"
                        class="text-[#000] text-xl delete-row2 flex  mt-3">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl flex  mt-3">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-down"></i>
                    </button>
                </div>
            </div>
            <div class="addChoice mt-4 space-y-3"></div>`;
        }else if(type === 'signatureBlock'){
            html = `
            <div class="form-item bg-white shadow rounded-lg p-6 mt-3" style="box-shadow: 1px 1px 6px #808080;">
                <p class="font-extrabold text-base mb-3">Signature Block</p>
                <div>
                    <input type="checkbox" for="signatureBlockRequired">
                    <label for="signatureBlockRequired">Required</label>
                </div>

                <div class="flex place-content-end gap-4 mt-6">
                    <button type="button"
                        class="text-[#000] text-xl delete-row2 flex  mt-3">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-down"></i>
                    </button>
                </div>
            </div>`;
        }else if(type === 'HTML'){
            html = `
            <div class="flex-item bg-white shadow rounded-lg p-6 mt-1" style="box-shadow: 1px 1px 6px #808080;">
                <p class="font-extrabold text-base mb-3">HTML</p>
                <label class="text-sm">Content</label>
                <textarea
                    id="content"
                    name="content"
                    rows="3"
                    class="w-full border-b border-[#bdbdbd] focus:outline-none focus:border-[#f18325] resize-none pt-1 pb-1">
                </textarea>

                <div class="flex place-content-end gap-4 mt-6">
                    <button type="button"
                        class="text-[#000] text-xl delete-row2 flex  mt-3">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl  flex  mt-3">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button type="button"
                        class="text-[#000] text-xl flex  mt-3">
                        <i class="fas fa-arrow-down"></i>
                    </button>
                </div>
            </div>`;
        }
        $('#form-items-container').append(html);
        const $dropdown = $(this).closest('el-dropdown');
        const $menu = $dropdown.find('el-menu[popover]');
        $menu.attr('data-closed', 'true');
    });

    $(document).on('click', '.addChoiceBtn', function () {
        const choiceRow = `
            <div class="choiceText flex gap-4 relative mt-3">
                <div class="flex-1 relative">
                    <input
                        type="text"
                        placeholder=" "
                        class="peer w-full border-b focus:outline-none focus:border-[#f18325] pt-3 pb-1"
                    />
                    <label
                        class="absolute left-0 top-1 transition-all duration-200
                            peer-placeholder-shown:top-2
                            peer-focus:top-0 peer-focus:text-sm">
                        Choice Text
                    </label>
                </div>

                <button type="button"
                    class="text-[#bdbdbd] text-xl delete-row3 mt-3">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;

        $(this).before(choiceRow);
    });


    $(document).on('click', '.delete-row2', function () {
        const $item = $(this).closest('div.bg-white');
        $item.remove();
    });

    $(document).on('click', '.delete-row3', function () {
        const $item = $(this).closest('div.choiceText');
        $item.remove();
    });

    $(document).on('click', '.fa-arrow-up', function () {
        const $item = $(this).closest('.form-item');
        const $prev = $item.prev('.form-item');
        if ($prev.length) {
            $item.insertBefore($prev);
        }
    });


    $(document).on('click', '.fa-arrow-down', function () {
        const $item = $(this).closest('.form-item');
        const $next = $item.next('.form-item');
        if ($next.length) {
            $item.insertAfter($next);
        }
    });

    // end forms manager

});
