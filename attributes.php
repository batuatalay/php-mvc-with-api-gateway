<?php
#[Attribute(Attribute::TARGET_METHOD)]
class Get {
    public function __construct(public string $path) {}
}

#[Attribute(Attribute::TARGET_METHOD)]
class Post {
    public function __construct(public string $path) {}
}

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class Middleware {
    public function __construct(public string $name) {}
}

#[Attribute(Attribute::TARGET_CLASS)]
class Prefix {
    public function __construct(public string $prefix) {}
}
?>