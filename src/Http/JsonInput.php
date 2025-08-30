<?php
namespace App\Http;

use Symfony\Component\HttpFoundation\Request;

final class JsonInput
{
    public static function body(Request $request): array
    {
        $data = json_decode($request->getContent() ?: '{}', true);
        return is_array($data) ? $data : [];
    }
}
