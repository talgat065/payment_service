<?php

$router->group(['prefix' => 'api'], function() use ($router) {

    // Public endpoints
    $router->get('/paymentMethods', 'PaymentMethodsController@index');

    // Private endpoints
    $router->group(['prefix' => 'private'], function() use ($router) {
        $router->get('/invoice/{id}', 'InvoiceController@show');
        $router->get('/invoices', 'InvoiceController@filterByDate');
        $router->post('/invoice', 'InvoiceController@store');
        $router->put('/invoice', 'InvoiceController@update');
        $router->post('/payment', 'PaymentController@store');
    });
});


