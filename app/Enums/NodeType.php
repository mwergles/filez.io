<?php

namespace App\Enums;

enum NodeType: string
{
    case FOLDER = 'folder';
    case FILE = 'file';
}
