<li class="nav-item">
    <a href="{{route('admin.main')}}" class="nav-link">
        <i class="nav-icon fa fa-home"></i>

        <p>
            Главная
        </p>
    </a>
</li>
@if(Auth::user()->id == 1)
    <li class="nav-item">
        <a href="{{route('admin.brand.index')}}" class="nav-link">
            <i class="nav-icon fas fa-building"></i>
            <p>
                Бренды
            </p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{route('admin.product.index')}}" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
                Продукты
            </p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{route('admin.user.index')}}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>
                Пользователи
            </p>
        </a>
    </li>
@endif

    <li class="nav-item">
        <a href="{{route('admin.store.index')}}" class="nav-link">
            <i class="nav-icon fas fa-shopping-basket"></i>
            <p>
                Торговые точки
            </p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{route('admin.order.index')}}" class="nav-link">
            <i class="nav-icon fas fa-tasks"></i>
            <p>
                Продажа
            </p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{route('admin.order.productIndex')}}" class="nav-link">
            <i class="nav-icon fas fa-tasks"></i>
            <p>
                Продажа 2
            </p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{route('admin.refund.index')}}" class="nav-link">
            <i class="nav-icon fas fa-tasks"></i>
            <p>
                Возврат
            </p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{route('admin.refundProducer.index')}}" class="nav-link">
            <i class="nav-icon fas fa-tasks"></i>
            <p>
                Возврат поставщику
            </p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{route('admin.receipt.index')}}" class="nav-link">
            <i class="nav-icon fas fa-tasks"></i>
            <p>
                Поступление
            </p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{route('admin.moving.index')}}" class="nav-link">
            <i class="nav-icon fas fa-tasks"></i>
            <p>
                Перемещение
            </p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{route('admin.reject.index')}}" class="nav-link">
            <i class="nav-icon fas fa-tasks"></i>
            <p>
                Списание
            </p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{route('admin.inventory.index')}}" class="nav-link">
            <i class="nav-icon fas fa-tasks"></i>
            <p>
                Инвентаризация
            </p>
        </a>
    </li>

