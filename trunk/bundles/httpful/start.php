<?php

Autoloader::namespaces(array(
    'Httpful' => Bundle::path('httpful')
));

$handlers = array(
    \Httpful\Mime::CSV   => new \Httpful\Handlers\CsvHandler(),
    \Httpful\Mime::FORM  => new \Httpful\Handlers\FormHandler(),
    \Httpful\Mime::JSON  => new \Httpful\Handlers\JsonHandler(),
    \Httpful\Mime::XHTML => new \Httpful\Handlers\XHtmlHandler(),
    \Httpful\Mime::XML   => new \Httpful\Handlers\XmlHandler(),
);

foreach ($handlers as $mime => $handler) {
    Httpful\Httpful::register($mime, $handler);
}

class_alias('Httpful\Request', 'Httpful');