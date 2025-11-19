<div class="d-flex justify-content-between p-3">
    @if($previousUrl = AdminMenu::getPreviousUrl())
        <a
            class="btn shadow-none border-none ps-0"
            href="{{$previousUrl}}"
        ><i class="fa fa-angle-left fs-4 align-bottom"></i></a>
    @endif
    <h4 class="page-title mb-0 lh-base">
        <span>{!! Template::getHeading() !!}</span>
    </h4>
    <div>
        <button
            class="btn btn-sm btn-outline-secondary"
            title="Refresh items"
            data-control="refresh-items"
        >
            <i class="fa fa-refresh"></i>
        </button>

        <button
            type="button"
            class="btn btn-sm btn-outline-secondary view-toggle-btn"
            data-view="board"
            data-control="switch-view"
            title="{{ lang('igniterlabs.kitchendisplay::default.text_switch_to_board') }}"
        >
            <i class="fa fa-columns"></i>
        </button>
        <button
            type="button"
            class="btn btn-sm btn-outline-secondary view-toggle-btn"
            data-view="list"
            data-control="switch-view"
            title="{{ lang('igniterlabs.kitchendisplay::default.text_switch_to_list') }}"
        >
            <i class="fa fa-table-cells-large"></i>
        </button>

        <button
            class="btn btn-sm btn-outline-secondary"
            title="Full screen"
            data-toggle="full-screen"
        ><i class="fa fa-expand"></i></button>
    </div>
</div>
<div class="row-fluid">
    <div class="card shadow-sm mx-3">
        {!! $this->renderKitchenDisplay() !!}
    </div>
</div>
