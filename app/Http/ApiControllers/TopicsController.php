<?php

namespace App\Http\ApiControllers;

use App\Models\Topic;
use App\Models\User;
use App\Models\Vote;
use App\Phphub\Core\CreatorListener;
use App\Transformers\TopicTransformer;
use Auth;
use Authorizer;
use Dingo\Api\Exception\StoreResourceFailedException;
use Gate;
use Illuminate\Http\Request;
use Response;

class TopicsController extends Controller implements CreatorListener
{
    public function index(Request $request, Topic $topic)
    {
        $filter = $topic->correctApiFilter($request->get('filters'));
        $topics = $topic->getTopicsWithFilter($filter, api_per_page());
        return $this->response()->paginator($topics, new TopicTransformer());
    }

    public function show($id)
    {
        $topic = Topic::with('user')->find($id);

        $topic_id = $topic->id;
        $user_id = Auth::id();

        if (Auth::check()) {
            $upvoted = Vote::where([
                    'user_id'      => $user_id,
                    'votable_id'   => $topic_id,
                    'votable_type' => 'App\Models\Topic',
                    'is'           => 'upvote',
                ])->exists();

            $downvoted = Vote::where([
                    'user_id'      => $user_id,
                    'votable_id'   => $topic_id,
                    'votable_type' => 'App\Models\Topic',
                    'is'           => 'downvote',
                ])->exists();

            $topic->vote_up = $upvoted;
            $topic->vote_down = $downvoted;
        }

        $topic->increment('view_count', 1);

        return $this->response()->item($topic, new TopicTransformer());
    }

    public function store(Request $request)
    {
        if (!Auth::user()->verified) {
            throw new StoreResourceFailedException('创建话题失败，请验证用户邮箱');
        }

        $data = array_merge($request->except('_token'), ['category_id' => $request->category_id]);

        return app('Phphub\Creators\TopicCreator')->create($this, $data);
    }

    public function destroy($id)
    {
        $topic = Topic::findOrFail($id);
        if (Gate::denies('delete', $topic)) {
            throw new AccessDeniedHttpException();
        }

        $topic->delete();
        return ['status' => 'ok'];
    }

    public function voteUp($id)
    {
        $topic = Topic::find($id);
        app('App\Phphub\Vote\Voter')->topicUpVote($topic);

        return response([
            'vote-up'    => true,
            'vote_count' => $topic->vote_count,
        ]);
    }

    public function voteDown($id)
    {
        $topic = Topic::find($id);
        app('App\Phphub\Vote\Voter')->topicDownVote($topic);

        return response([
            'vote-down'  => true,
            'vote_count' => $topic->vote_count,
        ]);
    }

    public function indexByUserVotes($user_id)
    {
        $user = User::findOrFail($user_id);
        $topics = $user->votedTopics()->orderBy('pivot_created_at', 'desc')->paginate(15);

        return $this->response()->paginator($topics, new TopicTransformer());
    }

    public function indexByUserId($user_id)
    {
        $topics = Topic::whose($user_id)->recent()->paginate(15);

        return $this->response()->paginator($topics, new TopicTransformer());

    }

    public function showWebView($id)
    {
        $topic = Topic::find($id);
        return view('api.topics.show', compact('topic'));
    }

    /**
     * ----------------------------------------
     * CreatorListener Delegate
     * ----------------------------------------
     */
    public function creatorFailed($errors)
    {
        throw new StoreResourceFailedException('创建话题失败：' . output_msb($errors->getMessageBag()) );
    }

    public function creatorSucceed($topic)
    {
        return $this->response()->item($topic, new TopicTransformer());
    }
}
