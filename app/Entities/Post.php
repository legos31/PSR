<?php


namespace App\Entities;


use Framework\Dbal\Entity;

class Post extends Entity
{
    public function __construct(

        private string $title,
        private string $body,
        private ?int $id,
        private ?\DateTimeImmutable $createdAt
    )
    {
    }

    public static function create(

        string $title,
        string $body,
        int $id = null,
        \DateTimeImmutable$createAt = null
    ):static
    {
        return new static($title, $body, $id, $createAt ?? new \DateTimeImmutable());
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param int|null $id
     * @return Post
     */
    public function setId(?int $id): Post
    {
        $this->id = $id;
        return $this;
    }
}