<?php
// Fichier : Tax.php
// Date : 2021-03-07
// Auteur : Davis Eath
// But : Représenter une taxe

namespace App\Util;

class Tax
{
    private $name;
    private $rate;

    public function __construct(string $name, string $rate)
    {
        $this->name = $name;
        $this->rate = $rate;
    }

    /**
     * Retourne le nom de la taxe
     * @return string Le nom de la taxe
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Retourne le taux de la taxe
     * @return string Le taux de la taxe
     */
    public function getRate(): string
    {
        return $this->rate;
    }

    /**
     * Applique la taxe sur un sous-total (total avant taxes)
     * @param string $price Le total avant les taxes
     * @return string La taxe appliquée
     */
    public function applyTax(string $price): string
    {
        return $price * $this->rate;
    }
}
