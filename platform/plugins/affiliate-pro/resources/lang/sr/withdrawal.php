<?php

return [
    'name' => 'Повлачења', 'view' => 'Прегледај повлачење #:id', 'affiliate' => 'Партнер', 'amount' => 'Износ', 'status' => 'Статус',
    'payment_method' => 'Начин плаћања', 'payment_details' => 'Детаљи плаћања', 'notes' => 'Белешке', 'created_at' => 'Креирано',
    'approve' => 'Одобри', 'reject' => 'Одбиј', 'approve_success' => 'Повлачење успешно одобрено', 'reject_success' => 'Повлачење успешно одбијено',
    'approve_withdrawal' => 'Одобри повлачење', 'reject_withdrawal' => 'Одбиј повлачење', 'approve_withdrawal_confirmation' => 'Да ли сте сигурни да желите одобрити повлачење #:id? Ова акција се не може опозвати.',
    'reject_withdrawal_confirmation' => 'Да ли сте сигурни да желите одбити повлачење #:id? Ова акција се не може опозвати.',
    'statuses' => ['pending' => 'На чекању', 'processing' => 'Обрада', 'approved' => 'Одобрено', 'rejected' => 'Одбијено', 'canceled' => 'Отказано'],
    'request' => 'Захтев за повлачење', 'history' => 'Историја повлачења', 'no_withdrawals' => 'Нису пронађена повлачења.', 'withdrawal_id' => 'ID повлачења',
    'date' => 'Датум', 'amount_required' => 'Износ повлачења је обавезан.', 'amount_numeric' => 'Износ повлачења мора бити број.',
    'amount_min' => 'Износ повлачења мора бити најмање 0.', 'payment_method_required' => 'Начин плаћања је обавезан.',
    'payment_details_required' => 'Детаљи плаћања су обавезни.', 'account_not_approved' => 'Ваш партнерски налог још није одобрен.',
    'minimum_amount' => 'Минимални износ повлачења је :amount.', 'insufficient_balance' => 'Немате довољно стања за ово повлачење.',
    'request_submitted' => 'Ваш захтев за повлачење је успешно послат.', 'submit_request' => 'Пошаљи захтев за повлачење',
    'available_balance' => 'Доступно стање', 'enter_amount' => 'Унесите износ', 'select_payment_method' => 'Изаберите начин плаћања',
    'payment_details_placeholder' => 'Унесите детаље плаћања (нпр. PayPal е-пошта, подаци о банковном рачуну)',
    'no_payment_methods_available' => 'Тренутно нису доступни начини плаћања. Молимо контактирајте администратора.',
    'bank_transfer' => 'Банковни пренос', 'paypal' => 'PayPal', 'stripe' => 'Stripe', 'other' => 'Друго',
    'bank_information' => 'Банковне информације', 'paypal_id' => 'PayPal ID', 'payout_payment_methods' => ['bank_transfer' => 'Банковни пренос', 'paypal' => 'PayPal', 'stripe' => 'Stripe', 'other' => 'Друго'],
];
