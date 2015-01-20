<?php
namespace Phrases\Controller;

use Cocur\Slugify\Slugify;
use Phrases\Persistance\RepositoryInterface;
use Zend\Http\Response;
use Zend\Http\Request;

class PostPhrase implements ExecutionInterface
{
    /**
     * @var RepositoryInterface
     */
    protected $repository;
    protected $data;

    public function __construct(RepositoryInterface $repository, array $postData)
    {
        $this->repository = $repository;
        $this->data       = $postData;
    }

    protected function isValidPostData()
    {
        $mandatoryKeys = [
            'title',
            'text'
        ];

        return !array_diff($mandatoryKeys, array_keys($this->data));
    }

    public function execute(Request $request)
    {
        $response = new Response();

        if ($this->isValidPostData()) {
            $this->repository->save($this->data);
            $response->setContent("/" . (new Slugify())->slugify($this->data['title']));
            return $response->setStatusCode(Response::STATUS_CODE_201);
        }

        return $response->setStatusCode(Response::STATUS_CODE_400);
    }
}
