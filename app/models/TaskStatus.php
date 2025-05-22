<?php


declare(strict_types=1);

enum TaskStatus: string {
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';

    
}

?>