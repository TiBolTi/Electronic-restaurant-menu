@extends('layouts.app')

@section('content')

<h3 class="mb-5 mt-3">Залы</h3>
<div id="alert"></div>



<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th class="id-column" scope="col">#</th>
            <th class="col-auto">
                <button data-column="name" data-column-name="Название" class="btn btn-outline-dark fw-bold table-sort" >Название</button>
            </th>
            <th class="col-2 da">
                <button data-column="number_of_tables" data-column-name="Кол-во мест" class="btn btn-outline-dark fw-bold table-sort table-sort-btn" >Кол-во мест</button>
            </th>
            <th class="date-column">
                <button data-column="created_at" data-column-name="Дата создания" class="btn btn-outline-dark fw-bold table-sort table-sort-btn" >Дата создания</button>
            </th>
            @if (auth()->user()->can('manage records'))
            <th class="buttons-column" scope="col">Кнопки</th>
            @endif
        </tr>
    </thead>

    <tbody  id="table">
        @foreach ($halls as $hall)
           @php $records_number=  \App\Models\Hall::where('id', '<=', $hall->id)->count()@endphp

        <tr class="table-container" id="record-{{$hall->id}}">
            <td class="text-center">{{ $records_number }} </td>
            <td><b>{{ $hall->name }}</b> зал</td>
            <td><b>{{ $hall->number_of_tables}}</b> столиков</td>
            <td>{{ $hall->created_at }}</td>
            @if (auth()->user()->can('manage records'))
            <td class="table-content" id="record-btn-{{$hall->id}}">
                <div class="table-buttons text-center">
                    <button type="button" data-number="{{ $records_number }}" data-number_of_tables="{{ $hall->number_of_tables}}" data-name="{{ $hall->name }}" data-id="{{$hall->id}}" class="btn btn-primary update-record"><i class="fa-solid fa-pen"></i></button>
                    <button class="btn btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-table="halls" data-id="{{ $hall->id }}">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </td>
            @endif
        </tr>
           <tr class="d-none" id="update-record-{{$hall->id}}"></tr>

        @endforeach
        <tr id="new-record"></tr>

        @if($halls->count() == 0) {{-- Записи отсутствуют --}}
        <tr id="no-records">
        <td colspan="12" align="center" class="h-100 align-items-center">
            <i class="fa-solid fa-file-circle-xmark records-missing-icon"></i>
            <p>Записи отсутствуют</p>
        </td>
        </tr>
        @endif
    </tbody>

    @if (auth()->user()->can('manage records'))
    <tfoot>
        <tr id="create-btn">
            <td colspan="12">
                    <button class="btn btn-success new-record" tabindex="-1" role="button">Добавить запись</button>
            </td>
        </tr>

        <tr>
            <td colspan="12" id="create-form" class="d-none">
                <form action="" id="form" method="POST" class="row g-3 d-flex justify-content-center align-items-center">
                    @csrf
                    <div class="col-auto w-50">
                        <input id="input-name" type="number" name="name" class="form-control" placeholder="Укажите номер зала">
                    </div>
                    <div class="col-auto w-25">
                        <input id="input-number_of_tables" type="number" name="number_of_tables" class="form-control" placeholder="Введите количество мест в зале">
                    </div>
                    <div class="col-auto">
                        <button type="submit" id="create-record" class="btn btn-success">Добавить запись</button>
                        <button type="submit" id="cancel-record" class="btn btn-danger">Отмена</button>
                    </div>
                </form>
            </td>
        </tr>
    </tfoot>
    @endif
</table>

<div class="col-12">{{ $halls->links('vendor.pagination.bootstrap-4') }}</div>




