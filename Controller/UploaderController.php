<?php

namespace Gwinn\TinymceFastloadBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UploaderController extends Controller
{
    public function uploadAction(Request $request)
    {
        $logger = $this->get('logger');

        $fileOrig = $request->files->get('tiny_inner_image');
        $fileName = time().'-'.$fileOrig->getClientOriginalName();
        $filePath  = realpath($this->container->getParameter('gwinn_tinymce_fastload.config.upload_path'));

        $logger->debug('TinyMCE file storage directory: '.$filePath);

        if (!file_exists($filePath) && !is_dir($filePath)) {
            $logger->info('TinyMCE creating directory '.$filePath);
            mkdir($filePath);
        }

        $fileOrig->move($filePath, $fileName);

        $urlPath = $this->getParameter('gwinn_tinymce_fastload.config.url_path').$fileName;

        $logger->debug('TinyMCE generated image response '.$urlPath);

        $response = new Response('<img src="'.$urlPath.'"/>');
        $response->headers->set('Content-Type', 'text/html');

        return $response;
    }

}
