<?php

namespace PHPHub\Http\ApiControllers;

use Dingo\Api\Exception\StoreResourceFailedException;
use PHPHub\Repositories\ReplyRepositoryInterface;
use PHPHub\Transformers\ReplyTransformer;
use PHPHub\User;
use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;

class RepliesController extends Controller
{
    /**
     * @var ReplyRepositoryInterface
     */
    private $repository;

    /**
     * TopicController constructor.
     *
     * @param ReplyRepositoryInterface $repository
     */
    public function __construct(ReplyRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the replies by topic id.
     *
     * @param $topic_id
     *
     * @return \Illuminate\Http\Response
     */
    public function indexByTopicId($topic_id)
    {
        $this->repository->addAvailableInclude('user', ['name', 'avatar']);

        $data = $this->repository
            ->byTopicId($topic_id)
            ->skipPresenter()
            ->autoWith()
            ->autoWithRootColumns(['id', 'vote_count', 'created_at'])
            ->paginate(per_page());

        return $this->response()->paginator($data, new ReplyTransformer());
    }

    public function indexByUserId($user_id)
    {
        $this->repository->addAvailableInclude('user', ['name', 'avatar']);

        $data = $this->repository
            ->byUserId($user_id)
            ->skipPresenter()
            ->autoWith()
            ->autoWithRootColumns(['id', 'vote_count'])
            ->paginate(per_page());

        return $this->response()->paginator($data, new ReplyTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $reply = $this->repository->create($request->all());

            return $this->response()->item($reply, new ReplyTransformer());
        } catch (ValidatorException $e) {
            throw new StoreResourceFailedException('Could not create new topic.', $e->getMessageBag()->all());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
