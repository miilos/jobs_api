<?php

namespace Milos\JobsApi\DTOs;

class CommentDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $userId,
        public readonly string $jobId,
        public readonly string $text,
        public readonly \DateTime $createdAt,
    ) {}
}