@if (isset($statements) && count($statements) > 0)
    <div class="stmtTableWrapper">
        <table class="stmtTable stmtSavedTable" id="stmtSavedTable">
            <thead>
                <tr>
                    <th>Statement</th>
                    <th>File</th>
                    <th>Rows</th>
                    <th>Linked</th>
                    <th>Total Commission</th>
                    <th>Uploaded</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @foreach ($statements as $statement)
                    @php
                        $statementId = (int)$statement->id;
                        $itemsCount = (int)$statement->itemsCount;
                        $linkedCount = (int)$statement->linkedCount;
                        $totalCommission = number_format((float)$statement->total_commission, 2);
                        $createdAt = date('m/d/Y', strtotime($statement->created_at));
                        $uploadedBy = trim((string)$statement->uploadedByName);

                        $isTextFile = strtolower(pathinfo($statement->original_file_name, PATHINFO_EXTENSION)) === 'txt';
                        $fileIcon = $isTextFile ? 'far fa-file-alt' : 'far fa-file-pdf';
                        $fileTitle = $isTextFile ? 'Open Text File' : 'Open PDF';

                        $linkedClass = '';
                        if ($itemsCount > 0 && $linkedCount == $itemsCount) {
                            $linkedClass = 'stmtLinked';
                        } elseif ($linkedCount > 0) {
                            $linkedClass = 'stmtPartiallyLinked';
                        }
                    @endphp

                    <tr>
                        <td class="stmtSavedName">{{ $statement->statement_name }}</td>
                        <td>
                            <a href="{{ route('statements.file', $statementId) }}" target="_blank" rel="noopener" class="stmtFileLink" data-toggle="tooltip" data-placement="bottom" title="{{ $fileTitle }}">
                                <i class="{{ $fileIcon }}"></i>
                                <span>{{ $statement->original_file_name }}</span>
                            </a>
                        </td>
                        <td>{{ $itemsCount }}</td>
                        <td><span class="stmtLinkedPill {{ $linkedClass }}">{{ $linkedCount }} / {{ $itemsCount }}</span></td>
                        <td>${{ $totalCommission }}</td>
                        <td>
                            {{ $createdAt }}
                            @if ($uploadedBy !== '')
                                <div class="stmtSubText">by {{ $uploadedBy }}</div>
                            @endif
                        </td>
                        <td class="stmtSavedActions">
                            <button type="button" class="stmtIconBtn stmtViewItemsBtn" data-statement-id="{{ $statementId }}" data-toggle="tooltip" data-placement="bottom" title="View Rows">
                                <i class="far fa-eye"></i>
                            </button>
                            <button type="button" class="stmtIconBtn stmtDeleteBtn" data-statement-id="{{ $statementId }}" data-statement-name="{{ $statement->statement_name }}" data-toggle="tooltip" data-placement="bottom" title="Delete Statement">
                                <i class="far fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="stmtEmptyState">
        <i class="far fa-check-circle"></i>
        <div>No unlinked statements.</div>
        <div class="stmtSubText">Every saved statement is attached to its reservations.</div>
    </div>
@endif