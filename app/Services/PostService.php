<?php


namespace App\Services;


use App\Entities\Post;
use Doctrine\DBAL\Connection;
use Framework\Dbal\EntityService;
use Framework\http\exceptions\NotFoundException;

class PostService
{
    public function __construct(private EntityService $entityService)
    {
    }

    public function save(Post $post):Post
    {
        $queryBuilder = $this->entityService->getConnection()->createQueryBuilder();
        $queryBuilder->insert('posts')
            ->values([
                'title' => ':title',
                'body' => ':body',
                'created_at' => ':createdAt'
            ])
            ->setParameters([
                'title'=> $post->getTitle(),
                'body' => $post->getBody(),
                'createdAt' => $post->getCreatedAt()->format('Y-m-d H:i:s')
            ])->executeQuery();
        $postId = $this->entityService->save($post);
        $post->setId($postId);
        return $post;
    }

    public function find($id): ?Post
    {
        $queryBuilder = $this->entityService->getConnection()->createQueryBuilder();
        $result = $queryBuilder->select('*')->from('posts')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery();

        $post = $result->fetchAssociative();

        if (!$post) {
            return null;
        }

        return $post = Post::create(
            $post['title'], $post['body'], $post['id'],
            new \DateTimeImmutable($post['created_at'])
        );
    }

    public function findOrFail($id): Post
    {
        $post = $this->find($id);
        if (is_null($post)) {
            throw new NotFoundException('not found');
        }
        return $post;
    }
}