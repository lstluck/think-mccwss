<?php

namespace mccwss;

interface Driver
{
    public function watch(callable $callback);
}
