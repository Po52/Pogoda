<?php

namespace App\Entity;

class TextInput{
    private ?string $text = null;

    public function getText():?string{
        return $this->text;
    }

    public function setText(string $text):void{
        $this->text = $text;
    }


}