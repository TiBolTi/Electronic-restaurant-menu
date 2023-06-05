{{--Модальное окно для удаления--}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Подтвердите удаление</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Вы действительно хотите удалить этот элемент?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" id="confirm-delete" class="btn btn-danger">Удалить</button>
                </div>
            </form>
        </div>
    </div>
</div>
@yield('delete')

{{--Модальное окно для подтверждения действия--}}
<div class="modal fade" id="foodModal" tabindex="-1" aria-labelledby="foodModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="deleteForm" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Подтвердите заказ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-body">
                </div>
                <div class="modal-footer">
                    {{--                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>--}}
                </div>
            </form>
        </div>
    </div>
</div>
