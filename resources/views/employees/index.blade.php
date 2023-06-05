@extends('layouts.app')

@section('content')

    <h3 class="mb-5 mt-3">Сотрудники</h3>
    <div id="alert"></div>

    <div class="d-flex justify-content-center w-100 mb-3">
        <a href="{{route('employees.index')}}">
            <button id="" type="button" class=" btn fw-bold me-2 btn-outline-dark">Сотрудники
            </button>
        </a>
        <a href="{{route('employees.clients')}}">
            <button  type="button" class="btn fw-bold ms-2 btn-outline-dark">Пользователи</button>
        </a>
    </div>

    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th class="id-column col-2">
                Имя
            </th>
            <th class="id-column col-auto">
                Почта
            </th>
            <th class="id-column col-2">
                Роль
            </th>
            <th class="date-column">
                Зарегистрирован
            </th>
        </tr>
        </thead>

        <tbody  id="table">


        @foreach ($users as $user)
            @if(!$user->hasRole('client'))

            <tr class="table-container" id="record-{{$user->id}}">
                <td>{{ $user->name }}</td>
                <td>{{ $user->email}}</td>
                <td>
                    @if (auth()->user()->can('manage staff'))
                        <select data-id="{{$user->id}}" data-name="{{$user->name}}" class="form-select user-role" aria-label="Default select example">
                            @foreach($roles as $role)
                                <option @if($user->roles()->pluck('name')->implode(', ') == $role->name) selected @endif  value="{{$role->name}}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @else
                        {{ $user->roles()->pluck('name')->implode(', ') }}
                    @endif
                </td>
                <td>{{ $user->created_at }}</td>
            </tr>
            <tr class="d-none" id="update-record-{{$user->id}}"></tr>
            @endif
        @endforeach
        <tr id="new-record"></tr>

        @if($users->count() == 0) {{-- Записи отсутствуют --}}
        <tr id="no-records">
            <td colspan="12" align="center" class="h-100 align-items-center">
                <i class="fa-solid fa-file-circle-xmark records-missing-icon"></i>
                <p>Записи отсутствуют</p>
            </td>
        </tr>
        @endif
        </tbody>
    </table>

    <div class="col-12">{{ $users->links('vendor.pagination.bootstrap-4') }}</div>

@endsection

@push('scripts')
<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.user-role').on('change', function () {
        var user = $(this);
        var id = user.data('id');
        var name = user.data('name');
        var selectedRole = user.val();


        $.ajax({
            url: '{{route('employees.roleUpdate')}}',
            method: 'POST',
            data: {
                id: id,
                role: selectedRole
            },
            success: function (data) {
                $('#alert').html(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <b>Успех!</b> Роль <b>${name}</b>, успешно изменина на <b>${selectedRole}</b>!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`)

            },

            error: function (data) {
                // Сообщение об ошибке
                $('#alert').html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Ошибка!</strong> Не удалось обновить роль у пользователя!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`)
            }
        });

    });
</script>

@endpush
