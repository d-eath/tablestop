<?php
// Fichier : AppExtension.php
// Date : 2021-02-04
// Auteur : Davis Eath
// But : Ajouter des fonctions et des filtres sur mesure pour Twig

namespace App\Twig;

use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('format_gender', [$this, 'formatGender']),
            new TwigFilter('format_province', [$this, 'formatProvince']),
            new TwigFilter('format_price', [$this, 'formatPrice']),
            new TwigFilter('format_percent', [$this, 'formatPercent'])
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('asset_exists', [$this, 'assetExists'])
        ];
    }

    /**
     * Filtre Twig qui convertit un acronyme à son genre
     */
    public function formatGender($gender)
    {
        $genders = [
            'X' => 'Neutre',
            'F' => 'Féminin',
            'M' => 'Masculin'
        ];

        return $genders[$gender];
    }

    /**
     * Filtre Twig qui convertit un code de province au nom de la province
     */
    public function formatProvince($province)
    {
        $provinces = [
            'AB' => 'Alberta',
            'BC' => 'Colombie-Britanique',
            'MB' => 'Manitoba',
            'NB' => 'Nouveau-Brunswick',
            'NL' => 'Terre-Neuve-et-Labrador',
            'NS' => 'Nouvelle-Écosse',
            'NT' => 'Territoires du Nord-Ouest',
            'NU' => 'Nunavut',
            'ON' => 'Ontario',
            'MB' => 'Île-du-Prince-Édouard',
            'QC' => 'Québec',
            'SK' => 'Saskatchewan',
            'YT' => 'Yukon'
        ];

        return $provinces[$province];
    }

    /**
     * Filtre Twig qui formatte un nombre décimal en format monétaire (12345.67 -> 12 345,67 CDN$)
     */
    public function formatPrice($number)
    {
        return number_format($number, 2, ',', ' ') . ' CDN$';
    }

    /**
     * Filtre Twig qui formatte un nombre décimal en pourcentage (0.12345 -> 12,345 %)
     */
    public function formatPercent($number)
    {
        return str_replace('.', ',', ($number * 100)) . ' %';
    }

    /**
     * Fonction Twig qui retourne si un asset est présent dans le dossier public
     */
    public function assetExists($path)
    {
        $projectDir = realpath($this->kernel->getProjectDir() . 'public/');
        $asset = realpath($projectDir . $path);

        return file_exists($asset);
    }
}
