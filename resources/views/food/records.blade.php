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
                <label for="input-topping" class="form-label">Блюда</label>
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
                        <label for="input-topping" class="form-label">Блюда</label>
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
                                        <span class="input-group-text update-topping-unit-{{$food->id}}">{{$topping->unit->getAbbreviationUnit()}}</span>
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
