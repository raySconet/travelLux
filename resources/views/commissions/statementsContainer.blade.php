<div class="statementsContainer mx-auto py-2 px-4">

    <div class="stmtCard stmtUploadCard">
        <div class="stmtCardTitle">
            <div>
                <i class="fas fa-file-upload"></i>
                <span>Upload Statement</span>
            </div>
        </div>

        <input type="file" id="stmtFileInput" accept=".pdf,.txt,application/pdf,text/plain" hidden />

        <div class="stmtDropzone" id="stmtDropzone" tabindex="0" role="button" aria-label="Upload PDF or TXT statement">
            <div class="stmtDropzoneIcon"><i class="fas fa-cloud-upload-alt"></i></div>
            <div class="stmtDropzoneText">Drag &amp; drop a PDF or TXT statement here</div>
            <div class="stmtDropzoneOr">or</div>
            <x-primary-btn type="button" id="stmtBrowseBtn">
                <i class="far fa-folder-open"></i>
                <span>Browse Files</span>
            </x-primary-btn>
            <div class="stmtDropzoneHint">PDF or TXT &middot; Max 20 MB</div>
        </div>

        <div class="stmtFileInfo" id="stmtFileInfo" style="display:none;">
            <div class="stmtFileIcon" id="stmtFileIcon"><i class="far fa-file-pdf"></i></div>
            <div class="stmtFileDetails">
                <div class="stmtFileName" id="stmtFileName"></div>
                <div class="stmtFileMeta" id="stmtFileMeta"></div>
            </div>
            <div class="stmtFileStatus" id="stmtFileStatus"></div>
            <button type="button" class="stmtIconBtn stmtFileRemoveBtn" id="stmtFileRemoveBtn" title="Remove File">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <div class="stmtCard stmtResultsCard" id="stmtResultsCard" style="display:none;">
        <div class="stmtCardTitle">
            <div>
                <i class="fas fa-table"></i>
                <span>Scanned Results</span>
                <span class="stmtBadge" id="stmtScanBadge"></span>
            </div>
            <div class="stmtResultsTotal">
                Total Commission: <strong id="stmtTotalCommission">$0.00</strong>
            </div>
        </div>

        <div class="stmtScanNotice" id="stmtScanNotice" style="display:none;">
            <i class="fas fa-info-circle"></i>
            <span id="stmtScanNoticeText"></span>
        </div>

        <div class="w-full lg:w-1/2 mb-4">
            <label for="stmtStatementName" class="block text-sm font-medium text-[#696969] mb-1">Statement Name</label>
            <input type="text" id="stmtStatementName" name="stmtStatementName" maxlength="255" value=""
                   class="w-full border-0 border-b-2 border-[#bdbdbd] text-sm px-1 py-2 focus:outline-none focus:border-[#B6844A]" />
            <div id="stmtStatementNameErrorMsg" class="errorMessage text-sm text-red-600 mt-1"></div>
        </div>

        <div class="stmtTableWrapper">
            <table class="stmtTable stmtEditTable" id="stmtEditTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Reservation Number</th>
                        <th>Client Name</th>
                        <th>Group Number</th>
                        <th>Agency Commission</th>
                        <th>Check Out Date</th>
                        <th>Hotel Name</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody id="stmtEditTableBody">

                </tbody>
            </table>
        </div>

        <div class="stmtResultsButtons">
            <x-secondary-btn type="button" id="stmtAddRowBtn">
                <i class="far fa-plus-square"></i>
                <span>Add Row</span>
            </x-secondary-btn>

            <div>
                <x-secondary-btn type="button" id="stmtClearBtn">
                    <i class="far fa-times-circle"></i>
                    <span>Clear</span>
                </x-secondary-btn>

                <x-primary-btn type="button" id="stmtSaveBtn">
                    <i class="fas fa-save"></i>
                    <span>Save Statement</span>
                </x-primary-btn>
            </div>
        </div>
    </div>

    <div class="stmtCard stmtSavedCard">
        <div class="stmtCardTitle">
            <div>
                <i class="fas fa-archive"></i>
                <span>Saved Statements</span>
            </div>
        </div>

        <div class="stmtFlashMessage" id="stmtFlashMessage" style="display:none;"></div>

        <div class="stmtSavedListContainer" id="stmtSavedListContainer">
            @include('commissions.statementsList')
        </div>
    </div>
