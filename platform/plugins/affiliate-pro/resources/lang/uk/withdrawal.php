<?php

return [
    'name' => 'Виведення', 'view' => 'Переглянути виведення #:id', 'affiliate' => 'Партнер', 'amount' => 'Сума', 'status' => 'Статус',
    'payment_method' => 'Спосіб оплати', 'payment_details' => 'Деталі оплати', 'notes' => 'Примітки', 'created_at' => 'Створено',
    'approve' => 'Схвалити', 'reject' => 'Відхилити', 'approve_success' => 'Виведення успішно схвалено', 'reject_success' => 'Виведення успішно відхилено',
    'approve_withdrawal' => 'Схвалити виведення', 'reject_withdrawal' => 'Відхилити виведення', 'approve_withdrawal_confirmation' => 'Ви впевнені, що хочете схвалити виведення #:id? Цю дію неможливо скасувати.',
    'reject_withdrawal_confirmation' => 'Ви впевнені, що хочете відхилити виведення #:id? Цю дію неможливо скасувати.',
    'statuses' => ['pending' => 'В очікуванні', 'processing' => 'Обробка', 'approved' => 'Схвалено', 'rejected' => 'Відхилено', 'canceled' => 'Скасовано'],
    'request' => 'Запит на виведення', 'history' => 'Історія виведень', 'no_withdrawals' => 'Виведення не знайдено.', 'withdrawal_id' => 'ID виведення',
    'date' => 'Дата', 'amount_required' => 'Сума виведення обов\'язкова.', 'amount_numeric' => 'Сума виведення має бути числом.',
    'amount_min' => 'Сума виведення має бути принаймні 0.', 'payment_method_required' => 'Спосіб оплати обов\'язковий.',
    'payment_details_required' => 'Деталі оплати обов\'язкові.', 'account_not_approved' => 'Ваш партнерський обліковий запис ще не схвалений.',
    'minimum_amount' => 'Мінімальна сума виведення становить :amount.', 'insufficient_balance' => 'У вас недостатньо балансу для цього виведення.',
    'request_submitted' => 'Ваш запит на виведення успішно надіслано.', 'submit_request' => 'Надіслати запит на виведення',
    'available_balance' => 'Доступний баланс', 'enter_amount' => 'Введіть суму', 'select_payment_method' => 'Виберіть спосіб оплати',
    'payment_details_placeholder' => 'Введіть деталі оплати (наприклад, електронна пошта PayPal, дані банківського рахунку)',
    'no_payment_methods_available' => 'Наразі способи оплати недоступні. Будь ласка, зв\'яжіться з адміністратором.',
    'bank_transfer' => 'Банківський переказ', 'paypal' => 'PayPal', 'stripe' => 'Stripe', 'other' => 'Інше',
    'bank_information' => 'Банківська інформація', 'paypal_id' => 'PayPal ID', 'payout_payment_methods' => ['bank_transfer' => 'Банківський переказ', 'paypal' => 'PayPal', 'stripe' => 'Stripe', 'other' => 'Інше'],
];
