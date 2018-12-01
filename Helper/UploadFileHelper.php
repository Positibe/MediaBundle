<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MediaBundle\Helper;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Positibe\Bundle\MediaBundle\Model\FileInterface;
use Positibe\Bundle\MediaBundle\Model\MediaManager;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class UploadFileHelper
 * @package Positibe\Bundle\MediaBundle\File
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class UploadFileHelper
{
    protected $manager;
    protected $mediaClass;
    protected $mediaManager;
    protected $editorHelpers;
    protected $allowNonUploadedFiles = false;

    /**
     * UploadFileHelper constructor.
     * @param EntityManagerInterface $manager
     * @param $mediaClass
     * @param MediaManager $mediaManager
     */
    public function __construct(
        EntityManagerInterface $manager,
        $mediaClass,
        MediaManager $mediaManager
    ) {
        $this->manager = $manager;
        $this->setClass($mediaClass);
        $this->mediaManager = $mediaManager;
    }

    /**
     * Set the class to use to get the file object;
     * if not called, the default class will be used.
     *
     * @param string $mediaClass fully qualified class name of file
     */
    public function setClass($mediaClass)
    {
        if (empty($mediaClass)) {
            $this->class = null;

            return;
        }

        $this->class = $mediaClass;
    }
    
    /**
     * @param $name
     * @param UploadCkeditorHelper $helper
     */
    public function addEditorHelper($name, $helper)
    {
        $this->editorHelpers[$name] = $helper;
    }

    /**
     * @param null $name
     * @return UploadCkeditorHelper
     */
    public function getEditorHelper($name = null)
    {
        if ($name && isset($this->editorHelpers[$name])) {
            return $this->editorHelpers[$name];
        }

        return isset($this->editorHelpers['default']) ? $this->editorHelpers['default'] : null;
    }

    /**
     * Validate the uploaded file.
     *
     * @param UploadedFile $file
     *
     * @return bool true either returns true or throws an exception
     *
     * @throws UploadException if the upload failed for some reason.
     */
    protected function validateFile(UploadedFile $file)
    {
        if ($file->isValid()) {
            return true;
        }

        throw new UploadException($this->getErrorMessage($file));
    }

    /**
     * Returns an informative upload error message.
     *
     * Copied from UploadedFile because its only public since 2.4
     *
     * @param UploadedFile $file The file with the error.
     *
     * @return string The error message regarding the specified error code
     */
    private function getErrorMessage(UploadedFile $file)
    {
        $errorCode = $file->getError();
        static $errors = array(
            UPLOAD_ERR_INI_SIZE => 'The file "%s" exceeds your upload_max_filesize ini directive (limit is %d kb).',
            UPLOAD_ERR_FORM_SIZE => 'The file "%s" exceeds the upload limit defined in your form.',
            UPLOAD_ERR_PARTIAL => 'The file "%s" was only partially uploaded.',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
            UPLOAD_ERR_CANT_WRITE => 'The file "%s" could not be written on disk.',
            UPLOAD_ERR_NO_TMP_DIR => 'File could not be uploaded: missing temporary directory.',
            UPLOAD_ERR_EXTENSION => 'File upload was stopped by a PHP extension.',
        );

        $maxFilesize = $errorCode === UPLOAD_ERR_INI_SIZE ? $file->getMaxFilesize() / 1024 : 0;
        $message = isset($errors[$errorCode]) ? $errors[$errorCode] : 'The file "%s" was not uploaded due to an unknown error.';

        return sprintf($message, $file->getClientOriginalName(), $maxFilesize);
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param null $mediaClass
     * @return FileInterface
     */
    public function handleUploadedFile(UploadedFile $uploadedFile, $mediaClass = null)
    {
        $this->validateFile($uploadedFile);

        $mediaClass = $mediaClass ?: $this->class;
        /** @var $file FileInterface */
        $file = new $mediaClass();
        if (!$file instanceof FileInterface) {
            throw new UploadException(sprintf('Invalid class %s specified', $mediaClass));
        }
        $file->setName($uploadedFile->getClientOriginalName());
        $file->copyContentFromFile($uploadedFile);

        return $file;
    }

    /**
     * @param Request $request
     * @param array $uploadedFiles
     * @return mixed
     */
    public function getUploadResponse(Request $request, array $uploadedFiles = array())
    {
        $editorHelper = $this->getEditorHelper($request->get('editor', 'default'));

        if (!$editorHelper) {
            throw new HttpException(
                409, sprintf(
                    'Editor type "%s" not found, cannot process upload.',
                    $request->get('editor', 'default')
                )
            );
        }

        if (count($uploadedFiles) === 0) {
            // by default get the first file
            $uploadedFiles = array($request->files->getIterator()->current());
        }

        // handle the uploaded file(s)
        $files = array();
        foreach ($uploadedFiles as $uploadedFile) {
            $file = $this->handleUploadedFile($uploadedFile);

            $editorHelper->setFileDefaults($request, $file);

            $this->manager->persist($file);

            $files[] = $file;
        }

        // write created FileInterface file(s) to storage
        $this->manager->flush();

        // response
        return $editorHelper->getUploadResponse($request, $files);
    }
}