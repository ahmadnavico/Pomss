<?php

namespace App\Enums;

enum PostStatusEnum: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case IN_REVIEW = 'in_review';
    case REVISION = 'revision';
    case ARCHIVED = 'archived';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PUBLISHED => 'Published',
            self::IN_REVIEW => 'In Review',
            self::REVISION => 'Revision',
            self::ARCHIVED => 'Archived',
            self::REJECTED => 'Rejected',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'bg-gray-200 text-gray-800 dark:bg-gray-800/30 dark:text-gray-500',
            self::PUBLISHED => 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500',
            self::IN_REVIEW => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-500',
            self::REVISION => 'bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-500',
            self::ARCHIVED => 'bg-gray-100 text-gray-800 dark:bg-gray-800/30 dark:text-gray-500',
            self::REJECTED => 'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500',
        };
    }
}
