<?php

return [
    'name' => 'Dvigi', 'view' => 'Poglej dvig #:id', 'affiliate' => 'Partner', 'amount' => 'Znesek', 'status' => 'Status',
    'payment_method' => 'Način plačila', 'payment_details' => 'Podrobnosti plačila', 'notes' => 'Opombe', 'created_at' => 'Ustvarjeno',
    'approve' => 'Odobri', 'reject' => 'Zavrni', 'approve_success' => 'Dvig uspešno odobren', 'reject_success' => 'Dvig uspešno zavrnjen',
    'approve_withdrawal' => 'Odobri dvig', 'reject_withdrawal' => 'Zavrni dvig', 'approve_withdrawal_confirmation' => 'Ali ste prepričani, da želite odobriti dvig #:id? Tega dejanja ni mogoče razveljaviti.',
    'reject_withdrawal_confirmation' => 'Ali ste prepričani, da želite zavrniti dvig #:id? Tega dejanja ni mogoče razveljaviti.',
    'statuses' => ['pending' => 'Na čakanju', 'processing' => 'Obdelovanje', 'approved' => 'Odobreno', 'rejected' => 'Zavrnjeno', 'canceled' => 'Preklicano'],
    'request' => 'Zahteva za dvig', 'history' => 'Zgodovina dvigov', 'no_withdrawals' => 'Ni najdenih dvigov.', 'withdrawal_id' => 'ID dviga',
    'date' => 'Datum', 'amount_required' => 'Znesek dviga je obvezen.', 'amount_numeric' => 'Znesek dviga mora biti številka.',
    'amount_min' => 'Znesek dviga mora biti vsaj 0.', 'payment_method_required' => 'Način plačila je obvezen.',
    'payment_details_required' => 'Podrobnosti plačila so obvezne.', 'account_not_approved' => 'Vaš partnerski račun še ni odobren.',
    'minimum_amount' => 'Minimalni znesek dviga je :amount.', 'insufficient_balance' => 'Nimate dovolj stanja za ta dvig.',
    'request_submitted' => 'Vaša zahteva za dvig je bila uspešno oddana.', 'submit_request' => 'Oddaj zahtevo za dvig',
    'available_balance' => 'Razpoložljivo stanje', 'enter_amount' => 'Vnesite znesek', 'select_payment_method' => 'Izberite način plačila',
    'payment_details_placeholder' => 'Vnesite podrobnosti plačila (npr. PayPal e-pošta, podatki bančnega računa)',
    'no_payment_methods_available' => 'Trenutno ni razpoložljivih načinov plačila. Prosim kontaktirajte administratorja.',
    'bank_transfer' => 'Bančno nakazilo', 'paypal' => 'PayPal', 'stripe' => 'Stripe', 'other' => 'Drugo',
    'bank_information' => 'Bančne informacije', 'paypal_id' => 'PayPal ID', 'payout_payment_methods' => ['bank_transfer' => 'Bančno nakazilo', 'paypal' => 'PayPal', 'stripe' => 'Stripe', 'other' => 'Drugo'],
];
