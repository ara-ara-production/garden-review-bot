<?php

namespace App\Enums;

enum MessageToUser: string
{
    case SuccesfulSubcribe = 'Вы успешно подписались на бота';
    case UserNotRegister = 'Пользователь не зарегестрирован для этого бота';
    case NoUsername = 'Невозможно определить пользователя, отсутствует имя пользователя';
    case Error = 'Возникла непредвиденная ошибка! Свяжитесь с @Tamanit для решения проблемы';

    case NoWorkNeeded = 'Ознакомился, мер не требуется';
}
