<?php

namespace Milos\JobsApi\Mappers;

use Milos\JobsApi\DTOs\CommentDTO;

class CommentMapper
{
    public static function toDTO(array $comment): CommentDTO
    {
        return new CommentDTO(
            id: $comment['comment_id'],
            userId: $comment['user_id'],
            jobId: $comment['job_id'],
            text: $comment['text'],
            createdAt: new \DateTime($comment['createdAt']),
        );
    }
}