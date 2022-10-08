<?php
// Fichier : ProductImageManager.php
// Date : 2021-05-16
// Auteur : Davis Eath
// But : Gérer les téléversements d'images pour les produits

namespace App\Util;

use LogicException;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mime\Exception\LogicException as ExceptionLogicException;
use Symfony\Component\Validator\Constraints as Assert;

class ProductImageManager
{
    private $productId;
    private $imgDir;

    /**
     * @Assert\File(maxSize="100k", maxSizeMessage="La taille du fichier est trop grande.",
     *              mimeTypes={"image/jpeg", "image/png"}, mimeTypesMessage="Le fichier n'est pas d'un format supporté.")
     */
    private $coverImage;
    private $removeCoverImage;

    /**
     * @Assert\File(maxSize="100k", maxSizeMessage="La taille du fichier est trop grande.",
     *              mimeTypes={"image/jpeg", "image/png"}, mimeTypesMessage="Le fichier n'est pas d'un format supporté.")
     */
    private $detailImage;
    private $removeDetailImage;

    public function __construct(int $productId, string $projectDir)
    {
        $this->productId = $productId;
        $this->imgDir = realpath($projectDir . '/public/images/products/');
    }

    public function getCoverImage(): ?UploadedFile
    {
        return $this->coverImage;
    }

    public function setCoverImage(UploadedFile $coverImage): self
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getRemoveCoverImage(): ?bool
    {
        return $this->removeCoverImage;
    }

    public function setRemoveCoverImage(bool $removeCoverImage): self
    {
        $this->removeCoverImage = $removeCoverImage;

        return $this;
    }

    public function getDetailImage(): ?UploadedFile
    {
        return $this->detailImage;
    }

    public function setDetailImage(UploadedFile $detailImage = null): self
    {
        $this->detailImage = $detailImage;

        return $this;
    }

    public function getRemoveDetailImage(): ?bool
    {
        return $this->removeDetailImage;
    }

    public function setRemoveDetailImage(bool $removeDetailImage): self
    {
        $this->removeDetailImage = $removeDetailImage;

        return $this;
    }

    /**
     * Gère les modifications d'images lorsque l'utilisateur soumet le formulaire
     */
    public function updateImages()
    {
        // priorité suppression d'image > téléversement d'image

        if ($this->getRemoveCoverImage())
        {
            $this->deleteImage(1);
        }
        else if (!is_null($this->getCoverImage()))
        {
            $this->moveTempFile($this->getCoverImage(), 1);
        }

        if ($this->getRemoveDetailImage())
        {
            $this->deleteImage(2);
        }
        else if (!is_null($this->getDetailImage()))
        {
            $this->moveTempFile($this->getDetailImage(), 2);
        }
    }

    /**
     * Déplace un fichier téléversé du dossier temporaire vers le dossier d'images de produit
     */
    private function moveTempFile(UploadedFile $upload, $type)
    {
        $filename = $this->productId . '_' . $type . '.jpg';

        switch ($upload->getMimeType())
        {
            case 'image/jpeg':
                $upload->move($this->imgDir, $filename);
                break;

            case 'image/png':
                $this->convertPngToJpeg($upload->getRealPath(), $filename);
                break;
        }
    }

    /**
     * Supprime une image de produit
     */
    private function deleteImage($type)
    {
        $filename = $this->productId . '_' . $type . '.jpg';
        $path = $this->imgDir . '/' . $filename;

        if (file_exists($path))
        {
            unlink($path); // suppression du fichier
        }
    }

    /**
     * Covertit une image PNG en JPEG, puis crée une copie vers le dossier d'images de produit
     */
    private function convertPngToJpeg($path, $filename)
    {
        $png = imagecreatefrompng($path);
        $image = imagecreatetruecolor(imagesx($png), imagesy($png));         // création d'un JPEG avec les mêmes dimensions que le PNG
        
        imagefill($image, 0, 0, imagecolorallocate($image, 255, 255, 255));  // remplissage de l'image JPEG avec un arrière-plan blanc
        imagecopy($image, $png, 0, 0, 0, 0, imagesx($png), imagesy($png));   // copie de l'image PNG à l'image JPEG
        imagedestroy($png);                                                  // libération de l'image PNG de la mémoire

        imagejpeg($image, $this->imgDir . '/' . $filename);                  // sauvegarde de l'image JPEG au dossier d'images
        imagedestroy($image);                                                // libération de l'image JPEG de la mémoire
    }
}