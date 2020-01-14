<?php

namespace Sitation\CustomRuleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Akeneo\Tool\Component\FileStorage\File\FileStorer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Finder\SplFileInfo;

class DefaultController extends Controller
{
	protected $filestorer;
    protected $productRepository;

	public function __construct(FileStorer $filestorer)
    {
        $this->filestorer =$filestorer;
        //$this->productRepository=$this->getContainer()->get('pim_catalog.repository.product');
    } 

    public function indexAction()
    {
        return $this->render('SitationCustomRuleBundle:Default:index.html.twig');
    }

    public function productUpdateAction(Request $request){
        /*$requestedData = json_decode($request->getContent(), true);
        $originalImageFilePath = $requestedData["values"]["image_1"][0]["data"]["filePath"];*/
        $repository = $this->get('pim_catalog.repository.product');
        $this->productRepository=$repository;
        $productId = $request->query->get('productId');
        $product = $this->productRepository->find($productId);
        $originalImageFilePath = (string) $product->getValue("image_1");
        $customisedImage = $this->imageResize($originalImageFilePath, 500, 400);
        $options = ['locale' => null, 'scope' => null];
        
        $validator = $this->get('pim_catalog.validator.product');
        $violations = $validator->validate($product);
        if(0 !== $violations->count()){
            return new JsonResponse(['status'=>"invalid product violation occured"]); 
        }

        $propertySetter = $this->get('pim_catalog.updater.property_setter');
        $propertySetter->setData(
            $product,
            'image_3',
            $customisedImage,
            $options
        );

        $saver = $this->get('pim_catalog.saver.product');
        $saver->save($product);    
        return new JsonResponse(['status'=>"success"]); 
    }


    public function imageResize($originalImage, $width=500, $height=500) {
        $fileDirPath = $this->getParameter('catalog_storage_dir');
        $imagePath = $fileDirPath."/".$originalImage;

        $imagick = new \Imagick($imagePath);
        $filePath = $imagick->getImageFilename();
        $customisedFileName = 'custom-'.end(explode(('/'), $filePath));

        $dd = explode("/", $originalImage);

        array_pop($dd);
        $originalImageHalfPath = implode('/', $dd);
        
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
        
        $localFile = $fileDirPath."/".$originalImageHalfPath."/".$customisedFileName;
        $imagick->writeImages($fileDirPath."/".$originalImageHalfPath."/".$customisedFileName, false);
        
        $uploadedFile = new SplFileInfo($localFile, $localFile, $customisedFileName);
        $fileInfo = $this->filestorer->store($uploadedFile, 'catalogStorage', true);
        $customisedImage = $fileInfo->getKey();
        return $customisedImage;
    }
}
