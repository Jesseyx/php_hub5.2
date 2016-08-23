<?php

namespace App\Http\Controllers;

use App\Models\Append;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Link;
use App\Models\Notification;
use App\Models\SiteStatus;
use App\Models\Topic;
use App\Phphub\Core\CreatorListener;
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

    public function index(Topic $topic)
    {
        $filter = $topic->present()->getTopicFilter();
        $topics = $topic->getTopicsWithFilter($filter, 40);
        $banners = Banner::allByPosition();
        $links = Link::allFromCache();

        return view('topics.index', compact('topics', 'banners', 'links'));
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
        $topic = Topic::findOrFail($id);
        $replies = $topic->getRepliesWithLimit(config('phphub.replies_perpage'));
        $category = $topic->category;
        $categoryTopics = $topic->getSameCategoryTopics();

        $topic->increment('view_count', 1);

        $banners = Banner::allByPosition();
        return view('topics.show', compact('topic', 'replies', 'category', 'categoryTopics', 'banners'));
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

        // 发送提醒
        Notification::notify('topic_mark_excellent', Auth::user(), $topic->user, $topic);

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
            $allowed_extensions = ['png', 'jpg', 'gif'];

            if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowed_extensions)) {
                return ['error' => 'You may only upload png, jpg or gif.'];
            }

            $fileName = $file->getClientOriginalName();
            $extension  = $file->getClientOriginalExtension() ?: 'png';
            $folderName = 'uploads/images/' . date('Ym', time()) . '/' . date('d', time()) . '/' . Auth::user()->id;
            $destinationPath = public_path() . '/' . $folderName;

            $safeName = str_random(10) . '.' . $extension;

            $file->move($destinationPath, $safeName);

            // If is not gif file, we will try to reduse the file size
            if ($file->getClientOriginalExtension() != 'gif') {
                // open an image file
                $img = Image::make($destinationPath . '/' .$safeName);
                // prevent possible upsizing
                $img->resize(1440, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                // finally we save the image as a new file
                $img->save();
            }

            $data['filename'] = getUserStaticDomain() . $folderName .'/'. $safeName;

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
        show_crx_hint();

        return redirect(route('topics.show', array($topic->id)));
    }
}
