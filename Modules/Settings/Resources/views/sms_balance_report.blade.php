@if ($balance['code'] !=100)
    <p style="color:red">
        Ошибка: {{ $balance['description'] }}
    </p>
@else
    @if ($balance['balance'] < 100)
        <p style="color:orangered">
            Баланс на {{date('d.m.Y H:i')}} всего {{ $balance['balance'] }} руб.
            Пополните баланс в <a href="https://sms.ru/?panel=my">личном кабинете sms.ru</a>
        </p>
    @else
        Баланс на {{date('d.m.Y H:i')}} составляет {{ $balance['balance'] }} руб.
    @endif
@endif
