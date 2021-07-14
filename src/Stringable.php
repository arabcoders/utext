<?php

if (\PHP_VERSION_ID < 80000 && !class_exists('Stringable')) {
    interface Stringable
    {
        /**
         * @return string
         */
        public function __toString();
    }
}