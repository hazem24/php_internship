<?php
namespace FlexyProject\GitHub\Receiver;

use FlexyProject\GitHub\AbstractApi;

/**
 * This class give you access to the Search API.
 *
 * @link    https://developer.github.com/v3/search/
 * @package FlexyProject\GitHub\Receiver
 */
class Search extends AbstractReceiver
{

    /** Available sub-Receiver */
    const REPOSITORIES  = 'Repositories';
    const CODE          = 'Code';
    const ISSUES        = 'Issues';
    const USERS         = 'Users';
    const LEGACY_SEARCH = 'LegacySearch';

    /**
     * Search repositories
     *
     * @link https://developer.github.com/v3/search/#search-repositories
     *
     * @param string $q
     * @param string $sort
     * @param string $order
     *
     * @return array
     * @throws \Exception
     */
    public function searchRepositories(string $q, string $sort = null,
                                       string $order = AbstractApi::DIRECTION_DESC): array
    {
        return $this->getApi()->request($this->getApi()->sprintf('/search/repositories?:args',
            http_build_query(['q' => $q, 'sort' => $sort, 'order' => $order])));
    }

    /**
     * Search code
     *
     * @link https://developer.github.com/v3/search/#search-code
     *
     * @param string $q
     * @param string $sort
     * @param string $order
     *
     * @return array
     * @throws \Exception
     */
    public function searchCode(string $q, string $sort = null, string $order = AbstractApi::DIRECTION_DESC): array
    {
        return $this->getApi()->request($this->getApi()->sprintf('/search/code?:args',
            http_build_query(['q' => $q, 'sort' => $sort, 'order' => $order])));
    }

    /**
     * Search issues
     *
     * @link https://developer.github.com/v3/search/#search-issues
     *
     * @param string $q
     * @param string $sort
     * @param string $order
     *
     * @return array
     * @throws \Exception
     */
    public function searchIssues(string $q, string $sort = null, string $order = AbstractApi::DIRECTION_DESC): array
    {
        return $this->getApi()->request($this->getApi()->sprintf('/search/issues?:args',
            http_build_query(['q' => $q, 'sort' => $sort, 'order' => $order])));
    }

    /**
     * Search users
     *
     * @link https://developer.github.com/v3/search/#search-users
     *
     * @param string $q
     * @param string $sort
     * @param string $order
     *
     * @return array
     * @throws \Exception
     */
    public function searchUsers(string $q, string $sort = null, string $order = AbstractApi::DIRECTION_DESC): array
    {
        return $this->getApi()->request($this->getApi()->sprintf('/search/users?:args',
            http_build_query(['q' => $q, 'sort' => $sort, 'order' => $order])));
    }
}