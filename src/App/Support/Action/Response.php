<?php

namespace VenderaTradingCompany\App\Support\Action;

use VenderaTradingCompany\App\Support\Action\Response\Status;

class Response
{
    private mixed $status;
    private mixed $data;

    public function __construct(mixed $status, mixed $data = null)
    {
        $this->status = $status;
        $this->data = $data;
    }

    public static function done(mixed $data = null): Response
    {
        return new Response(Status::$done, $data);
    }

    public static function error(mixed $data = null): Response
    {
        return new Response(Status::$error, $data);
    }

    public function isDone(): bool
    {
        return $this->status == Status::$done;
    }

    public function getData()
    {
        return $this->data;
    }

    public function hasData()
    {
        return $this->data != null;
    }
}
