<?php

namespace managers;

class AiManager
{
    // private string $baseUrl = "http://hive-ai:5000/";
    private string $baseUrl = "http://localhost:5000/";

    public function getMove($move_number, $hand, $board)
    {
        $data = [
            "move_number" => $move_number,
            "hand" => $hand,
            "board" => $board,
        ];

        $options = [
            "http" => [
                "method" => "POST",
                "header" => "Content-Type: application/json\r\n",
                "content" => json_encode($data)
            ]
        ];

        $context = stream_context_create($options);

        $result = file_get_contents($this->baseUrl, false, $context);

        return json_decode($result, true);
    }
}