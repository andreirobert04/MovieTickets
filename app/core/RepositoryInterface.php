<?php

interface RepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): ?array;
}
