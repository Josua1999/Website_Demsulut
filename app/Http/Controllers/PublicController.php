<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Event;
use App\Models\Thread;
use App\Models\Research;
use App\Models\Setting;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Display landing page
     */
    public function index()
    {
        $setting = Setting::first();
        $latestBlogs = Blog::with('user')
            ->where('publication_status', 1)
            ->latest()
            ->take(6)
            ->get();
        $upcomingEvents = Event::where('event_status', 1)
            ->orderBy('date_start', 'desc')
            ->take(3)
            ->get();
        $latestThreads = Thread::with('user')
            ->latest()
            ->take(5)
            ->get();
        
        return view('public.index', compact('setting', 'latestBlogs', 'upcomingEvents', 'latestThreads'));
    }

    /**
     * Display public blog list
     */
    public function blogs()
    {
        $setting = Setting::first();
        $blogs = Blog::with('user')
            ->where('publication_status', 1)
            ->latest()
            ->paginate(12);
        
        return view('public.blogs', compact('setting', 'blogs'));
    }

    /**
     * Display single blog post
     */
    public function blogShow($id)
    {
        $setting = Setting::first();
        $blog = Blog::with('user')
            ->where('publication_status', 1)
            ->findOrFail($id);
        $relatedBlogs = Blog::with('user')
            ->where('id', '!=', $id)
            ->where('publication_status', 1)
            ->latest()
            ->take(3)
            ->get();
        
        return view('public.blog-show', compact('setting', 'blog', 'relatedBlogs'));
    }

    /**
     * Display public events list
     */
    public function events()
    {
        $setting = Setting::first();
        $upcomingEvents = Event::where('event_status', 1)
            ->orderBy('date_start', 'desc')
            ->paginate(12);
        
        return view('public.events', compact('setting', 'upcomingEvents'));
    }

    /**
     * Display single event
     */
    public function eventShow($id)
    {
        $setting = Setting::first();
        $event = Event::where('event_status', 1)
            ->findOrFail($id);
        
        return view('public.event-show', compact('setting', 'event'));
    }

    /**
     * Display public forum/threads
     */
    public function forum()
    {
        $setting = Setting::first();
        $threads = Thread::with('user')->latest()->paginate(15);
        
        return view('public.forum', compact('setting', 'threads'));
    }

    /**
     * Display single thread
     */
    public function threadShow($id)
    {
        $setting = Setting::first();
        $thread = Thread::with(['user', 'comments.user'])->findOrFail($id);
        
        return view('public.thread-show', compact('setting', 'thread'));
    }

    /**
     * Display public research/journal list
     */
    public function research()
    {
        $setting = Setting::first();
        $researches = Research::with('user')
            ->latest()
            ->paginate(12);
        
        return view('public.research', compact('setting', 'researches'));
    }

    /**
     * Display about page
     */
    public function about()
    {
        $setting = Setting::first();
        
        return view('public.about', compact('setting'));
    }

    /**
     * Display contact page
     */
    public function contact()
    {
        $setting = Setting::first();
        
        return view('public.contact', compact('setting'));
    }
}
