<?php

$router->group(['prefix' => 'api'], function() use ($router) {

    // Public endpoints
    $router->get('/paymentMethods', 'PaymentMethodsController@index');

    // Private endpoints
    $router->group(['prefix' => 'private'], function() use ($router) {
        $router->post('/invoice/create', 'InvoiceController@store');
        $router->put('/invoice/update', 'InvoiceController@update');
    });
});


