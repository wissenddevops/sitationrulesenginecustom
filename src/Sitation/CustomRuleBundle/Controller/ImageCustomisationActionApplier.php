<?php

namespace Sitation\CustomRuleBundle\Controller;

use Akeneo\Tool\Bundle\RuleEngineBundle\Model\ActionInterface;
use Sitation\CustomRuleBundle\Model\ProductImageCustomisationAction;
use Akeneo\Tool\Component\RuleEngine\ActionApplier\ActionApplierInterface;
use Akeneo\Tool\Component\StorageUtils\Updater\PropertySetterInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\JsonResponse;
use Psr\Log\LoggerInterface;
use Akeneo\Tool\Component\FileStorage\File\FileStorer;
use Akeneo\Pim\Enrichment\Component\FileStorage;
use Symfony\Component\Finder\SplFileInfo;

use Akeneo\Pim\Enrichment\Component\Product\Repository\ProductRepositoryInterface;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Akeneo\Pim\Enrichment\Bundle\Controller\InternalApi\ProductController; 
use Akeneo\Tool\Component\StorageUtils\Updater\ObjectUpdaterInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Akeneo\Pim\Enrichment\Component\Product\Model\ProductInterface;

class ImageCustomisationActionApplier implements ActionApplierInterface
{
    /** @var PropertySetterInterface */
    protected $propertySetter;
    protected $logger;
    protected $fileStorer;
    protected $catalogStorage;
    

    /**
     * @param PropertySetterInterface $propertySetter
     */
    public function __construct(PropertySetterInterface $propertySetter, LoggerInterface $logger, FileStorer $fileStorer, $catalogStorage)
    {
        $this->propertySetter = $propertySetter;
        $this->logger = $logger;
        $this->fileStorer = $fileStorer;
        $this->catalogStorage = $catalogStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function applyAction(ActionInterface $action, array $products = [])
    {

        $attributes = $action->getAttributes();
        $width = $action->getWidth();
        $height = $action->getHeight();
        $attributes=$attributes[0];
        $this->logger->info('Height : '.$height.'width : '.$width);
        foreach ($products as $product) {
            
            $this->logger->info(print_r(json_decode($product, true)));
            $value = $product->getValue($attributes);
            if($value!==null) {
                $image = (string) $value;
                $fileDirPath = $this->catalogStorage;
                $imagePath = $fileDirPath."/".$image;
                $imagick = new \Imagick($imagePath);
                $filePath = $imagick->getImageFilename();
                $customisedFileName = 'custom-'.end(explode(('/'), $filePath));

                $dd = explode("/", $image);

                array_pop($dd);
                $halfPath = implode('/', $dd);
                
                $imagick->resizeImage($width, $height,  \imagick::FILTER_LANCZOS, 1, TRUE);
                $cropWidth = $imagick->getImageWidth();
                $cropHeight = $imagick->getImageHeight();

                if($cropZoom) {
                    $newWidth = $cropWidth / 2;
                    $newHeight = $cropHeight / 2;

                    $imagick->cropimage(
                        $newWidth,
                        $newHeight,
                        ($cropWidth - $newWidth) / 2,
                        ($cropHeight - $newHeight) / 2
                    );

                    $imagick->scaleimage(
                        $imagick->getImageWidth() * 4,
                        $imagick->getImageHeight() * 4
                    );
                }
                
                $localFile = $fileDirPath."/".$halfPath."/".$customisedFileName;
                $imagick->writeImages($localFile, false);
                
                $uploadedFile = new SplFileInfo($localFile, $localFile, $customisedFileName);
                $fileInfo = $this->fileStorer->store($uploadedFile, FileStorage::CATALOG_STORAGE_ALIAS, true);
                
                $fnam = $fileInfo->getKey();

                $ii = $this->propertySetter->setData(
                    $product,
                    $action->getField(),
                    $fnam,
                    $action->getOptions()
                );
            }    
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ActionInterface $action)
    {
        return $action instanceof ProductImageCustomisationAction;
    }
}