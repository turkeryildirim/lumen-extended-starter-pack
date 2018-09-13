<?php

return [
    'serializers' => [
        'success' => \Flugg\Responder\Serializers\SuccessSerializer::class,
        'error' => \Flugg\Responder\Serializers\ErrorSerializer::class,
    ],
    'decorators' => [
        \Flugg\Responder\Http\Responses\Decorators\StatusCodeDecorator::class,
        \Flugg\Responder\Http\Responses\Decorators\SuccessFlagDecorator::class,
    ],
    'fallback_transformer' => \Flugg\Responder\Transformers\ArrayTransformer::class,
    'load_relations_parameter' => 'with',
    'filter_fields_parameter' => 'only',
    'recursion_limit' => 5,
    'error_message_files' => ['errors'],
];