@push('scripts')
    <script>
        $(document).ready(function() {

            // Кнопка вызова формы
            $('.new-record').click(function (e) {
                e.preventDefault();
                $('#create-btn').addClass('d-none');//Скрыть кнопку создания записи
                $('#create-form').removeClass('d-none');//Показать форму для создания записи
            })

            // Кнопка отмены создания новой записи
            $('#cancel-record').click(function (e) {
                e.preventDefault();
                // Удаление класса is-invalid и сообщений об ошибках
                $('form .is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $('#form')[0].reset(); //Очистить форму

                $('#create-form').addClass('d-none'); //Скрыть форму
                $('#create-btn').removeClass('d-none'); //Показать кнопку

            })

            // Кнопка создание новой записи
            $('#create-record').click(function (e) {
                e.preventDefault();

                $('#create-record').prop('disabled', true); //Отключить кнопку пока не выполниться действие

                $.ajax({
                    url: '{{route('halls.store')}}',
                    method: 'POST',
                    data: $('#form').serialize(),
                    success: function (data) {
                        // Удаление класса is-invalid и сообщений об ошибках
                        $('form .is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').remove();
                        if (data.success) {
                            $('#no-records').hide(); //Скрыть сообщение об отсутствии записей
                            $('#create-record').prop('disabled', false); //Включить кнопку

                            $('#create-form').addClass('d-none'); //Скрыть форму
                            $('#create-btn').removeClass('d-none'); //Показать кнопку

                            $('#form')[0].reset(); //Очистить форму
                            $('#new-record').before(data.record); //Добавить новую запись в таблицу

                            // Сообщение об успехе
                            var recordCount = data.record_id; //Получение номера записи для отображения в сообщении
                            $('#alert').html(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Запись №${recordCount}!</strong> Была успешно добавленна!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`
                            )

                            // Если записей больше, чем на указанно в пагинаторе,
                            // после создания записи перекинуть пользователя на следующую страницу
                            var lastPage = {{ $halls->lastPage() }};
                            if ($('.table-container').length >= {{ $halls->perPage() }}) {
                                window.location.href = "{{ route('halls.index') }}?page=" + lastPage;
                            }
                        } else {

                            // Сообщение об ошибке
                            $('#alert').html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Ошибка!</strong> Не удалось добавить запись!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`)

                            $('#create-record').prop('disabled', false); //Включить кнопку
                            // Если есть ошибки валидации, отобразить их
                            $.each(data.errors, function(field, errors) {
                                // Найти элемент формы, соответствующий полю, содержащему ошибку
                                var input = $('form [name="' + field + '"]');
                                // Добавить класс is-invalid для этого элемента
                                input.addClass('is-invalid');
                                // Вывести сообщение об ошибке
                                var errorMessages = '<div class="invalid-feedback">';
                                $.each(errors, function(index, error) {
                                    errorMessages += '<div>' + error + '</div>';
                                });
                                errorMessages += '</div>';
                                input.after(errorMessages);
                            });
                        }
                    },
                    error: function (data) {
                        $('#create-record').prop('disabled', false); //Включить кнопку

                        // Сообщение об ошибке
                        $('#alert').html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Ошибка!</strong> Не удалось добавить запись!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`)
                    }
                });
            });
        });

        // Скрипт изменения записи
        $(document).ready(function() {
            // Появление формы изменения записи
            $(document).on('click', '.update-record', function(e) {
                e.preventDefault();

                var record = $(this); // текущая запись
                var id = record.data('id'); // получаем ID текущей записи
                var number = record.data('number'); //номер записи
                var name = record.data('name'); //содержимое записи
                var number_of_tables = record.data('number_of_tables');

                $('#record-' + id).hide(); //скрываем старую запись

                //вызываем форму на место записи
                $('#update-record-' + id).after(`
            <tr class="table-container" id="update-record-form-${id}">
                <td class="text-center">${number}</td>
                <td colspan="12" class="w-100 table-content record">
                    <form id="update-form-${id}" method="PUT" class="row g-3 d-flex justify-content-center align-items-center">
                        @csrf
                @method("PUT")
                <div class="col-auto w-50">
                    <label for="input-name-${id}" class="visually-hidden">Введите название зала</label>
                            <input value="${id}" name="id" type="hidden" class="form-control" placeholder="Введите название зала" value="${id}">
                            <input id="input-name-${id}" value="${name}" name="name" type="number" class="form-control" placeholder="Укажите номер зала" value="${name}">
                        </div>
                        <div class="col-auto w-25">
                        <input id="input-number_of_tables-${id}" value="${number_of_tables}" type="number" name="number_of_tables" class="form-control" placeholder="Введите количество мест в зале">
                    </div>
            <div class="col-auto">
                <button type="submit" id="complete-update-${id}" class="btn btn-success">Обновить запись</button>
                            <button type="button" id="cancel-update-${id}" class="btn btn-danger">Отмена</button>
                        </div>
                    </form>
                </td>
            </tr>

        `);

                    // Обновление записи
                    $('#complete-update-' + id).click(function (e) {
                        e.preventDefault();
                        $.ajax({
                            url: '{{ isset($hall) ? route('halls.update', $hall) : '' }}',
                            method: 'PUT',
                            data: $('#update-form-' + id).serialize(),
                            success: function (data) {
                                // Удаление класса is-invalid и сообщений об ошибках
                                $('form .is-invalid').removeClass('is-invalid');
                                $('.invalid-feedback').remove();

                                if (data.success) {

                                sort()

                                $('#update-record-form-' + id).remove(); //Скрытие формы
                                $('#record-' + id).replaceWith(data.record); //Замена старой записи новой

                                // Сообщение об успехе
                                var recordCount = data.record_id; //Получение номера записи для отображения в сообщении
                                $('#alert').html(`
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Запись №${recordCount}!</strong> Была успешно изменина!
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>`)
                            }
                                else {

                                    // Сообщение об ошибке
                                    $('#alert').html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Ошибка!</strong> Не удалось добавить запись!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`)


                                    // Если есть ошибки валидации, отобразить их
                                    $.each(data.errors, function(field, errors) {
                                        // Найти элемент формы, соответствующий полю, содержащему ошибку
                                        var input = $('#update-form-' + id +' [name="' + field + '"]');
                                        // Добавить класс is-invalid для этого элемента
                                        input.addClass('is-invalid');
                                        // Вывести сообщение об ошибке
                                        var errorMessages = '<div class="invalid-feedback text-start">';
                                        $.each(errors, function(index, error) {
                                            errorMessages += '<div>' + error + '</div>';
                                        });
                                        errorMessages += '</div>';
                                        input.after(errorMessages);
                                    });
                                }
                            },
                            error: function (data) {
                                // Сообщение об ошибке
                                $('#alert').html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Ошибка!</strong> Не удалось добавить запись!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`)
                            }
                        });
                    });

                // Обработчик клика на кнопку "Отмена"
                $('#cancel-update-' + id).click(function (e) {
                    e.preventDefault();
                    $('#update-record-form-' + id).remove(); //Спрятать форму
                    $('#record-' + id).show(); //Показать старую запись


                });
            });
        });

        // Скрипт сортировки

        var prevTableColumn; //Выбранный столбец
        var sortState = "no-sort"; //Тип сортировки
        var column = "id"; //Сортируемый столбец
        var paginate = "{{$paginate}}" //Пагинатор

        $('.table-sort').click(function (e) {
            e.preventDefault();

            var table_column = $(this); // текущая запись
            column = table_column.data('column'); //Сортируемый столбец
            var name = table_column.data('column-name'); //Название сортируемого столбеца

            // Сброс значения предыдущей кнопки
            if (prevTableColumn && prevTableColumn[0] !== table_column[0]) {
                prevTableColumn.html(prevTableColumn.data('column-name'));

                sortState = "no-sort";
            }

            // Выбор типа сортировки
            // 1 нажатие на кнопку, сортировка по возрастанию
            if (sortState === "no-sort") {
                table_column.html(name + ` <i class="fa-solid fa-caret-up"></i>`);
                sortState = "asc";

                sort() //Вызов функции сортировки
            }
            // 2 нажатие на кнопку, сортировка по убыванию
            else if (sortState === "asc") {
                table_column.html(name + ` <i class="fa-solid fa-caret-down"></i>`);
                sortState = "desc";

                sort() //Вызов функции сортировки
            }
            // 3 нажатие на кнопку, отключение сортировки
            else if (sortState === "desc") {
                table_column.html(name);
                sortState = "no-sort";

                sort() //Вызов функции сортировки
            }

            prevTableColumn = table_column;
        });

        //Функция сортировки
        function sort() {
            $.ajax({
                url: '{{route('halls.sort')}}',
                method: 'get',
                data: {
                    column: column,
                    sort: sortState,
                    paginate: paginate,
                },
                success: function (data) {
                    $('#table').html(data.view);
                },
                error: function () {
                    console.log('sort ERROR!')
                }
            });
        }

    </script>
    @endpush


<script>
    $(document).ready(function() {
        // Удаление записи
        var deleteModal = $('#deleteModal');
        deleteModal.on('show.bs.modal', function(event) {

            var button = $(event.relatedTarget);
            var table = button.data('table');
            var id = button.data('id');
            var form = deleteModal.find('form');
            var actionUrl = "{{ url('') }}/" + table + "/" + id;

            form.off('submit');
            form.attr('action', actionUrl);
            form.on('submit', function(event) {
                event.preventDefault();

                $.ajax({
                    url: actionUrl,
                    type: 'DELETE',
                    data: form.serialize(),
                    success: function(data) {
                        $('#record-' + id).remove();
                        sort();
                        // Скрываем модальное окно
                        deleteModal.modal('toggle');


                        // Сообщение об успехе
                        var recordCount = data.record_id; //Получение номера записи для отображения в сообщении

                        $('#alert').html(`<div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Запись №${recordCount}!</strong> Была успешно удалена!
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `);


                    },
                    error: function(data) {
                        // Сообщение об ошибке
                        $('#alert').html(`
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Ошибка! </strong> Не удалось удалить запись!
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `)
                    }
                });
            });
        });

    });
</script>
@section('delete')
    @endsection


@endsection
