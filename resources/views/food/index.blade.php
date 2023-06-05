@extends('layouts.app')

@section('content')

    <h3 class="mb-5 mt-3">Блюда</h3>
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
            <th class="col-1">
                <button data-column="name" data-column-name="Цена" class="btn btn-outline-dark fw-bold table-sort table-sort-btn" >Цена</button>
            </th>
            <th class="col-1">
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
        @foreach ($foods as $food)
            @php $records_number=  \App\Models\Food::where('id', '<=', $food->id)->count()@endphp

            <tr class="table-container" id="record-{{$food->id}}">
                <td class="text-center">{{ $records_number }} </td>
                <td><img width="200px" class="img rounded" src="{{asset('storage/files/' . $food->image)}}" alt=""></td>
                <td>{{ $food->name }}</td>
                <td>{{ $food->price }} сом</td>

                <td>
                    @if($food->is_publish == 1)
                        Опубликовано
                    @else
                        Не опубликовано
                    @endif
                </td>
                <td>{{ $food->created_at }}</td>

                    <td class="table-content" id="record-btn-{{$food->id}}">
                        <div class="table-buttons text-center">
                            <button type="button" id="more-about-{{$food->id}}" data-ingredients="{{ $toppings }}" data-categories="{{ $categories }}" data-publish="{{ $food->is_publish }}" data-number="{{ $records_number }}"  data-name="{{ $food->name }}" data-id="{{$food->id}}" class="btn btn-primary more-about">Подробнеe</button>
                            <button type="button" id="hide-more-about-{{$food->id}}" data-id="{{$food->id}}" class="btn btn-secondary hide-more-about d-none">Скрыть</button>
                        </div>
                    </td>

            </tr>


            <tr id="more-about-record-{{$food->id}}" class="d-none">
                <td colspan="12">
                        <div class="d-flex justify-content-around">
                            <div class="d-flex flex-column mt-3 ms-5 w-50">
                                {{-- Название --}}
                                <div class="mb-3">
                                    <label for="input-name" class="form-label">Название</label>
                                    <h5>{{$food->name}}</h5>
                                </div>
                                {{-- Описание --}}
                                <div class="mb-3 w-75">
                                    <label for="input-desc" class="form-label">Описание</label>
                                    <h6 style="overflow-wrap: anywhere;">{{$food->description}}</h6>
                                </div>
                                {{-- Цена --}}
                                <label for="input-price" class="form-label">Цена</label>
                                <div class="input-group mb-3">
                                    <h5>{{$food->price}} сом</h5>

                                </div>

                                <div class="d-flex flex-column me-5 w-50"">
                                    {{-- Это веганское --}}
                                    @if($food->is_vegan == true)
                                        <p class="btn bg-success fw-bold rounded" style="color: #ffffff;"><i class="fa-solid fa-leaf"></i> Веганское блюдо</p>
                                    @endif
{{--                                    Это специальное--}}
                                    @if($food->is_special == true)
                                        <p class="btn bg-danger fw-bold rounded" style="color: #ffffff;"><i class="fa-solid fa-star"></i> Специальное блюдо</p>
                                    @endif
                                    {{-- Публикация --}}
                                    @if($food->is_publish == true)
                                        <p class="btn bg-primary fw-bold rounded" style="color: #ffffff;"><i class="fa-solid fa-globe"></i> Опубликовано</p>
                                     @endif
                                </div>
                            </div>

                            <div class="d-flex flex-column justify-content-start w-50 mt-3 me-5 flex-column">
                                <label for="input-topping" class="form-label">Категории</label>
                                <div class="mb-3 d-flex flex-column">

                                        @foreach($categories as $category)
                                            @if($category->is_publish == true && $food->category->contains($category))
                                                <h5><span class="badge bg-primary">{{$category->name}}</span></h5>
                                            @endif
                                        @endforeach

                                </div>
                                <div class="flex-wrap d-flex mb-5"></div>

                                <label for="input-topping" class="form-label mt-5">Ингредиенты</label>
                                <div class="mb-3 d-flex flex-column h-50 flex-wrap">
                                    @foreach($food->topping as $topping)
                                        @php
                                            $quantity = $topping->pivot->quantity;
                                        @endphp
                                        <h5><span class="badge bg-success">{{$topping->name}} @if($quantity) - {{$quantity}} {{$topping->unit->getAbbreviationUnit()}}@endif</span></h5>
                                    @endforeach

                                </div>
                                <div class="flex-wrap d-flex"></div>
                            </div>
                        </div>
                    @if (auth()->user()->can('manage records'))
                        <div class="col-auto text-center mt-3 mb-3">
                            <button type="submit" data-id="{{$food->id}}" class="btn btn-success update-record"><i class="fa-solid fa-pen"></i> Изменить запись</button>
                            <button class="btn btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-table="food" data-id="{{ $food->id }}">
                                <i class="fa-solid fa-trash"></i> Удалить
                            </button>
                        </div>
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="12" id="update-record-{{$food->id}}" class="d-none">
                    <form action="" id="update-form-{{$food->id}}" method="POST" class="row g-3 d-flex justify-content-center align-items-center">
                        @csrf
                        <div class="d-flex justify-content-around">
                            <div class="d-flex flex-column mt-3">
                                {{-- Название --}}
                                <div class="mb-3">
                                    <label for="update-input-name-{{$food->id}}" class="form-label">Название</label>
                                    <input id="update-input-name-{{$food->id}}" type="text" value="{{$food->name}}" name="name" class="form-control" placeholder="Введите название блюда">
                                </div>
                                {{-- Описание --}}
                                <div class="mb-3">
                                    <label for="update-input-desc-{{$food->id}}" class="form-label">Описание</label>
                                    <textarea class="form-control" name="description" id="update-input-desc-{{$food->id}}" placeholder="Введите описание для вашего блюда" rows="3">{{$food->description}}</textarea>
                                </div>
                                {{-- Изображение --}}
                                <div class="mb-3">
                                    <label for="update-image-input-{{$food->id}}" class="form-label">Изображение</label>
                                    <input type="file" accept="image/*" name="image" id="update-image-input-{{$food->id}}" class="form-control" required>
                                </div>
                                {{-- Цена --}}
                                <label for="update-input-price-{{$food->id}}" class="form-label">Цена</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" value="{{$food->price}}" name="price" id="update-input-price-{{$food->id}}" placeholder="Укажите цену блюда">
                                    <span class="input-group-text">сом</span>
                                </div>
                                {{-- Это веганское --}}
                                <div class="col-auto form-check">
                                    <input class="form-check-input" name="is_vegan" type="checkbox" id="update-check-is-vegan-{{$food->id}}" @if($food->is_vegan == 1) checked @endif>
                                    <label class="form-check-label" for="update-check-is-vegan-{{$food->id}}">Это веганское блюдо</label>
                                </div>
                                {{-- Это специальное --}}
                                <div class="col-auto form-check">
                                    <input class="form-check-input" name="is_special" type="checkbox" id="update-check-is-special-{{$food->id}}" @if($food->is_special == 1) checked @endif>
                                    <label class="form-check-label" for="update-check-is-special-{{$food->id}}">Это специальное блюдо</label>
                                </div>
                                {{-- Публикация --}}
                                <div class="col-auto form-check form-switch mt-3">
                                    <input class="form-check-input" name="is_publish" type="checkbox" id="update-check-is-publish-{{$food->id}}" @if($food->is_publish == 1) checked @endif>
                                    <label class="form-check-label" for="update-check-is-publish-{{$food->id}}">Опубликовать блюдо</label>
                                </div>
                            </div>
                            <div class="d-flex flex-column justify-content-start w-50 mt-3 flex-column">
                                <label for="input-topping" class="form-label">Категории</label>
                                <div class="mb-3 d-flex" id="update-category-list-{{$food->id}}">
                                    <select id="update-input-category-{{$food->id}}" class="form-select me-2">
                                        @foreach($categories as $category)
                                            @if($category->is_publish == true && !$food->category->contains($category))
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endif
                                        @endforeach


                                    </select>
                                    <button type="button" id="update-add-category-{{$food->id}}" class="btn d-block btn-success form-control">Добавить категорию</button>
                                </div>
                                <div class="flex-wrap d-flex mb-5" id="update-category-records-{{$food->id}}">
                                    @foreach($categories as $category)
                                        @if($category->is_publish == true && $food->category->contains($category))
                                            <div style="width: 48%; margin-left: 5px; margin-bottom: 3px; height: fit-content" class="input-group update-category-input-group-{{$food->id}}">
                                                <button class="btn btn-danger update-delete-category-{{$food->id}}" type="button"><i class="fa-solid fa-xmark"></i></button>
                                                <input class="form-control" type="hidden" name="update-category-{{$food->id}}" value="{{$category->id}}">
                                                <span class="input-group-text update-category-name-{{$food->id}} " style="width: 85%">{{$category->name}}</span>
                                                </div>
                                        @endif
                                    @endforeach
                                </div>



                                <label for="update-input-topping-{{$food->id}}" class="form-label mt-5">Ингредиенты</label>
                                <div class="mb-3 d-flex" id="update-topping-list-{{$food->id}}">
                                    <select id="update-input-topping-{{$food->id}}" class="form-select me-2">
                                        @foreach($toppings as $topping)
                                            @if(!$food->topping->contains($topping))
                                            <option data-unit="{{$topping->unit->getAbbreviationUnit()}}" value="{{$topping->id}}">{{$topping->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <button type="button" id="update-add-topping-{{$food->id}}" class="btn d-block btn-success form-control">Добавить ингредиент</button>
                                </div>
                                <div class="flex-wrap d-flex" id="update-topping-records-{{$food->id}}">

                                    @foreach($food->topping as $topping)

                                        @if($food->topping->contains($topping))
                                            @php
                                                $quantity = $topping->pivot->quantity;
                                            @endphp
                                            <div style="width: 48%; margin-left: 5px; margin-bottom: 3px; height: fit-content" class="input-group update-topping-input-group-{{$food->id}}">
                                                <button class="btn btn-danger update-delete-topping-{{$food->id}}" type="button"><i class="fa-solid fa-xmark"></i></button>
                                                <span class="w-50 input-group-text update-topping-name-{{$food->id}}" style="white-space: pre-wrap; text-align: start;">{{$topping->name}}</span>
                                                <input class="form-control" type="hidden" name="update-topping-{{$food->id}}" value="{{$topping->id}}">
                                                <input class="form-control" type="number" name="update-quantity-{{$food->id}}" value="@if($quantity){{$quantity}}@endif" placeholder="Вес / Количество">
                                                <span  class=" input-group-text update-topping-unit-{{$food->id}}">{{$topping->unit->getAbbreviationUnit()}}</span>
                                                </div>
                                        @endif
                                    @endforeach

                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="submit" id="complete-update-{{$food->id}}" class="btn btn-success">Обновить запись</button>
                            <button type="button" id="cancel-update-{{$food->id}}" class="btn btn-danger">Отмена</button>
                        </div>
                    </form>
                </td>
            </tr>

        @endforeach

        <tr id="new-record"></tr>

        @if($foods->count() == 0) {{-- Записи отсутствуют --}}
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
                        <div class="d-flex justify-content-around">
                            <div class="d-flex flex-column mt-3">
                                {{-- Название --}}
                                <div class="mb-3">
                                    <label for="input-name" class="form-label">Название</label>
                                    <input id="input-name" type="text" name="name" class="form-control" placeholder="Введите название блюда">
                                </div>
                                {{-- Описание --}}
                                <div class="mb-3">
                                    <label for="input-desc" class="form-label">Описание</label>
                                    <textarea class="form-control" name="description" id="input-desc" placeholder="Введите описание для вашего блюда" rows="3"></textarea>
                                </div>
                                {{-- Изображение --}}
                                <div class="mb-3">
                                    <label for="image-input" class="form-label">Изображение</label>
                                    <input type="file" accept="image/*" name="image" id="image-input" class="form-control" required>
                                </div>
                                {{-- Цена --}}
                                <label for="input-price" class="form-label">Цена</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" name="price" id="input-price" placeholder="Укажите цену блюда">
                                    <span class="input-group-text">сом</span>
                                </div>
                                {{-- Это веганское --}}
                                <div class="col-auto form-check">
                                    <input class="form-check-input" name="is_vegan" type="checkbox" id="check-is-vegan">
                                    <label class="form-check-label" id="check-is-vegan-label" for="check-is-vegan">Это веганское блюдо</label>
                                </div>
                                {{-- Это специальное --}}
                                <div class="col-auto form-check">
                                    <input class="form-check-input" name="is_special" type="checkbox" id="check-is-special">
                                    <label class="form-check-label" id="check-is-special-label" for="check-is-special">Это специальное блюдо</label>
                                </div>
                                {{-- Публикация --}}
                                <div class="col-auto form-check form-switch mt-3">
                                    <input class="form-check-input" name="is_publish" type="checkbox" id="check-is-publish">
                                    <label class="form-check-label" id="check-is-publish-label" for="check-is-publish">Опубликовать блюдо</label>
                                </div>
                            </div>
                            <div class="d-flex flex-column justify-content-start w-50 mt-3 flex-column">
                                <label for="input-topping" class="form-label">Категории</label>
                                <div class="mb-3 d-flex" id="category-list">
                                    <select id="input-category" class="form-select me-2">
                                        @foreach($categories as $category)
                                            @if($category->is_publish == true)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <button type="button" id="add-category" class="btn d-block btn-success form-control">Добавить Категорию</button>
                                </div>
                                <div class="flex-wrap d-flex mb-5" id="category-records"></div>



                                <label for="input-topping" class="form-label mt-5">Ингредиенты</label>
                                <div class="mb-3 d-flex" id="topping-list">
                                    <select id="input-topping" class="form-select me-2">
                                        @foreach($toppings as $topping)
                                            <option data-unit="{{$topping->unit->getAbbreviationUnit()}}" value="{{$topping->id}}">{{$topping->name}}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" id="add-topping" class="btn d-block btn-success form-control">Добавить ингредиент</button>
                                </div>
                                <div class="flex-wrap d-flex" id="topping-records"></div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="submit" id="create-record" class="btn btn-success">Добавить запись</button>
                            <button type="button" id="cancel-record" class="btn btn-danger">Отмена</button>
                        </div>
                    </form>
                </td>
            </tr>
            </tfoot>
        @endif
    </table>

    <div class="col-12">{{ $foods->links('vendor.pagination.bootstrap-4') }}</div>




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
                $('#check-is-publish').click(function () {
                    if ($(this).is(':checked')) {
                        $('#check-is-publish-label').html('Опубликовано');
                    } else {
                        $('#check-is-publish-label').html('Не опубликовано');
                    }
                })


                // Добавить блюдо
                $('#add-category').on('click', function () {
                    var selectedCategory = $('#input-category option:selected');
                    var categoryId = selectedCategory.val();
                    var categoryName = selectedCategory.text();


                    // Проверка, чтобы ингредиент был выбран
                    if (categoryId && categoryName) {
                        // Добавление выбранного ингредиента в форму
                        var categoryList = $('#category-records');
                        categoryList.append('' +
                            '<div style="width: 48%; margin-left: 5px; margin-bottom: 3px; height: fit-content" class="input-group">' +
                            '<button class="btn btn-danger delete-category" type="button"><i class="fa-solid fa-xmark"></i></button>' +
                            '<input class="form-control" type="hidden" name="category" value="' + categoryId + '">' +
                            '<span class="input-group-text category-name " style="width: 85%">' + categoryName + '</span>' +
                            '</div>');

                        // Удаление выбранного ингредиента из списка
                        selectedCategory.remove();

                    }
                    if ($('#input-category option').length === 0) {
                        $('#category-list').addClass('d-none');
                    }
                });

                $(document).on('click', '.delete-category', function (e) {
                    e.preventDefault();
                    var categoryContainer = $(this).closest('.input-group');
                    var categoryName = categoryContainer.find('span.category-name').text();


                    // Вернуть ингредиент обратно в список
                    $('#input-category').append('<option  value="' + categoryName + '">' + categoryName + '</option>');

                    // Удалить запись об ингредиенте из формы
                    categoryContainer.remove();

                    $('#category-list').removeClass('d-none');
                });


                // Добавить ингредиенты
                $('#add-topping').on('click', function () {
                    var selectedTopping = $('#input-topping option:selected');
                    var toppingId = selectedTopping.val();
                    var toppingName = selectedTopping.text();
                    var toppingUnit = selectedTopping.data('unit');


                    // Проверка, чтобы ингредиент был выбран
                    if (toppingId && toppingName) {
                        // Добавление выбранного ингредиента в форму
                        var toppingList = $('#topping-records');
                        toppingList.append('' +
                            '<div style="width: 48%; margin-left: 5px; margin-bottom: 3px; height: fit-content" class="input-group">' +
                            '<button class="btn btn-danger delete-topping" type="button"><i class="fa-solid fa-xmark"></i></button>' +
                            '<span class="w-50 input-group-text topping-name" style="white-space: pre-wrap; text-align: start;">' + toppingName + '</span>' +
                            '<input class="form-control" type="hidden" name="topping" value="' + toppingId + '">' +
                            '<input class="form-control" type="text" name="quantity" placeholder="Вес / Количество">' +
                            '<span class="input-group-text topping-unit">' + toppingUnit + '</span>' +
                            '</div>');

                        // Удаление выбранного ингредиента из списка
                        selectedTopping.remove();

                    }
                    if ($('#input-topping option').length === 0) {
                        $('#topping-list').addClass('d-none');
                    }
                });

                $(document).on('click', '.delete-topping', function (e) {
                    e.preventDefault();
                    var toppingContainer = $(this).closest('.input-group');
                    var toppingName = toppingContainer.find('span.topping-name').text();
                    var toppingInput = toppingContainer.find('input[name="topping[]"]');
                    var toppingUnit = toppingContainer.find('span.topping-unit').text();

                    // Вернуть ингредиент обратно в список
                    $('#input-topping').append('<option data-unit="' + toppingUnit + '" value="' + toppingName + '">' + toppingName + '</option>');

                    // Удалить запись об ингредиенте из формы
                    toppingContainer.remove();

                    $('#topping-list').removeClass('d-none');
                });


                // Кнопка создание новой записи
                $('#create-record').click(function (e) {
                    e.preventDefault();
                    $('#create-record').prop('disabled', true); //Отключить кнопку пока не выполниться действие

                    var formData = new FormData();
                    formData.append('image', $('#image-input')[0].files[0]);
                    formData.append('name', $('#input-name').val());
                    formData.append('description', $('#input-desc').val());
                    formData.append('price', $('#input-price').val());

                    var categories = [];
                    $('input[name="category"]').each(function () {
                        categories.push($(this).val());
                    });

                    for (var i = 0; i < categories.length; i++) {
                        formData.append('categories[]', categories[i]);
                    }


                    var toppings = [];
                    $('input[name="topping"]').each(function () {
                        toppings.push($(this).val());
                    });

                    for (var i = 0; i < toppings.length; i++) {
                        formData.append('toppings[]', toppings[i]);
                    }

                    var quantities = [];
                    $('input[name="quantity"]').each(function () {
                        quantities.push($(this).val());
                    });

                    for (var i = 0; i < quantities.length; i++) {
                        formData.append('quantity_toppings[]', quantities[i]);
                    }

                    formData.append('is_vegan', $('#check-is-vegan').is(':checked') ? 1 : 0);
                    formData.append('is_special', $('#check-is-special').is(':checked') ? 1 : 0);
                    formData.append('is_publish', $('#check-is-publish').is(':checked') ? 1 : 0);

                    $.ajax({
                        url: '{{route('food.store')}}',
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
                                sort()
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
                                var lastPage = {{ $foods->lastPage() }};
                                if ($('.table-container').length >= {{ $foods->perPage() }}) {
                                    window.location.href = "{{ route('food.index') }}?page=" + lastPage;
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
                                $.each(data.errors, function (field, errors) {
                                    // Найти элемент формы, соответствующий полю, содержащему ошибку
                                    var input = $('form [name="' + field + '"]');
                                    // Добавить класс is-invalid для этого элемента
                                    input.addClass('is-invalid');
                                    // Вывести сообщение об ошибке
                                    var errorMessages = '<div class="invalid-feedback">';
                                    $.each(errors, function (index, error) {
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

                })


                // Скрипт изменения записи
                $(document).ready(function () {

                    // Появление формы изменения записи
                    $(document).on('click', '.more-about', function (e) {
                        e.preventDefault();

                        var record = $(this); // текущая запись
                        var id = record.data('id'); // получаем ID текущей записи

                        $('#more-about-record-' + id).removeClass('d-none');
                        if ($('#update-input-category-' + id + ' option').length === 0) {
                            $('#update-category-list-' + id).addClass('d-none');
                        }
                        if ($('#update-input-topping-' + id + ' option').length === 0) {
                            $('#update-topping-list-' + id).addClass('d-none');
                        }
                        $('#more-about-' + id).addClass('d-none');
                        $('#hide-more-about-' + id).removeClass('d-none');

                        $('.hide-more-about').click(function (e) {
                            e.preventDefault();
                            var record = $(this); // текущая запись
                            var id = record.data('id'); // получаем ID текущей записи

                            $('#more-about-' + id).removeClass('d-none');
                            $('#hide-more-about-' + id).addClass('d-none');

                            $('#more-about-record-' + id).addClass('d-none');
                            $('#update-record-' + id).addClass('d-none');
                        })

                        // Форма изменения записи
                        $(document).on('click', '.update-record', function (e) {
                            e.preventDefault();
                            var record = $(this); // текущая запись
                            var id = record.data('id'); // получаем ID текущей записи

                            $('#more-about-record-' + id).addClass('d-none');
                            $('#update-record-' + id).removeClass('d-none');
                        });


                        // Добавить блюдо
                        $('#update-add-category-' + id).on('click', function () {
                            var selectedCategory = $('#update-input-category-' + id + ' option:selected');
                            var categoryId = selectedCategory.val();
                            var categoryName = selectedCategory.text();


                            // Проверка, чтобы категория был выбрана
                            if (categoryId && categoryName) {
                                // Добавление выбранного блюда в форму
                                var categoryList = $('#update-category-records-' + id);
                                categoryList.append('' +
                                    '<div style="width: 48%; margin-left: 5px; margin-bottom: 3px; height: fit-content" class="input-group update-category-input-group-' + id + '">' +
                                    '<button class="btn btn-danger update-delete-category-' + id + '" type="button"><i class="fa-solid fa-xmark"></i></button>' +
                                    '<input class="form-control" type="hidden" name="update-category-' + id + '" value="' + categoryId + '">' +
                                    '<span class="input-group-text update-category-name-' + id + '" style="width: 85%">' + categoryName + '</span>' +
                                    '</div>');

                                // Удаление выбранного блюда из списка
                                selectedCategory.remove();

                            }
                            if ($('#update-input-category-' + id + ' option').length === 0) {
                                $('#update-category-list-' + id).addClass('d-none');
                            }
                        });

                        $(document).on('click', '.update-delete-category-' + id, function (e) {
                            e.preventDefault();
                            var categoryContainer = $(this).closest('.update-category-input-group-' + id);
                            var categoryName = categoryContainer.find('span.update-category-name-' + id).text();


                            // Вернуть блюдо обратно в список
                            $('#update-input-category-' + id).append('<option  value="' + categoryName + '">' + categoryName + '</option>');

                            // Удалить запись об блюда из формы
                            categoryContainer.remove();

                            $('#update-category-list-' + id).removeClass('d-none');
                        });


                        // Добавить ингредиенты
                        $('#update-add-topping-' + id).on('click', function () {
                            var selectedTopping = $('#update-input-topping-' + id + ' option:selected');
                            var toppingId = selectedTopping.val();
                            var toppingName = selectedTopping.text();
                            var toppingUnit = selectedTopping.data('unit');


                            // Проверка, чтобы ингредиент был выбран
                            if (toppingId && toppingName) {
                                // Добавление выбранного ингредиента в форму
                                var toppingList = $('#update-topping-records-' + id);
                                toppingList.append('' +
                                     '<div style="width: 48%; margin-left: 5px; margin-bottom: 3px; height: fit-content" class="input-group update-topping-input-group-' + id + '">' +
                                    '<button class="btn btn-danger update-delete-topping-' + id + '" type="button"><i class="fa-solid fa-xmark"></i></button>' +
                                    '<span class="w-50 input-group-text update-topping-name-' + id + '" style="white-space: pre-wrap; text-align: start;">' + toppingName + '</span>' +
                                    '<input class="form-control" type="hidden" name="update-topping-' + id + '" value="' + toppingId + '">' +
                                    '<input class="form-control" type="number" name="update-quantity-' + id + '" placeholder="Вес / Количество">' +
                                    '<span class="input-group-text update-topping-unit-' + id + '">' + toppingUnit + '</span>' +
                                    '</div>');

                                // Удаление выбранного ингредиента из списка
                                selectedTopping.remove();

                            }
                            if ($('#update-input-topping-' + id + ' option').length === 0) {
                                $('#update-topping-list-' + id).addClass('d-none');
                            }
                        });

                        $(document).on('click', '.update-delete-topping-' + id, function (e) {
                            e.preventDefault();
                            var toppingContainer = $(this).closest('.update-topping-input-group-' + id);
                            var toppingName = toppingContainer.find('span.update-topping-name-' + id).text();
                            var toppingInput = toppingContainer.find('input[name="topping[]"]');
                            var toppingUnit = toppingContainer.find('span.update-topping-unit-' + id).text();

                            // Вернуть ингредиент обратно в список
                            $('#update-input-topping-' + id).append('<option data-unit="' + toppingUnit + '" value="' + toppingName + '">' + toppingName + '</option>');

                            // Удалить запись об ингредиенте из формы
                            toppingContainer.remove();

                            $('#update-topping-list-' + id).removeClass('d-none');
                        });


                        // Обновление записи
                        $('#complete-update-' + id).click(function (e) {
                            e.preventDefault();

                            var formData = new FormData();
                            formData.append('id', id);
                            formData.append('image', $('#update-image-input-' + id)[0].files[0]);
                            formData.append('name', $('#update-input-name-' + id).val());
                            formData.append('description', $('#update-input-desc-' + id).val());
                            formData.append('price', $('#update-input-price-' + id).val());

                            var categories = [];
                            $('input[name="update-category-' + id + '"]').each(function () {
                                categories.push($(this).val());
                            });

                            for (var i = 0; i < categories.length; i++) {
                                formData.append('categories[]', categories[i]);
                            }


                            var toppings = [];
                            $('input[name="update-topping-' + id + '"]').each(function () {
                                toppings.push($(this).val());
                            });

                            for (var i = 0; i < toppings.length; i++) {
                                formData.append('toppings[]', toppings[i]);
                            }

                            var quantities = [];
                            $('input[name="update-quantity-' + id + '"]').each(function () {
                                quantities.push($(this).val());
                            });

                            for (var i = 0; i < quantities.length; i++) {
                                formData.append('quantity_toppings[]', quantities[i]);
                            }

                            formData.append('is_vegan', $('#update-check-is-vegan-' + id).is(':checked') ? 1 : 0);
                            formData.append('is_special', $('#update-check-is-special-' + id).is(':checked') ? 1 : 0);
                            formData.append('is_publish', $('#update-check-is-publish-' + id).is(':checked') ? 1 : 0);

                            $.ajax({
                                url: '{{route('food.update')}}',
                                method: 'POST',
                                contentType: false,
                                processData: false,
                                data: formData,

                                success: function (data) {
                                    // Удаление класса is-invalid и сообщений об ошибках
                                    $('form .is-invalid').removeClass('is-invalid');
                                    $('.invalid-feedback').remove();
                                    if (data.success) {

                                        $('#update-record-form-' + id).remove(); //Скрытие формы
                                        $('#record-' + id).replaceWith(data.record); //Замена старой записи новой
                                        sort()
                                        // Сообщение об успехе
                                        var recordCount = data.record_id; //Получение номера записи для отображения в сообщении
                                        $('#alert').html(`
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Запись №${recordCount}!</strong> Была успешно изменина!
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>`)
                                    } else {

                                        // Сообщение об ошибке
                                        $('#alert').html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Ошибка!</strong> Не удалось добавить запись!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`)


                                        // Если есть ошибки валидации, отобразить их
                                        $.each(data.errors, function (field, errors) {
                                            // Найти элемент формы, соответствующий полю, содержащему ошибку
                                            var input = $('#update-form-' + id + ' [name="' + field + '"]');
                                            // Добавить класс is-invalid для этого элемента
                                            input.addClass('is-invalid');
                                            // Вывести сообщение об ошибке
                                            var errorMessages = '<div class="invalid-feedback text-start">';
                                            $.each(errors, function (index, error) {
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
                            $('#update-record-' + id).addClass('d-none'); //Спрятать форму
                            $('#more-about-record-' + id).removeClass('d-none'); //Показать старую запись


                        });
                    });
                });
            })
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
                        url: '{{route('food.sort')}}',
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
                            $('#more-about-record-' + id).remove();
                            $('#update-record-' + id).remove();
                            // Скрываем модальное окно

                            deleteModal.modal('toggle');
                            sort();

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
    @endpush
    @section('delete')
    @endsection


@endsection
