<div data-control="kitchen-display-board" class="board d-flex gap-3 overflow-x-auto pb-3 flex-nowrap">
    @foreach($boardColumns as $boardColumn)
        @php($columnItems = $this->getColumnItems($boardColumn))
        <div class="board-column bg-light-subtle rounded shadow-sm p-2" style="flex: 0 0 300px;" data-status="new">
            <div class="fw-semibold mb-3 d-flex justify-content-between align-items-center">
                <span>{{ $boardColumn->label }}</span>
                <span class="board-counter badge" style="background-color: {{ $this->getBoardColumnColor((int)$boardColumn->statusId) }}">
                    {{ count($columnItems) }}
                </span>
            </div>

            <div class="order-list overflow-y-auto overflow-x-visible" style="height: calc(100vh - 230px);">
                @foreach($columnItems as $item)
                    {!! $this->makePartial('kitchendisplay/card', ['item' => $item]) !!}
                    {!! $this->makePartial('kitchendisplay/custom-time-modal', ['item' => $item]) !!}
                    {!! $this->makePartial('kitchendisplay/card-details-modal', ['item' => $item]) !!}
                @endforeach
            </div>
        </div>
    @endforeach
</div>
