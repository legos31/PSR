<?php

namespace Framework\Authentication;

interface UserServiceInterface
{
    public function findByEmail(string $email): ?AuthUserInterface;
}
