<?php
namespace FlexyProject\GitHub\Receiver\PullRequests;

use FlexyProject\GitHub\AbstractApi;
use FlexyProject\GitHub\Receiver\Api;
use FlexyProject\GitHub\Receiver\PullRequests;

/**
 * Class AbstractPullRequests
 *
 * @package FlexyProject\GitHub\Receiver\PullRequests
 */
abstract class AbstractPullRequests
{
    /** Api trait */
    use Api;

    /** Properties */
    protected $pullRequests;

    /**
     * Constructor
     *
     * @param PullRequests $pullRequests
     */
    public function __construct(PullRequests $pullRequests)
    {
        $this->setPullRequests($pullRequests);
        $this->setApi($pullRequests->getApi());
    }

    /**
     * Get pullRequests
     *
     * @return PullRequests
     */
    public function getPullRequests(): PullRequests
    {
        return $this->pullRequests;
    }

    /**
     * Set pullRequests
     *
     * @param PullRequests $pullRequests
     *
     * @return AbstractPullRequests
     */
    public function setPullRequests(PullRequests $pullRequests): AbstractPullRequests
    {
        $this->pullRequests = $pullRequests;

        return $this;
    }
}