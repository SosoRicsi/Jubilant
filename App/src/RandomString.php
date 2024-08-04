<?php 
    namespace Jubilant;

    class RandomString {
        private $customid;
        private $randomString;
        private $randomNums;

        public function generateRandomChars(int $lenght = 4) {
            $characters = '0123456789abcdefghijklmnopqrs092u3tuvwxyzaskdhfhf9882323ABCDEFGHIJKLMNksadf9044OPQRSTUVWXYZ';
            $charactersLen = strlen($characters);
            $this->randomString = '';

            for ($i = 0; $i < $lenght; $i++) {
                $this->randomString .= $characters[rand(0, $charactersLen - 1)];
            }

            return $this->randomString;
        }

        public function generateCustomString(string $prefix = null, int $sectionNum = 3, int $sectionLenght = 5) {
            for ($i = 0; $i < $sectionNum; $i++) {
                $this->customid .= $this->generateRandomChars($sectionLenght);
                if ($i < $sectionNum - 1) {
                    $this->customid .= "-";
                }
            }
            if($prefix != null) {
                return $prefix."::".$this->customid;
            } else {
                return $this->customid;
            }
        }

        public function generateCustomNums(string $prefix = null, int $lenght = 8) {
            $numbers = '0123456789';
            $numbersLen = strlen($numbers);

            for ($i = 0; $i < $lenght; $i++) {
                $this->randomNums .= $numbers[rand(0, $numbersLen - 1)];
            }

            return $this->randomNums;
        }

    }

?>