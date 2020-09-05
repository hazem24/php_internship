<?php
namespace FlexyProject\GitHub\Receiver\GitData;

use Symfony\Component\HttpFoundation\Request;

/**
 * The Blobs API class provides access to GitData's blobs.
 *
 * @link    https://developer.github.com/v3/git/blobs/
 * @package FlexyProject\GitHub\Receiver\GitData
 */
class Blobs extends AbstractGitData
{

    /**
     * Get a Blob
     *
     * @link https://developer.github.com/v3/git/blobs/#get-a-blob
     *
     * @param string $sha
     *
     * @return array
     */
    public function getBlob(string $sha): array
    {
        return $this->getApi()->request($this->getApi()->sprintf('/repos/:owner/:repo/git/blobs/:sha',
            $this->getGitData()->getOwner(), $this->getGitData()->getRepo(), $sha));
    }

    /**
     * Create a Blob
     *
     * @link https://developer.github.com/v3/git/blobs/#create-a-blob
     *
     * @param string $content
     * @param string $encoding
     *
     * @return array
     */
    public function createBlob(string $content, string $encoding = 'utf-8'): array
    {
        return $this->getApi()->request($this->getApi()
                                             ->sprintf('/repos/:owner/:repo/git/blobs', $this->getGitData()->getOwner(),
                                                 $this->getGitData()->getRepo()), Request::METHOD_POST, [
                'content'  => $content,
                'encoding' => $encoding
            ]);
    }
}