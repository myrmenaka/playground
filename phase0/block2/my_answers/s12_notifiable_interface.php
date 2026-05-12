<?php

declare(strict_types=1);

interface Notifiable
{
    public function notify(): string;
}