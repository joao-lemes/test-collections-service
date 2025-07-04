<?php

declare(strict_types=1);

return [
    'unauthorized' => 'Não autorizado',
    'not_found' => [
        'default' => 'Item não encontrado',
        'reset_password_token' => 'Token para resetar a senha não encontrado',
        'account' => 'Conta não encontrada',
        'insufficient_balance' => 'Conta não possui saldo suficiente',
    ],
    'bad_request' => [
        'default' => 'Requisição ruim',
        'invalid_id' => 'Id inválido',
    ],
    'internal_server_error' => 'Erro do servidor interno',

    'queue' => [
        'email_sending' => 'Erro na fila de envio de email',
    ],

    'external_request' => [
        'error' => 'Requisição externa com erro',
        'invalid_uri' => 'Uri inválida',
        'invalid_method' => 'Método inválido',
    ],

    'existing_email_for_this_institution' => 'Email existente para esta instituição',

    'and_more_error' => '(e mais :amount erro)',
    'and_more_errors' => '(e mais :amount erros)',

    'invalid_signature' => 'Assinatura inválida',
    'error_decoding_base64' => 'Erro ao decodificar a base64',
];