</div>

<!-- Statement Rows Modal -->
<div id="stmtItemsModal" class="stmtModal fixed inset-0 bg-black/50 hidden items-center justify-center z-[9999] p-4" role="dialog" aria-modal="true">
    <div class="bg-white w-full max-w-6xl rounded-lg shadow-lg relative max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between px-3 py-4 border-b-2 border-[#dee2e6]">
            <div class="flex items-center space-x-2">
                <i class="fas fa-envelope-open-text text-[#B6844A] text-xl"></i>
                <h2 class="text-xl" id="stmtItemsModalTitle">Statement</h2>
            </div>

            <button type="button" class="stmtModalClose text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
        </div>

        <div class="px-4 py-4 overflow-y-auto">
            <div class="stmtModalMeta">
                <div>File: <a href="javascript:void(0);" id="stmtItemsModalFileLink" target="_blank" rel="noopener"></a></div>
                <div>Uploaded: <strong id="stmtItemsModalUploaded"></strong></div>
                <div>Rows: <strong id="stmtItemsModalCount"></strong></div>
                <div>Total Commission: <strong id="stmtItemsModalTotal"></strong></div>
            </div>

            <div class="stmtItemsTableWrapper">
                <table class="stmtTable stmtItemsTable">
                    <thead>
                        <tr>
                            <th>Reservation Number</th>
                            <th>Client Name</th>
                            <th>Group Number</th>
                            <th>Agency Commission</th>
                            <th>Check Out Date</th>
                            <th>Hotel Name</th>
                            <th>Reservation</th>
                        </tr>
                    </thead>

                    <tbody id="stmtItemsModalTableBody">

                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end px-6 py-4 border-t-2 border-[#dee2e6]">
            <button type="button" class="stmtModalClose space-x-2 bg-[#bdbdbd] border text-white py-2 px-8 rounded cursor-pointer  hover:bg-white hover:border-[#bdbdbd] hover:text-[#bdbdbd] transition-all duration-200">
                <i class="fa fa-times-circle"></i>
                <span>Close</span>
            </button>
        </div>
    </div>
</div>

<!-- Delete Statement Modal -->
<div id="stmtDeleteModal" class="stmtModal fixed inset-0 bg-black/50 hidden items-center justify-center z-[9999] p-4" role="dialog" aria-modal="true">
    <div class="bg-white w-full max-w-lg rounded-lg shadow-lg relative">
        <div class="flex items-center justify-between px-3 py-4 border-b-2 border-[#dee2e6]">
            <div class="flex items-center space-x-2">
                <i class="far fa-trash-alt text-[#B6844A] text-xl"></i>
                <h2 class="text-xl">Delete Statement</h2>
            </div>

            <button type="button" class="stmtModalClose text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
        </div>

        <div class="px-4 py-4">
            <p class="mb-1">Are you sure you want to delete <strong id="stmtDeleteName"></strong>?</p>
            <p class="stmtMutedText mb-0">Its rows will no longer be available to attach to reservations.</p>
        </div>

        <div class="flex justify-end gap-2 px-6 py-4 border-t-2 border-[#dee2e6]">
            <x-primary-btn type="button" id="stmtConfirmDeleteBtn">
                <i class="far fa-trash-alt"></i>
                <span>Delete</span>
            </x-primary-btn>

            <button type="button" class="stmtModalClose space-x-2 bg-[#bdbdbd] border text-white py-2 px-8 rounded cursor-pointer  hover:bg-white hover:border-[#bdbdbd] hover:text-[#bdbdbd] transition-all duration-200">
                <i class="fa fa-times-circle"></i>
                <span>Cancel</span>
            </button
        </div>
    </div>
</div>