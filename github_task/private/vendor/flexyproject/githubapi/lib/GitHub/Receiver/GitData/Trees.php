<?php
namespace FlexyProject\GitHub\Receiver\GitData;

use Symfony\Component\HttpFoundation\Request;

/**
 * The Trees API class provides access to GitData's trees
 *
 * @link    https://developer.github.com/v3/git/trees/
 * @package FlexyProject\GitHub\Receiver\GitData
 */
class Trees extends AbstractGitData
{

    /**
     * Get a Tree
     *
     * @link https://developer.github.com/v3/git/trees/#get-a-tree
     *
     * @param string $sha
     *
     * @return array
     * @throws \Exception
     */
    public function get(string $sha): array
    {
        return $this->getApi()->request($this->getApi()->sprintf('/repos/:owner/:repo/git/trees/:sha',
            $this->getGitData()->getOwner(), $this->getGitData()->getRepo(), $sha));
    }

    /**
     * Get a Tree Recursively
     *
     * @link https://developer.github.com/v3/git/trees/#get-a-tree-recursively
     *
     * @param string $sha
     *
     * @return array
     * @throws \Exception
     */
    public function getRecursively(string $sha): array
    {
        return $this->getApi()->request($this->getApi()->sprintf('/repos/:owner/:repo/git/trees/:sha?recursive=1',
            $this->getGitData()->getOwner(), $this->getGitData()->getRepo(), $sha));
    }

    /**
     * Create a Tree
     *
     * @link https://developer.github.com/v3/git/trees/#create-a-tree
     *
     * @param array  $tree
     * @param string $base_tree
     *
     * @return array
     * @throws \Exception
     */
    public function create(array $tree, string $base_tree): array
    {
        return $this->getApi()->request($this->getApi()
                                             ->sprintf('/repos/:owner/:repo/git/trees', $this->getGitData()->getOwner(),
                                                 $this->getGitData()->getRepo()), Request::METHOD_POST, [
                'tree'      => $tree,
                'base_tree' => $base_tree
            ]);
    }
} 