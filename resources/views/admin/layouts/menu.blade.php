<li class="nav-item">
    <a href="{{route('admin.main')}}" class="nav-link">
        <i class="nav-icon fa fa-home"></i>

        <p>
            Главная
        </p>
    </a>
</li>
{{--    <li class="nav-item">--}}
{{--        <a href="{{route('admin.product.index')}}" class="nav-link">--}}
{{--            <i class="nav-icon fas fa-th"></i>--}}
{{--            <p>--}}
{{--                Продукты--}}
{{--            </p>--}}
{{--        </a>--}}
{{--    </li>--}}
    <li class="nav-item">
        <a href="{{route('admin.user.index')}}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>
                 Пользователи
            </p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{route('admin.counteragent.index')}}" class="nav-link">
            <i class="nav-icon fas fa-shopping-bag"></i>
            <p>
                Контрагенты
            </p>
        </a>
    </li>
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

