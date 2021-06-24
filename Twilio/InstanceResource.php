<?php


namespace Twilio;


class InstanceResource {
    protected $version;
    protected $context;
    protected $properties = [];
    protected $solution = [];

    public function __construct(Version $version) {
        $this->version = $version;
    }

    public function toArray() {
        return $this->properties;
    }

    public function __toString() {
        return '[InstanceResource]';
    }

    public function __isset($name) {
        return \array_key_exists($name, $this->properties);
    }
}
