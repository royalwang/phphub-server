<?php

namespace PHPHub\Http\ApiControllers;

use PHPHub\Repositories\NodeRepositoryInterface;
use PHPHub\Transformers\NodeTransformer;

class NodesController extends Controller
{
    /**
     * @var NodeRepositoryInterface
     */
    private $repository;

    /**
     * TopicController constructor.
     *
     * @param NodeRepositoryInterface $repository
     */
    public function __construct(NodeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->repository->all(['id', 'name', 'parent_node']);

        return $this->response()->collection($data, new NodeTransformer());
    }
}
