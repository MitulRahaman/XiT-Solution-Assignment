<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css" >
    <link rel="stylesheet" href="{{ asset('css/oneui.min.css') }}">

    <style >
        div.dataTables_info
        {
            color: black;
        }

        .text-gray-800, .text-gray-900, .text-gray-500{
            color: black;
        }

    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if( auth()->user()->is_admin )
                        <div class="block-content block-content-full">
                            <table class="table table-bordered table-striped table-vcenter js-dataTable-buttons" id="dataTable">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 80px;">Sl no.</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th class="d-none d-sm-table-cell" style="width: 25%; text-align:center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    @else
                        @if( auth()->user()->is_verified )
                        {{ __("You're logged in!") }}
                        @elseif( auth()->user()->is_deleted )
                        {{ __("Your request is declined.") }}
                        @else
                        {{ __("Your registration is pending approval from an Admin. Please be patient. We will notify you.") }}
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="accept-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-vcenter" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="accept" action="" method="post">
                    @csrf
                    <div class="block block-rounded block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title text-center">Accept User</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                    x
                                </button>
                            </div>
                        </div>
                        <div class="block-content font-size-sm">
                            <p class="text-center"><span id="restore_user_name"></span> User will be accepted. Are you sure?</p>
                            <input type="hidden" name="accepted_user_id" id="accepted_user_id">
                        </div>
                        <div class="block-content block-content-full text-right border-top">
                            <button type="button" class="btn btn-alt-primary mr-1" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="decline-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-vcenter" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="decline" action="" method="post">
                    @csrf
                    <div class="block block-rounded block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title text-center">Decline User</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                    x
                                </button>
                            </div>
                        </div>
                        <div class="block-content font-size-sm">
                            <p class="text-center">Are you sure want to decline this user?</p>
                            <input type="hidden" name="deleted_user_id" id="deleted_user_id">
                        </div>
                        <div class="block-content block-content-full text-right border-top">
                            <button type="button" class="btn btn-alt-primary mr-1" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('js/oneui.app.min.js') }}"></script>
<script src="{{ asset('js/oneui.core.min.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

<script>
    jQuery(function(){
        function createTable(){
            $('#dataTable').DataTable( {
                dom: 'Blfrtip',
                searching: false,
                bLengthChange : false,
                bPaginate: false,
                ordering: false,
                ajax: {
                    type: 'POST',
                    url: '{{ route("user.pendingUsers") }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                    }
                },
            });
        }
        createTable();
    });

    function show_accept_modal(id) {
        const url = "{{ route('user.accept') }}";
        $('#accept').attr('action', url);
        $('#accepted_user_id').attr('value', id);
        $('#accept-modal').modal('show');
    }
    function show_decline_modal(id) {
        const url = "{{ route('user.decline') }}";
        $('#decline').attr('action', url);
        $('#deleted_user_id').attr('value', id);
        $('#decline-modal').modal('show');
    }

 </script>
