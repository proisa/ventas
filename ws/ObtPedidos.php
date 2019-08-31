<?php


$pedidos = [
    [
        'id'=>1,
        'mesa'=>'01',
        'tipo'=>'entrada',
        'dep'=>'cocina',
        'detalle'=>'Croquetas de pollo'
    ],
    [
        
        'id'=>2,
        'mesa'=>'03',
        'tipo'=>'plato fuerte',
        'dep'=>'cocina',
        'detalle'=>'Pechuga de pollo'
    ],
    [
        
        'id'=>6,
        'mesa'=>'03',
        'tipo'=>'plato fuerte',
        'dep'=>'bar',
        'detalle'=>'Agua Dasani'
    ]

];


$pedido = [
        'id'=>1,
        'mesa'=>'01',
        'tipo'=>'entrada',
        'dep'=>'cocina',
        'detalle'=>'Croquetas de pollo'
];

$pd = [
    '{"id":1,"mesa":"01","tipo":"entrada","dep":"cocina","detalle":"Croquetas de pollo"}',
    '{"id":1,"mesa":"01","tipo":"entrada","dep":"cocina","detalle":"Croquetas de pollo"}',
    '{"id":1,"mesa":"01","tipo":"entrada","dep":"cocina","detalle":"Croquetas de pollo"}'
];



echo '<pre>';
echo print_r($pd);
echo '</pre>';