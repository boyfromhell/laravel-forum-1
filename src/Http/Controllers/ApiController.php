<?php namespace Taskforcedev\LaravelForum\Http\Controllers;

use \Auth;
use \Event;
use \Redirect;
use \Request;
use \Response;
use \Schema;
use Taskforcedev\LaravelForum\Forum;
use Taskforcedev\LaravelForum\ForumCategory;
use Taskforcedev\LaravelForum\ForumPost;
use Taskforcedev\LaravelForum\ForumReply;
use Taskforcedev\LaravelForum\Events\PostCreated;
use Taskforcedev\LaravelForum\Events\PostReply;

/**
 * Class ApiController
 * @package Taskforcedev\LaravelForum\Http\Controllers
 */
class ApiController extends BaseController
{
    public function forumCategoryStore()
    {
        $data = [
            "name" => Request::input('name'),
        ];

        $response = $this->adminCheck();
        if (isset($response)) {
            return $response;
        }

        /* If data invalid return bad request */
        if (!ForumCategory::valid($data)) {
            return Response::make('Bad Request', 400);
        }

        ForumCategory::create($data);
    }

    public function forumStore()
    {
        $data = [
            "name" => Request::input('name'),
            "description" => Request::input('description'),
            "category_id" => Request::input('category'),
        ];

        $response = $this->adminCheck();
        if (isset($response)) {
            return $response;
        }

        if (!Forum::valid($data)) {
            return Response::make('Bad Request', 400);
        }

        Forum::create($data);
    }

    public function forumPostStore()
    {
        if (!Auth::check()) {
            return Response::make('Unauthorized', 401);
        }

        $user = Auth::user();

        $forum_id = Request::input('forum_id');

        $data = [
            "author_id" => $user->id,
            "title" => Request::input('title'),
            "body" => $this->sanitizeData(Request::input('body')),
            "forum_id" => $forum_id
        ];

        if (!ForumPost::valid($data)) {
            return Response::make('Bad Request', 400);
        }

        $post = ForumPost::create($data);

        event(new PostCreated($post, $user));
        return redirect()->route('laravel-forum.view.post', [$forum_id , $post->id]);
    }

    public function forumReplyStore()
    {
        if (!Auth::check()) {
            return Response::make('Unauthorized', 401);
        }

        $user = Auth::user();

        $forum_id = Request::input('forum_id');
        $post_id = Request::input('post_id');

        $data = [
            'author_id' => $user->id,
            'body' => $this->sanitizeData(Request::input('body')),
            'post_id' => $post_id,
        ];

        if (!ForumReply::valid($data)) {
            return redirect()->route('laravel-forum.view.post', [$forum_id, $post_id]);
        }

        $reply = ForumReply::create($data);

        event(new PostReply($reply, $user));
        return redirect()->route('laravel-forum.view.post', [$forum_id, $post_id]);
    }

    private function adminCheck()
    {
        if (!$this->canAdministrate()) {
            return Response::make('Unauthorised', 401);
        }
    }

    private function sanitizeData($data)
    {
        /* Sanitize post input */
        $removals = [
            '/<script\b[^>]*>/',
            '/<\/script\b[^>]*>/'
        ];
        foreach ($removals as $r) {
            $data = preg_replace($r, '', $data);
        }
        return $data;
    }

    public function lockPost($id)
    {
        if (!$this->canAdministrate() && !$this->canModerate()) {
            return Response::make('Unauthorised', 401);
        }

        $post = $this->postExists($id);
        if (!$post) {
            return Response::make('Post not found', 404);
        }

        $post->locked = 1;
        $post->save();
        return Response::make('Post Locked', 200);
    }

    public function unlockPost($id)
    {
        if (!$this->canAdministrate() && !$this->canModerate()) {
            return Response::make('Unauthorised', 401);
        }

        $post = $this->postExists($id);
        if (!$post) {
            return Response::make('Post not found', 404);
        }

        $post->locked = 0;
        $post->save();
        return Response::make('Post Unlocked', 200);
    }

    public function stickyPost($id)
    {
        if (!$this->canAdministrate() && !$this->canModerate()) {
            return Response::make('Unauthorised', 401);
        }

        $post = $this->postExists($id);
        if (!$post) {
            return Response::make('Post not found', 404);
        }

        $post->sticky = 1;
        $post->save();
        return Response::make('Post Unlocked', 200);
    }

    public function unstickyPost($id)
    {
        if (!$this->canAdministrate() && !$this->canModerate()) {
            return Response::make('Unauthorised', 401);
        }

        $post = $this->postExists($id);
        if (!$post) {
            return Response::make('Post not found', 404);
        }

        $post->sticky = 0;
        $post->save();
        return Response::make('Post Unlocked', 200);
    }

    private function postExists($post_id)
    {
        try {
            $post = ForumPost::where('id', $post_id)->firstOrFail();
            return $post;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function postDelete($forum_id, $post_id)
    {
        if (!$this->canAdministrate() && !$this->canModerate()) {
            return Response::make('Unauthorised', 401);
        }

        $post = $this->postExists($post_id);
        if (!$post) {
            return Response::make('Post not found', 404);
        }

        $post->delete();
        return Response::make('Post Deleted', 200);
    }

    public function forumDelete()
    {
        if (!$this->canAdministrate() && !$this->canModerate()) {
            return Response::make('Unauthorised', 401);
        }

        $forum_id = Request::input('forum_id');

        $forum = $this->forumExists($forum_id);
        if (!$forum) {
            return Response::make('Forum not found', 404);
        }

        $forum->delete();
        return Response::make('Forum Deleted', 200);
    }

    private function forumExists($id)
    {
        try {
            $forum = Forum::where('id', $id)->firstOrFail();
            return $forum;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function forumCategoryDelete()
    {
        if (!$this->canAdministrate() && !$this->canModerate()) {
            return Response::make('Unauthorised', 401);
        }

        $cat_id = Request::input('category_id');

        $cat = $this->forumCategoryExists($cat_id);
        if (!$cat) {
            return Response::make('Forum Category not found', 404);
        }

        $cat->delete();
        return Response::make('Forum Category Deleted', 200);
    }

    private function forumCategoryExists($id)
    {
        try {
            $cat = ForumCategory::where('id', $id)->firstOrFail();
            return $cat;
        } catch (\Exception $e) {
            return false;
        }
    }
}
