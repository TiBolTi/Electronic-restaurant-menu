@extends('layouts.app')

@section('content')

    <h3 class="mb-5 mt-3">Категории</h3>
    <div id="alert"></div>



    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th class="id-column" scope="col">#</th>
            <th class="col-auto id-column">
               Изображение
            </th>
            <th class="col-4">
                <button data-column="name" data-column-name="Название" class="btn btn-outline-dark fw-bold table-sort" >Название</button>
            </th>
            <th class="col-2">
                <button data-column="is_publish" data-column-name="Публикация" class="btn btn-outline-dark fw-bold table-sort table-sort-btn" >Публикация</button>
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
        @foreach ($categories as $category)
            @php $records_number=  \App\Models\Category::where('id', '<=', $category->id)->count()@endphp

            <tr class="table-container" id="record-{{$category->id}}">
                <td class="text-center">{{ $records_number }} </td>
                <td><img width="200px" class="img rounded" src="{{asset('storage/files/' . $category->image)}}" alt=""></td>
                <td>{{ $category->name }}</td>
                <td>
                    @if($category->is_publish == 1)
                        Опубликовано
                    @else
                        Не опубликовано
                    @endif
                </td>
                <td>{{ $category->created_at }}</td>
                @if (auth()->user()->can('manage records'))
                    <td class="table-content" id="record-btn-{{$category->id}}">
                        <div class="table-buttons text-center">
                            <button type="button" data-publish="{{ $category->is_publish }}" data-number="{{ $records_number }}"  data-name="{{ $category->name }}" data-id="{{$category->id}}" class="btn btn-primary update-record"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-table="categories" data-id="{{ $category->id }}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                @endif
            </tr>
            <tr class="d-none" id="update-record-{{$category->id}}"></tr>

        @endforeach
        <tr id="new-record"></tr>

        @if($categories->count() == 0) {{-- Записи отсутствуют --}}
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
                    <form action="" id="form" method="POST" class="row g-3 d-flex justify-content-center align-items-center" >
                        @csrf
                        <div class="col-auto w-25">
                            <input type="file" accept="image/*" name="image" id="image-input" class="form-control" required>
                        </div>
                        <div class="col-auto w-25 ">
                            <input id="input-name" type="text" name="name" class="form-control" placeholder="Введите название категории">
                        </div>
                        <div class="col-auto form-check form-switch ms-4 w-25">
                            <input class="form-check-input" name="is_publish" type="checkbox" id="check-is-publish">
                            <label class="form-check-label" id="check-is-publish-label" for="check-is-publish">Опубликовать категорию</label>
                        </div>
                        <div class="col-auto">
                            <button type="submit" id="create-record" class="btn btn-success ">Добавить запись</button>
                            <button type="submit" id="cancel-record" class="btn btn-danger">Отмена</button>
                        </div>
                    </form>
                </td>
            </tr>
            </tfoot>
        @endif
    </table>

    <div class="col-12">{{ $categories->links('vendor.pagination.bootstrap-4') }}</div>




    @push('scripts')
        <script>
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
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
                $('#check-is-publish').click(function() {
                    if ($(this).is(':checked')) {
                        $('#check-is-publish-label').html('Опубликовано');
                    } else {
                        $('#check-is-publish-label').html('Не опубликовано');
                    }
                })
                // Кнопка создание новой записи
                $('#create-record').click(function (e) {
                    e.preventDefault();

                    $('#create-record').prop('disabled', true); //Отключить кнопку пока не выполниться действие

                    var formData = new FormData();
                    formData.append('image', $('#image-input')[0].files[0]);
                    formData.append('name', $('#input-name').val());
                    formData.append('is_publish', $('#check-is-publish').is(':checked') ? 1 : 0);

                    $.ajax({
                        url: '{{route('categories.store')}}',
                        method: 'POST',
                        contentType: false,
                        processData: false,
                        data: formData,

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
                                var record_count = data.record_id; //Получение номера записи для отображения в сообщении
                                $('#alert').html(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Запись №${record_count}!</strong> Была успешно добавленна!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`
                                )

                                // Если записей больше, чем на указанно в пагинаторе,
                                // после создания записи перекинуть пользователя на следующую страницу
                                var lastPage = {{ $categories->lastPage() }};
                                if ($('.table-container').length >= {{ $categories->perPage() }}) {
                                    window.location.href = "{{ route('categories.index') }}?page=" + lastPage;
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
                var updateFormData;
                // Появление формы изменения записи
                $(document).on('click', '.update-record', function(e) {
                    e.preventDefault();

                    var record = $(this); // текущая запись
                    var id = record.data('id'); // получаем ID текущей записи
                    var number = record.data('number'); //номер записи
                    var name = record.data('name'); //содержимое записи
                    var publish = record.data('publish');



                    $('#record-' + id).hide(); //скрываем старую запись


                    //вызываем форму на место записи
                    $('#update-record-' + id).after(`
            <tr class="table-container" id="update-record-form-${id}" >
                <td class="text-center">${number}</td>
                <td colspan="12" class="w-100 table-content record">
                    <form id="update-form-${id}" method="PUT" class="row g-3 d-flex justify-content-center align-items-center"  enctype="multipart/form-data">
                        @csrf
                    @method("POST")
                    <div class="col-auto w-25">
                        <input type="file" accept="image/*"  name="image" id="image-input-${id}" class="form-control">
                    </div>
                <div class="col-auto w-25">
                    <label for="input-name-${id}" class="visually-hidden">Введите название категории</label>
                            <input value="${id}" name="id" type="hidden" class="form-control" placeholder="Введите название категории" value="${id}">
                            <input id="input-name-${id}" value="${name}" name="name" type="text" class="form-control" placeholder="Введите название категории" value="${name}">
                        </div>
                        <div class="col-auto form-check form-switch ms-3 w-25 text-start">
                            <input class="form-check-input" name="is_publish" type="checkbox" id="check-is-publish-${id}" ${publish === 1 ? 'checked' : ''}>
                            <label class="form-check-label" id="check-is-publish-label-${id}" for="check-is-publish">${publish === 1 ? 'Опубликовано' : 'Не опубликовано'}</label>
                        </div>
                <div class="col-auto">
                    <button type="submit" id="complete-update-${id}" class="btn btn-success">Обновить запись</button>
                            <button type="button" id="cancel-update-${id}" class="btn btn-danger">Отмена</button>
                        </div>
                    </form>
                </td>
            </tr>

        `);

                    $('#check-is-publish-'+ id).click(function() {
                        if ($(this).is(':checked')) {
                            $('#check-is-publish-label-'+ id).html('Опубликовано');
                        } else {
                            $('#check-is-publish-label-'+ id).html('Не опубликовано');
                        }
                    })





                    // Обновление записи
                    $('#complete-update-' + id).click(function (e) {
                        e.preventDefault();

                        var formData = new FormData();
                        formData.append('id', id);
                        formData.append('image', $('#image-input-'+id)[0].files[0]);
                        formData.append('name', $('#input-name-'+id).val());
                        formData.append('is_publish', $('#check-is-publish-'+id).is(':checked') ? 1 : 0);


                        $.ajax({
                            url: '{{route('categories.update')}}',
                            method: 'POST',
                            contentType: false,
                            processData: false,
                            data: formData,

                            success: function (data) {
                                // Удаление класса is-invalid и сообщений об ошибках
                                $('form .is-invalid').removeClass('is-invalid');
                                $('.invalid-feedback').remove();
                                if (data.success) {
                                    sort()
                                    $('#update-record-form-' + id).remove(); //Скрытие формы
                                    $('#record-' + id).replaceWith(data.record); //Замена старой записи новой

                                    // Сообщение об успехе
                                    var record_count = data.record_id; //Получение номера записи для отображения в сообщении
                                    $('#alert').html(`
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Запись №${record_count}!</strong> Была успешно изменина!
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
                    url: '{{route('categories.sort')}}',
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
                            var record_count = data.record_id; //Получение номера записи для отображения в сообщении

                            $('#alert').html(`<div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Запись №${record_count}!</strong> Была успешно удалена!
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
