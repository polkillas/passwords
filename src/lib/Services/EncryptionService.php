<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 26.08.17
 * Time: 21:24
 */

namespace OCA\Passwords\Services;

use OCA\Passwords\Db\Folder;
use OCA\Passwords\Db\Revision;
use OCA\Passwords\Db\Tag;
use OCA\Passwords\Encryption\EncryptionInterface;
use OCA\Passwords\Encryption\SseV1Encryption;

/**
 * Class EncryptionService
 *
 * @package OCA\Passwords\Services
 */
class EncryptionService {

    const DEFAULT_CSE_ENCRYPTION = 'none';
    const DEFAULT_SSE_ENCRYPTION = 'SSEv1r1';
    const CSE_ENCRYPTION_NONE    = 'none';
    const SSE_ENCRYPTION_V1      = 'SSEv1r1';

    protected $encryptionMapping
        = [
            self::SSE_ENCRYPTION_V1 => SseV1Encryption::class
        ];

    /**
     * @param string $type
     *
     * @return EncryptionInterface
     * @throws \Exception
     */
    public function getEncryptionByType(string $type): EncryptionInterface {

        if(!isset($this->encryptionMapping[ $type ])) {
            throw new \Exception("Encryption type {$type} does not exsist");
        }

        return new $this->encryptionMapping[$type];
    }

    /**
     * @param Revision $revision
     *
     * @return Revision
     */
    public function encryptRevision(Revision $revision): Revision {
        $encryption = $this->getEncryptionByType($revision->getSseType());

        return $encryption->encryptRevision($revision);
    }

    /**
     * @param Revision $revision
     *
     * @return Revision
     */
    public function decryptRevision(Revision $revision): Revision {
        $encryption = $this->getEncryptionByType($revision->getSseType());

        return $encryption->decryptRevision($revision);
    }

    /**
     * @param Folder $folder
     *
     * @return Folder
     */
    public function encryptFolder(Folder $folder): Folder {
        $encryption = $this->getEncryptionByType($folder->getSseType());

        return $encryption->encryptFolder($folder);
    }

    /**
     * @param Folder $folder
     *
     * @return Folder
     */
    public function decryptFolder(Folder $folder): Folder {
        $encryption = $this->getEncryptionByType($folder->getSseType());

        return $encryption->decryptFolder($folder);
    }

    /**
     * @param Tag $tag
     *
     * @return Tag
     */
    public function encryptTag(Tag $tag): Tag {
        $encryption = $this->getEncryptionByType($tag->getSseType());

        return $encryption->encryptTag($tag);
    }

    /**
     * @param Tag $tag
     *
     * @return Tag
     */
    public function decryptTag(Tag $tag): Tag {
        $encryption = $this->getEncryptionByType($tag->getSseType());

        return $encryption->decryptTag($tag);
    }
}