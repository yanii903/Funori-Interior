@extends('admin.layout.admin')

@section('content')
    <div class="main-content-inner">
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Contact List</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="index.html">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="#">
                            <div class="text-tiny">Contact</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Contact List</div>
                    </li>
                </ul>
            </div>
            <!-- order-list -->
            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="form-search flex gap10" method="GET">
                            <fieldset class="name">
                                <input type="text" placeholder="Search name or email..." name="keyword"
                                    value="{{ request('keyword') }}">
                            </fieldset>
                            <fieldset>
                                <select name="status">
                                    <option value="">All Status</option>
                                    <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                                    <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
                                    <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>Replied
                                    </option>
                                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>
                                        Resolved</option>
                                </select>
                            </fieldset>
                            <div class="button-submit">
                                <button type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <a class="tf-button style-1 w208" href="#">
                    {{-- <a class="tf-button style-1 w208" href="{{ route('admin.contacts.export') }}"> --}}
                        <i class="icon-file-text"></i>Export all order
                    </a>
                </div>
                <div class="wg-table table-all-category">
                    <ul class="table-title flex gap20 mb-14">
                        <li>
                            <div class="body-title">Email</div>
                        </li>
                        <li>
                            <div class="body-title">Name</div>
                        </li>
                        <li>
                            <div class="body-title">Phone</div>
                        </li>
                        <li>
                            <div class="body-title">Subject</div>
                        </li>
                        <li>
                            <div class="body-title">Message</div>
                        </li>
                        <li>
                            <div class="body-title">Status</div>
                        </li>
                        <li>
                            <div class="body-title">Admin Reply</div>
                        </li>
                        <li>
                            <div class="body-title">Replied By Id</div>
                        </li>
                        <li>
                            <div class="body-title">Created At</div>
                        </li>
                        <li>
                            <div class="body-title">Action</div>
                        </li>
                    </ul>
                    <ul class="flex flex-column">
                        @foreach ($contacts as $value)
                            <li class="wg-product item-row gap20">
                                <div class="name">
                                    <div class="title line-clamp-2 mb-0">
                                        <a href="#" class="body-text fw-6">{{ $value->email }}</a>
                                    </div>
                                </div>
                                <div class="body-text text-main-dark mt-4">{{ $value->name }}</div>
                                <div class="body-text text-main-dark mt-4">{{ $value->phone }}</div>
                                <div class="body-text text-main-dark mt-4">{{ $value->subject }}</div>
                                <div class="body-text text-main-dark mt-4">(view detail)</div>
                                <div>
                                    <div class="block-available status-{{ $value->status }} fw-7">
                                        {{ ucfirst($value->status) }}
                                    </div>
                                </div>
                                <div class="body-text text-main-dark mt-4">(view detail)</div>
                                <div class="body-text text-main-dark mt-4">
                                    @if ($value->replied_by)
                                        {{ \App\Models\User::find($value->replied_by)->full_name ?? 'Unknown' }}
                                    @else
                                        <p class="btn btn-danger fw-bold">Unknown</p>
                                    @endif
                                </div>
                                <div class="body-text text-main-dark mt-4">
                                    {{ $value->created_at->format('d-m-Y') }}
                                </div>
                                <div class="list-icon-function">
                                    <div class="item eye" data-bs-toggle="modal"
                                        data-bs-target="#quickViewModal{{ $value->id }}">
                                        <i class="icon-eye"></i>
                                    </div>
                                    <!-- Modal Chi tiết liên hệ -->
                                    <div class="modal fade" id="quickViewModal{{ $value->id }}" tabindex="-1"
                                        aria-labelledby="quickViewLabel{{ $value->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content shadow-lg rounded-4 border-0"
                                                style="font-size: 16px;">
                                                <div class="modal-header bg-primary text-white">
                                                    <h3 class="modal-title fw-bold mb-0 text-light"
                                                        id="quickViewLabel{{ $value->id }}">
                                                        <i class="bi bi-info-circle me-2"></i>Contact Details
                                                    </h3>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Đóng"></button>
                                                </div>
                                                <div class="modal-body px-5 py-4">
                                                    <div class="row gy-3">
                                                        <div class="col-sm-6">
                                                            <strong>Email:</strong>
                                                            <div class="text-muted">{{ $value->email }}</div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <strong>Name:</strong>
                                                            <div class="text-muted">{{ $value->name }}</div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <strong>Phone:</strong>
                                                            <div class="text-muted">{{ $value->phone }}</div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <strong>Subject:</strong>
                                                            <div class="text-muted">{{ $value->subject }}</div>
                                                        </div>
                                                        <div class="col-12">
                                                            <strong>Message:</strong>
                                                            <div
                                                                class="border rounded p-3 bg-light text-secondary fst-italic">
                                                                {{ $value->message ?? '(No content)' }}
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <strong>Admin Reply:</strong>
                                                            <div
                                                                class="border rounded p-3 bg-light text-secondary fst-italic">
                                                                {{ $value->admin_reply ?? '(No content)' }}
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <strong>Status:</strong>
                                                            <span
                                                                class="badge fs-6
                            @if ($value->status == 'new') bg-primary
                            @elseif($value->status == 'read') bg-warning text-dark
                            @elseif($value->status == 'replied') bg-info text-dark
                            @elseif($value->status == 'resolved') bg-success
                            @else bg-secondary @endif">
                                                                {{ ucfirst($value->status) }}
                                                            </span>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <strong>Created At:</strong>
                                                            <div class="text-muted">
                                                                {{ $value->created_at->format('d-m-Y H:i') }}</div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <strong>Replied By:</strong>
                                                            <div class="text-muted">
                                                                @if ($value->replied_by)
                                                                    {{ \App\Models\User::find($value->replied_by)->full_name ?? 'Unknown' }}
                                                                @else
                                                                    Unknown
                                                                @endif
                                                                {{-- {{ $value->replied_by ?? 'Chưa có' }} --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="item edit">
                                        <a href="{{ route('admin.contacts.edit', $value->id) }}"><i
                                                class="icon-edit-3"></i></a>
                                    </div>
                                    <div class="item trash">
                                        <form action="{{ route('admin.contacts.destroy', $value->id) }}" method="POST"
                                            style="display:inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this contacts?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                style="background: none; border: none; padding: 0; color: inherit; cursor: pointer; display: flex; align-items: center;">
                                                <i class="icon-trash-2" style="color: red; font-size: 20px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10">
                    <div class="text-tiny">
                        Hiển thị {{ $contacts->firstItem() }} đến {{ $contacts->lastItem() }} trong tổng số
                        {{ $contacts->total() }} bản ghi
                    </div>

                    <ul class="wg-pagination">
                        {{-- Previous Page Link --}}
                        <li class="{{ $contacts->onFirstPage() ? 'disabled' : '' }}">
                            <a href="{{ $contacts->previousPageUrl() ?? '#' }}">
                                <i class="icon-chevron-left"></i>
                            </a>
                        </li>

                        {{-- Pagination Elements --}}
                        @for ($i = 1; $i <= $contacts->lastPage(); $i++)
                            <li class="{{ $contacts->currentPage() == $i ? 'active' : '' }}">
                                <a href="{{ $contacts->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        {{-- Next Page Link --}}
                        <li class="{{ $contacts->hasMorePages() ? '' : 'disabled' }}">
                            <a href="{{ $contacts->nextPageUrl() ?? '#' }}">
                                <i class="icon-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
            <!-- /order-list -->
        </div>
        <!-- /main-content-wrap -->
    </div>
@endsection
