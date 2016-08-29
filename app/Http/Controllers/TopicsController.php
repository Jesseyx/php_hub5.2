<?php

namespace App\Http\Controllers;

use App\Models\ActiveUser;
use App\Models\Append;
use App\Models\Banner;
use App\Models\Category;
use App\Models\HotTopic;
use App\Models\Link;
use App\Models\Notification;
use App\Models\SiteStatus;
use App\Models\Topic;
use App\Phphub\Core\CreatorListener;
use App\Phphub\Handler\Exception\ImageUploadException;
use App\Phphub\Markdown\Markdown;
use App\Phphub\Notification\Notifier;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\StoreTopicRequest;

use Auth;
use Image;

class TopicsController extends Controller implements CreatorListener
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index(Request $request, Topic $topic)
    {
        $topics = $topic->getTopicsWithFilter($request->get('filter'), 40);
        $banners = Banner::allByPosition();
        $links = Link::allFromCache();

        $active_users = ActiveUser::fetchAll();
        $hot_topics = HotTopic::fetchAll();

        return view('topics.index', compact('topics', 'banners', 'links', 'active_users', 'hot_topics'));
    }

    public function create(Request $request)
    {
        $category = Category::find($request->input('category_id'));
        $categories = Category::all();

        return view('topics.create_edit', compact('category', 'categories'));
    }

    public function store(StoreTopicRequest $request)
    {
        return app('App\Phphub\Creators\TopicCreator')->create($this, $request->except('_token'));
    }

    public function show($id)
    {
        $topic = Topic::where('id', $id)->with('user', 'lastReplyUser')->first();

        if ($topic->user->is_banned == 'yes') {
            flash('你访问的文章已被屏蔽，有疑问请加微信：summer_charlie', 'error');
            return redirect(route('topics.index'));
        }

        $randomExcellentTopics = $topic->getRandomExcellent();
        $replies = $topic->getRepliesWithLimit(config('phphub.replies_perpage'));
        $categoryTopics = $topic->getSameCategoryTopics();
        $userTopics = $topic->byWhom($topic->user_id)->with('user')->recent()->limit(8)->get();
        $votedUsers = $topic->votes()->orderBy('id', 'desc')->with('user')->get()->pluck('user');

        $topic->increment('view_count', 1);

        $banners = Banner::allByPosition();
        return view('topics.show', compact('topic', 'randomExcellentTopics', 'replies', 'categoryTopics', 'userTopics', 'votedUsers', 'banners'));
    }

    public function edit($id)
    {
        $topic = Topic::findOrFail($id);
        $this->authorize('update', $topic);

        $categories = Category::all();
        $category = $topic->category;

        if ($topic->body_original) {
            $topic->body = $topic->body_original;
        }

        return view('topics.create_edit', compact('topic', 'categories', 'category'));
    }

    public function update(StoreTopicRequest $request, $id)
    {
        $topic = Topic::findOrFail($id);
        $this->authorize('update', $topic);

        $data = $request->only('title', 'body', 'category_id');

        $markdown = new Markdown;
        $data['body_original'] = $data['body'];
        $data['body'] = $markdown->convertMarkdownToHtml($data['body']);
        $data['excerpt'] = Topic::makeExcerpt($data['body']);

        $topic->update($data);

        // 发送通知
        flash(lang('Operation succeeded.'), 'success');

        return redirect(route('topics.show', $topic->id));
    }

    public function destroy($id)
    {
        $topic = Topic::findOrFail($id);
        $this->authorize('delete', $topic);

        $topic->delete();

        // 发送通知
        flash(lang('Operation succeeded.'), 'success');

        return redirect(route('topics.index'));
    }

    public function append($id, Request $request)
    {
        $topic = Topic::findOrFail($id);
        $this->authorize('append', $topic);

        $markdown = new Markdown;
        $content = $markdown->convertMarkdownToHtml($request->input('content'));

        $append = Append::create(['topic_id' => $topic->id, 'content' => $content]);

        // 生成通知
        app(Notifier::class)->newAppendNotify(Auth::user(), $topic, $append);

        return response([
            'status' => 200,
            'message' => lang('Operation succeeded.'),
            'append' => $append,
        ]);
    }

    /**
     * ----------------------------------------
     * User Topic Vote function
     * ----------------------------------------
     */
    public function upvote($id)
    {
        $topic = Topic::find($id);
        app('App\Phphub\Vote\Voter')->topicUpVote($topic);

        return response(['status' => 200]);
    }

    public function downvote($id)
    {
        $topic = Topic::find($id);
        app('App\Phphub\Vote\Voter')->topicDownVote($topic);

        return response(['status' => 200]);
    }
    // 加精
    public function recommend($id)
    {
        $topic = Topic::findOrFail($id);
        $this->authorize('recommend', $topic);

        $topic->is_excellent = $topic->is_excellent == 'yes' ? 'no' : 'yes';
        $topic->save();

        // 发送提醒
        Notification::notify('topic_mark_excellent', Auth::user(), $topic->user, $topic);

        return response(['status' => 200, 'message' => lang('Operation succeeded.')]);
    }
    // 置顶
    public function pin($id)
    {
        $topic = Topic::findOrFail($id);
        $this->authorize('pin', $topic);
        
        $topic->order = $topic->order > 0 ? 0 : 999;
        $topic->save();

        return response(['status' => 200, 'message' => lang('Operation succeeded.')]);
    }
    // 沉掉主题，就是不再显示了，和置顶不置顶还有区别
    public function sink($id)
    {
        $topic = Topic::findOrFail($id);
        $this->authorize('sink', $topic);

        $topic->order = $topic->order >= 0 ? -1 : 0;
        $topic->save();

        return response(['status' => 200, 'message' => lang('Operation succeeded.')]);
    }

    // 上传图片
    public function uploadImage(Request $request)
    {
        if ($file = $request->file('file')) {
            try {
                $upload_status = app('App\Phphub\Handler\ImageUploadHandler')->uploadImage($file);
            } catch (ImageUploadException $exception) {
                return ['error' => $exception->getMessage()];
            }

            $data['filename'] = $upload_status['filename'];

            SiteStatus::newImage();
        } else {
            $data['error'] = 'Error while uploading file';
        }

        return $data;
    }

    /**
     * ----------------------------------------
     * CreatorListener Delegate
     * ----------------------------------------
     */
    public function creatorFailed($errors)
    {
        return redirect('/');
    }

    public function creatorSucceed($topic)
    {
        // 发送通知
        flash(lang('Operation succeeded.'), 'success');

        return redirect(route('topics.show', array($topic->id)));
    }
}
